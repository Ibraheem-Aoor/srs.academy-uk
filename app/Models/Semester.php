<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasStatus;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'year', 'status', 'start_date' , 'end_date'
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_semester', 'semester_id', 'program_id');
    }

    public function programSections()
    {
        return $this->hasMany(ProgramSemesterSection::class, 'semester_id', 'id');
    }

    public function studentEnrolls()
    {
        return $this->hasMany(StudentEnroll::class, 'semester_id');
    }

    public function classes()
    {
        return $this->hasMany(ClassRoutine::class, 'semester_id', 'id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'semester_id', 'id');
    }
}
