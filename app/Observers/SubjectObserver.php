<?php

namespace App\Observers;

use App\Enums\CourseTypeEnum;
use App\Jobs\DuplicateMoodleCourseJob;
use App\Models\EnrollSubject;
use App\Models\MoodleSubjectSession;
use App\Models\Session;
use App\Models\Subject;
use App\Services\Moodle\CourseService;
use App\Services\Moodle\SessionService;
use Illuminate\Support\Facades\DB;

class SubjectObserver
{
    public CourseService $moodle_course_service;
    public SessionService $moodle_session_service;
    public function __construct()
    {
        $this->moodle_course_service = new CourseService();
        $this->moodle_session_service = new SessionService();
    }
    /**
     * Handle the Subject "created" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function created(Subject $subject)
    {

        try {
            if ($subject->type == CourseTypeEnum::QUICK_COURSE) {
                DB::beginTransaction();
                // Insert Data
                $session = new Session;
                $session->title = $subject->title;
                $session->save();
                // Create Session 'mdl_category' On Moodle
                $session_on_moodle = $this->moodle_session_service->store($session);
                $session->id_on_moodle = $session_on_moodle[0]['id'];
                $session->type = CourseTypeEnum::QUICK_COURSE;
                $session->save();
                $this->syncSubjectsWithMoodle($subject, $session);
                DB::commit();
            }

        } catch (\Throwable $e) {
            dd($e);
            DB::rollBack();
            logError(e: $e, method: __METHOD__, class: get_class($this));
        }
    }

    /**
     * Sync the given subjects with Moodle.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $subjects
     * @param  \App\Models\Session  $session
     * @return void
     */
    private function syncSubjectsWithMoodle($subject, $session)
    {
        $enroll_data = [
            'session_id' => $session->id,
        ];
        $enroll  = EnrollSubject::query()->firstOrCreate($enroll_data , $enroll_data);
        $enroll->subjects()->sync([$subject->id]);

        $is_subject_exists_on_moodle_at_all = MoodleSubjectSession::query()->where('subject_id', $subject->id)
            ->where('session_id', $session->id)->exists();
        if (!$is_subject_exists_on_moodle_at_all) {
            $created_course_on_moodle = $this->moodle_course_service->store($subject, $session);
            MoodleSubjectSession::query()->updateOrCreate([
                'session_id' => $session->id,
                'subject_id' => $subject->id,
            ], [
                'session_id' => $session->id,
                'subject_id' => $subject->id,
                'id_on_moodle' => $created_course_on_moodle[0]['id'],
            ]);
            $subject->save();
        }
    }
    /**
     * Handle the Subject "updated" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function updated(Subject $subject)
    {
        //
    }

    /**
     * Handle the Subject "deleted" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function deleted(Subject $subject)
    {
        //
    }

    /**
     * Handle the Subject "restored" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function restored(Subject $subject)
    {
        //
    }

    /**
     * Handle the Subject "force deleted" event.
     *
     * @param  \App\Models\Subject  $subject
     * @return void
     */
    public function forceDeleted(Subject $subject)
    {
        //
    }
}
