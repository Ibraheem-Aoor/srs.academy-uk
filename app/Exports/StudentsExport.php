<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $students = Student::get(['student_id', 'admission_date', 'first_name', 'last_name', 'father_name', 'mother_name', 'email', 'gender', 'dob', 'phone', 'password_text']);
            // Decrypt the password_text for each student
        $students->transform(function ($student) {
            $student->password_text = Crypt::decryptString($student->password_text);
            return $student;
        });

        return $students;
    }


    public function headings(): array
    {
        return ['student_id', 'admission_date', 'first_name', 'last_name', 'father_name', 'mother_name', 'email', 'gender', 'dob', 'phone', 'password'];
    }
}
