<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentEnroll;
use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\Program;
use App\Models\Section;
use App\Models\Session;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use App\Rules\SubjectMustBeOfferedWithSingleEnrollmentSession;
use App\Services\Moodle\StudentEnrollService;
use Toastr;
use Auth;
use Illuminate\Support\Facades\DB;

class StudentSingleEnrollController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Module Data
        $this->title = trans_choice('module_single_enroll', 1);
        $this->route = 'admin.single-enroll';
        $this->view = 'admin.single-enroll';
        $this->path = 'student';
        $this->access = 'student-enroll';


        $this->middleware('permission:' . $this->access . '-single');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['view'] = $this->view;
        $data['path'] = $this->path;
        $data['access'] = $this->access;
        $data['students'] = Student::query()->where('status', '1')->orderBy('student_id', 'asc')->get();
        $data['current_session'] = Session::query()->where('current', 1)->first();
        if (!empty($request->student) && $request->student != Null) {

            $data['selected_student'] = $request->student;

            $data['row'] = $student = Student::where('student_id', $request->student)->first();

            // Filter Enroll Data
            $data['programs'] = Program::where('status', '1')->orderBy('title', 'asc')->get();

            $data['sessions'] = Session::with('programs')->whereHas('programs', function ($query) use ($student) {
                $query->where('program_id', $student->program_id);
            })->where('status', '1')->orderBy('id', 'desc')->get();

            $data['semesters'] = Semester::with('programs')->whereHas('programs', function ($query) use ($student) {
                $query->where('program_id', $student->program_id);
            })->where('status', '1')->orderBy('id', 'asc')->get();

            $data['sections'] = Section::with('semesterPrograms')->whereHas('semesterPrograms', function ($query) use ($student) {
                $query->where('program_id', $student->program_id);
            })->where('status', '1')->orderBy('title', 'asc')->get();


            $data['grades'] = Grade::where('status', '1')->orderBy('min_mark', 'desc')->get();
        } else {
            $data['selected_student'] = Null;
        }
        return view($this->view . '.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     */
    public function store(Request $request, StudentEnrollService $moodle_stuent_enroll_service)
    {
        // Field Validation
        $request->validate([
            'student' => 'required',
            'program' => 'required',
            'session' => 'required',
            'section' => 'nullable',
            'subjects' => ['required' ,new SubjectMustBeOfferedWithSingleEnrollmentSession($request->student , $request->session)],
        ]);
        try {
            DB::beginTransaction();
            // Duplicate Enroll Check
            $duplicate_check = StudentEnroll::where('student_id', $request->student)->where('session_id', $request->session)->first();
            // $semester_check = StudentEnroll::where('student_id', $request->student)->where('semester_id', $request->semester)->first();

            if (!isset($duplicate_check)) {
                // Pre Enroll Update
                $pre_enroll = StudentEnroll::where('student_id', $request->student)->where('status', '1')->first();
                if (isset($pre_enroll)) {
                    $pre_enroll->status = '0';
                    $pre_enroll->save();
                }

                // Student New Enroll
                $enroll = new StudentEnroll;
                $enroll->student_id = $request->student;
                $enroll->program_id = $request->program;
                $enroll->session_id = $request->session;
                $enroll->semester_id = Session::query()->find($request->session)->semester_id;
                $enroll->section_id = $request->section ?? null;
                $enroll->created_by = Auth::guard('web')->user()->id;
                $enroll->status = 1;
                $enroll->save();

                // Attach Subject
                $enroll->subjects()->attach($request->subjects);
                // get the student
                $student = Student::find($request->student);

                //!! Disable All Enrolls And Make The Current Enroll Where The Current Session !!
                $student->studentEnrolls()->update(['status' => 0]);
                // Get The CURRENT SESSION
                $current_running_session = Session::query()->where('current', 1)->first();
                $student->studentEnrolls()->where('session_id', $current_running_session->id)->update(['status' => 1]);

                // Program Update
                $student->program_id = $request->program;
                $student->save();

                $moodle_stuent_enroll_service->store($enroll);
                Toastr::success(__('msg_promoted_successfully'), __('msg_success'));
            } else {
                Toastr::error(__('msg_enroll_already_exists'), __('msg_error'));
            }
            DB::commit();

            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            Toastr::error(__('msg_created_error'), __('msg_error'));

            return redirect()->back();
        }
    }
}
