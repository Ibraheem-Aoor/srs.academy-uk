<?php

namespace App\Console\Commands;

use App\Models\EnrollSubject;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudentEnroll;
use App\Services\Moodle\CourseService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class AutoSwitchCurrentSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current-session:switch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Switching For The Current Session According To The Date';

    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            StudentEnroll::query()->where('status', '1')->chunkById(20, function ($enrolls) {
                foreach ($enrolls as $enroll) {
                    if ($enroll->session->end_date == Carbon::today()->toDateString()) {
                        $enroll->status = 0;
                        $enroll->save();
                        info('STUDENT ENROLL WITH ENROLL_ID : ' . $enroll->id . ' HAS BEEN DEACTIVATED');
                    }
                }
            });
        } catch (Throwable $e) {
            logError(e: $e, method: __METHOD__, class: get_class($this), custom_message: __('Moodle_Error'));
        }
    }



}
