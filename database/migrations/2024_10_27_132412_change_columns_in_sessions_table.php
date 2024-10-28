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
            $table->dropForeign('student_enrolls_session_id_foreign');
            $table->dropColumn('semester_id');
        });
        Schema::table('class_routines', callback: function (Blueprint $table) {
            $table->dropForeign('class_routines_semester_id_foreign');
            $table->dropColumn('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            //
        });
    }
};
