<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\Student;
use App\Models\Subject;
use App\Services\Moodle\CourseService;
use App\Services\Moodle\SessionService;
use App\Services\Moodle\StudentService;
use Illuminate\Http\Request;
use Throwable;

class SyncDataWithMoodleController extends Controller
{
    /**
     * This Class assumes that we have data already exisits on moodle. so we need to bring the ids to link with our srs.
     */


    #=-----------
    public function syncSessions(SessionService $moodle_session_service)
    {
        try {
            $query_params['wsfunction'] = 'core_course_get_categories';
            $moodle_sesssions = $moodle_session_service->get($query_params);
            foreach ($moodle_sesssions as $moodle_session) {
                if ($srs_session = Session::query()->where('title', $moodle_session['name'])->first()) {
                    $srs_session->update(['id_on_moodle' => $moodle_session['id']]);
                }
            }
            dd('Done Successfully');
        } catch (Throwable $e) {
            dd($e);
        }
    }
    public function syncCourses(CourseService $moodle_course_service)
    {
        try {
            $query_params['wsfunction'] = 'core_course_get_courses';
            $moodle_courses = $moodle_course_service->get($query_params);

            foreach ($moodle_courses as $moodle_course) {
                if ($srs_course = Subject::query()->where('title', $moodle_course['fullname'])->orWhere('code', @$moodle_course['shortname'])->first()) {
                    $srs_course->update(['id_on_moodle' => $moodle_course['id']]);
                }
            }
            dd('Done Successfully');
        } catch (Throwable $e) {
            dd($e);
        }
    }

    
    public function syncStudents(StudentService $moodle_student_service)
    {
        try {
            $query_params['wsfunction'] = 'core_user_get_users';
            $s = Student::query()->chunkById(10, function ($students) use ($query_params, $moodle_student_service) {
                foreach ($students as $student) {
                    $query_params['criteria'] = [
                        [
                            'key' => 'email',
                            'value' => $student->email,
                        ]
                    ];
                    $moodle_students = $moodle_student_service->get($query_params);
                    $student->update([
                        'id_on_moodle' => $moodle_students['users'][0]['id'],
                    ]);
                }
            });
            dd('Done Successfully' , $s);
        } catch (Throwable $e) {
            dd($e);
        }
    }
}
