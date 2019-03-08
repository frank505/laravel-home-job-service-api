<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceFormOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_form_options', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->integer("service_id"); 
             $table->string("type");
             $table->string("name");
             $table->string("display");
             $table->string("required");
             $table->string("order");
             $table->boolean('ispublic');
             $table->integer("price");
             $table->json('options')->nullable(); //json_encoded_array
             $table->string("selected");
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
        Schema::dropIfExists('service_form_options');
    }
}
