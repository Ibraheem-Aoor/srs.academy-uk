<?php

namespace App\Observers;

use App\Models\ClassRoutine;

class ClassRoutineObserver
{
    /**
     * Handle the ClassRoutine "created" event.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return void
     */
    public function created(ClassRoutine $classRoutine)
    {
        // dd($classRoutine);
    }

    /**
     * Handle the ClassRoutine "updated" event.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return void
     */
    public function updated(ClassRoutine $classRoutine)
    {
        dd($classRoutine);

    }

    /**
     * Handle the ClassRoutine "deleted" event.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return void
     */
    public function deleted(ClassRoutine $classRoutine)
    {
        //
    }

    /**
     * Handle the ClassRoutine "restored" event.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return void
     */
    public function restored(ClassRoutine $classRoutine)
    {
        //
    }

    /**
     * Handle the ClassRoutine "force deleted" event.
     *
     * @param  \App\Models\ClassRoutine  $classRoutine
     * @return void
     */
    public function forceDeleted(ClassRoutine $classRoutine)
    {
        //
    }
}
