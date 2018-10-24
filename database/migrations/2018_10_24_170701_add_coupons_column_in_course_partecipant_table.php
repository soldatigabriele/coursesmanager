<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCouponsColumnInCoursePartecipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_partecipant', function (Blueprint $table) {
            $table->unsignedInteger('coupon_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_partecipant', function (Blueprint $table) {
            $table->dropColumn('coupon_id');
        });
    }
}
