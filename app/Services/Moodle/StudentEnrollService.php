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
    public function store(StudentEnroll $student_enroll)
    {
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
                'suspend' => 0,
            ];
        }
        // Set the web service function name
        $query_params['wsfunction'] = 'enrol_manual_enrol_users';

        //data to save sent along with the query parameters.
        $query_params['enrolments'] = $enrolments;
        return parent::update($query_params);
    }


    /**
     * Sync the student's enrollments on Moodle.
     */
    public function sync(StudentEnroll $student_enroll, array $requested_subjects)
    {
        // Retrieve the student role ID from Moodle
        $student_role_id = $this->role_service->getStudentRoleId();

        // Get currently enrolled subjects
        $currently_enrolled_subjects = $student_enroll->subjects()->pluck('id')->toArray();

        // Determine subjects to enroll in and subjects to drop
        $subjects_to_enroll = array_diff($requested_subjects, $currently_enrolled_subjects);
        $subjects_to_drop = array_diff($currently_enrolled_subjects, $requested_subjects);
        // Prepare enrolments data
        $enrolments = [];

        // Add enrolments for new subjects
        foreach ($subjects_to_enroll as $subject_id) {
            $subject = Subject::find($subject_id);
            $enrolments[] = [
                'roleid' => $student_role_id,
                'courseid' => $subject->id_on_moodle,
                'userid' => $student_enroll->student->id_on_moodle,
                'timestart' => strtotime($student_enroll->session->start_date),
                'timeend' => strtotime($student_enroll->session->end_date),
                'suspend' => 0,
            ];
        }

        // Suspend enrolments for dropped subjects
        foreach ($subjects_to_drop as $subject_id) {
            $subject = Subject::find($subject_id);
            $enrolments[] = [
                'roleid' => $student_role_id,
                'courseid' => $subject->id_on_moodle,
                'userid' => $student_enroll->student->id_on_moodle,
                'timestart' => strtotime($student_enroll->session->start_date),
                'timeend' => strtotime($student_enroll->session->end_date),
                'suspend' => 1,
            ];
        }
        if (isset($enrolments) && !empty($enrolments)) {
            // Set the web service function name
            $query_params['wsfunction'] = 'enrol_manual_enrol_users';

            // Add enrolments to the query parameters
            $query_params['enrolments'] = $enrolments;

            // Send the enrolment data to Moodle
            return parent::update($query_params);
        }
    }





}
