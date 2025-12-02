<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_groups', function (Blueprint $table) {
            $table->text('special_requirements')->nullable()->after('number_of_students');
        });
    }

    public function down()
    {
        Schema::table('student_groups', function (Blueprint $table) {
            $table->dropColumn('special_requirements');
        });
    }
};
