<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {

        Schema::table('student_enrolls', function (Blueprint $table) {
            $table->integer('program_id')->unsigned()->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('student_enrolls', function (Blueprint $table) {
            $table->integer('program_id')->unsigned();
        });
    }
};
