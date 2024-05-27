<?php
namespace App\Services\Moodle;

use App\Models\Program;
use App\Models\Session;
use App\Models\StudentEnroll;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class StudentEnrollService extends BaseService
{
    protected $model = StudentEnroll::class;
    protected RoleService $role_service;
    public function __construct()
    {
        parent::__construct();
        $this->role_service = new RoleService();
    }

    /**
     * Create The Program On Moodle.
     */
    public function create(StudentEnroll $student_enroll)
    {
        try {
            //bring the student role id  from moodle
            $student_role_id = $this->role_service->getStudentRoleId();
            // Create the data array for the POST request
            $enrolments = [];
            foreach ($student_enroll->subjects as $subject) {
                $enrolments[] = [
                    'roleid' => $student_role_id,
                    'courseid' => $subject->id_on_moodle,
                    'userid' => $student_enroll->student->id_on_moodle,
                    'timestart' => strtotime($student_enroll->session->start_date),
                    'timeend' => strtotime($student_enroll->session->end_date),
                ];
            }
            // Set the web service function name
            $this->params['wsfunction'] = 'enrol_manual_enrol_users';

            //data to save sent along with the query parameters.
            $this->params['enrolments'] = $enrolments;

            // Build the full URL with the parameters
            $this->url = $this->url . '?' . http_build_query($this->params);

            // Send the POST request with  parameters
            $response = $this->http->post(
                $this->url
            )->json();
            return !isset($response['exception']) ? $response : throw new \Exception($response['exception']);
        } catch (Throwable $e) {
            logError(e: $e, method: __METHOD__, class: get_class($this));
            return back();
        }
    }

    /**
     * Create The Program On Moodle.
     */
    public function sync(StudentEnroll $student_enroll)
    {
        try {
            // check each subject if the user is enrolled to in moodle
            foreach ($student_enroll->subjects as $subject) {
                $exists = 0;
                $this->params['courseid'] = $subject->id_on_moodle;
                $this->params['wsfunction'] = 'core_enrol_get_enrolled_users';
                // Build the full URL with the parameters
                $url = $this->url . '?' . http_build_query($this->params);

                // Send the POST request with  parameters
                $current_enrollments = $this->http->post(
                    $url
                )->json();
                if($current_enrollments != [])
                {
                    dd($current_enrollments);
                }
                // if not enrolled. then make him enrolled.
                foreach ($current_enrollments as $current_enrollment) {
                    if ($current_enrollment['id'] == $student_enroll->student->id_on_moodle) {
                        $exists = 1;
                        break;
                    }
                }
                if (!$exists) {
                    $this->enrollSingleSubject($student_enroll, $subject);
                }
            }//end loop for subject enrollments
            return back();
        } catch (Throwable $e) {
            dd($e);
            logError(e: $e, method: __METHOD__, class: get_class($this));
            return back();
        }
    }



    public function enrollSingleSubject(StudentEnroll $student_enroll, Subject $subject)
    {
        try {
            //bring the student role id  from moodle
            $student_role_id = $this->role_service->getStudentRoleId();
            // Create the data array for the POST request
            $enrolments = [];
            $enrolments[] = [
                'roleid' => $student_role_id,
                'courseid' => $subject->id_on_moodle,
                'userid' => $student_enroll->student->id_on_moodle,
                'timestart' => strtotime($student_enroll->session->start_date),
                'timeend' => strtotime($student_enroll->session->end_date),
            ];
            // Set the web service function name

            //data to save sent along with the query parameters.
            $query_params = array_except($this->params , 'wsfunction');
            $query_params['enrolments'] = $enrolments;
            $query_params['wsfunction'] = 'enrol_manual_enrol_users';
            // Build the full URL with the parameters
            $url = $this->url . '?' . http_build_query($query_params);

            // Send the POST request with  parameters
            $response = $this->http->post(
                $url
            )->json();
            dd($response ,   $url);
            return !isset($response['exception']) ? $response : throw new \Exception($response['exception']);
        } catch (Throwable $e) {
            dd($e);
            logError(e: $e, method: __METHOD__, class: get_class($this));
            return back();
        }
    }




}
