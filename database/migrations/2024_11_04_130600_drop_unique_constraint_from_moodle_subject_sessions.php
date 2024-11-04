<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moodle_subject_sessions', function (Blueprint $table) {
            // Drop the unique constraint
            $table->dropUnique(['subject_id', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moodle_subject_sessions', function (Blueprint $table) {
            // Recreate the unique constraint if needed
            $table->unique(['subject_id', 'session_id']);
        });
    }
};
