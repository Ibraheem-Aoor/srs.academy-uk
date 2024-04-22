<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramSubject extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'program_id' , 'subject_type' , 'exam_type_category_id'];


    public function examTypeCategory() : BelongsTo
    {
        return $this->belongsTo(ExamTypeCategory::class, 'exam_type_category_id');a
    }
}