<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_enrolls', function (Blueprint $table) {
            if (Schema::hasColumn('student_enrolls', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }
        });

        Schema::table('class_routines', function (Blueprint $table) {
            if (Schema::hasColumn('class_routines', 'semester_id')) {
                $table->dropForeign(['semester_id']);
                $table->dropColumn('semester_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_enrolls', function (Blueprint $table) {
            if (!Schema::hasColumn('student_enrolls', 'semester_id')) {
                $table->unsignedBigInteger('semester_id')->nullable();
                $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            }
        });

        Schema::table('class_routines', function (Blueprint $table) {
            if (!Schema::hasColumn('class_routines', 'semester_id')) {
                $table->unsignedBigInteger('semester_id')->nullable();
                $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');
            }
        });
    }
};
