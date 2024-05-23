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




}
