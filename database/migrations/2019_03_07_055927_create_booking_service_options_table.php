<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingServiceOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_service_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("service_id");
            $table->string("description");
            $table->string("name");
            $table->string("type");
            $table->string("required");
            $table->string("selected");
            $table->string("display");
            $table->string("order");
           $table->string("options");
            $table->integer("price");
            $table->timestamps();
            //service_id
            //description
            //name
            //type
            //required
            //selected
            //display
            //order
            //options
            //price
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_service_options');
    }
}
