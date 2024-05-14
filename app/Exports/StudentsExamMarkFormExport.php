<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExamMarkFormExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(protected $students , protected $marks)
    {

    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->students;
    }


    public function headings(): array
    {
        return ['student_id', 'first_name', 'last_name' , 'attendance' , 'achieve_marks' , 'note'];
    }
}
