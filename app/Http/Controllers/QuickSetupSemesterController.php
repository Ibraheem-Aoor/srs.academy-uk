<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\Session;
use App\Models\Student;
use App\Models\Subject;
use App\Services\Moodle\CourseService;
use App\Services\Moodle\SessionService;
use App\Services\Moodle\StudentService;
use Illuminate\Http\Request;
use Throwable;

class QuickSetupSemesterController extends Controller
{
    public function setupSemestersDate()
    {
        $semesters = [
            'Spring_(2024)' => ['start_date' => '2024-03-02', 'end_date' => '2024-05-01'],
            'Summer_(2024)' => ['start_date' => '2024-05-02', 'end_date' => '2024-07-01'],
            'Fall_(2024)' => ['start_date' => '2024-01-01', 'end_date' => '2024-03-01'],
        ];

        foreach ($semesters as $semesterName => $dates) {
            $semester = Semester::where('title', $semesterName)->first();

            if ($semester) {
                $semester->start_date = $dates['start_date'];
                $semester->end_date = $dates['end_date'];
                $semester->save();
            }
        }
        dd('DONE SUCCESSFULLY');
    }


    public function setupSessionDates()
    {
        // Define semesters and their session date ranges
        $semesters = [
            'Spring_(2024)' => [
                'Spring_1' => ['start_date' => '2024-03-02', 'end_date' => '2024-04-01'],
                'Spring_2' => ['start_date' => '2024-04-02', 'end_date' => '2024-05-01'],
            ],
            'Summer_(2024)' => [
                'Summer_1' => ['start_date' => '2024-05-02', 'end_date' => '2024-06-01'],
                'Summer_2' => ['start_date' => '2024-06-02', 'end_date' => '2024-07-01'],
            ],
            'Fall_(2024)' => [
                'Fall_1' => ['start_date' => '2024-01-01', 'end_date' => '2024-02-01'],
                'Fall_2' => ['start_date' => '2024-02-02', 'end_date' => '2024-03-01'],
            ],
        ];

        // Loop through each semester and its sessions
        foreach ($semesters as $semesterName => $sessions) {
            $semester = Semester::where('title', $semesterName)->first();

            if ($semester) {
                foreach ($sessions as $sessionKey => $dates) {
                    $sessionName = "2024_{$sessionKey}";
                    $session = Session::where('title', 'like' , '%'.$sessionName.'%')->first();

                    if ($session) {
                        // Link session to the semester
                        $session->semester_id = $semester->id;

                        // Update session title
                        $session->title = "2024 {$sessionKey}";

                        // Set start and end dates
                        $session->start_date = $dates['start_date'];
                        $session->end_date = $dates['end_date'];

                        // Save the session
                        $session->save();
                    }
                }
            }
        }
        dd('DONE SUCCESSFULLY');
    }

}
