<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id");
            $table->integer("service_id");
            $table->string("location");
            $table->string("artisan_id");
            $table->date('time');
            $table->string("address");
            $table->string("total_cost");
            $table->integer("status");
            $table->date("scheduledate");
            $table->date("completedate");
            //user_id
            // service_id
            // location
            // artisan_id
            // time
            // address
            // total_cost
            // status
            // scheduledate
            // completedate
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
