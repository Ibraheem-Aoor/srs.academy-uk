<?php

namespace App\Models\Scopes;

use App\Enums\CourseTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CourseTypeScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->when(request()->has('quick_course') && request()->quick_course == true, function ($query): void {
            $query->where('type', CourseTypeEnum::QUICK_COURSE);
        });
        // $builder->when(request()->quick_course == null, function ($query): void {
        //     $query->where('type', CourseTypeEnum::CERTIFICATE);
        // });

        $model->when(request()->has('quick_course') && request()->quick_course == true, function ($query): void {
            $query->where('type', CourseTypeEnum::QUICK_COURSE);
        });
        // $model->when(request()->quick_course == null, function ($query): void {
        //     $query->where('type', CourseTypeEnum::CERTIFICATE);
        // });


    }
}
