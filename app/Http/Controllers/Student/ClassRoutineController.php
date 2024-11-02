<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentEnroll;
use Illuminate\Http\Request;
use App\Models\ClassRoutine;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;

class ClassRoutineController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Module Data
        $this->title = trans_choice('module_class_routine', 1);
        $this->route = 'student.class-routine';
        $this->view = 'student.class-routine';
        $this->path = 'class-routine';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['view'] = $this->view;
        $data['path'] = $this->path;
        $student_id = Auth::guard('student')->user()->id;

        // Get All Auth Active Student Enrollments "degrees/courses"
        $enrolls = StudentEnroll::where('student_id', $student_id)
            ->where('status', '1')
            ->with(['subjects'])
            ->get();

        if (isset($enrolls) && !$enrolls->isEmpty()) {
            $subject_ids = [];
            $enrolls->each(function ($enroll) use (&$subject_ids) {
                $subject_ids = array_merge($subject_ids, $enroll->subjects->pluck('id')->toArray());
            });

            $session_ids = $enrolls->pluck('session_id')->toArray();
            $program_ids = $enrolls->pluck('program_id')->toArray();
            // Class Routine
            if (isset($enrolls) && isset($session_ids)) {
                $data['rows'] = ClassRoutine::where('status', '1')
                    ->whereIn('session_id', $session_ids)
                    // ->whereIn('program_id', $program_ids)
                    ->whereIn('subject_id', $subject_ids)
                    ->orderBy('start_time', 'asc')
                    ->get();
            }
        }

        return view($this->view . '.index', $data);
    }
}
