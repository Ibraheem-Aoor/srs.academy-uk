<?php

namespace App\Http\Controllers;

class CoursableController extends Controller
{
    /**
     * This controller is used as base controller for other controllers that deals with courses
     * to differ beteeween course types in \App\Enum\CourseTypeEnum
     */
    protected $is_quick_course = false;

    public function __construct()
    {
        $this->is_quick_course = request()->query('quick_course', false);
        view()->share([
            'is_quick_course' =>  $this->is_quick_course,
        ]);
    }
}
