<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('firstname');
            $table->string("lastname");
            $table->string('email')->unique();
            $table->string('password');
            $table->string("phone");
            $table->string("cityid");
            $table->string("roleid");
            $table->string("stateid");
            $table->string("country_id")->nullable()->default(NULL);
            $table->string("what_you_do")->nullable()->default(NULL);
            $table->string("address")->nullable()->default(NULL);
            $table->string("bank")->nullable()->default(NULL);
            $table->string("account_number")->nullable()->default(NULL);
            $table->string("gaurantors_name")->nullable()->default(NULL);
            $table->string("gaurantors_number")->nullable()->default(NULL);
            $table->string("why_you_love_what_you_do")->nullable()->default(NULL);
            $table->string("facebook_handle")->nullable()->default(NULL);
            $table->string("instagram_handle")->nullable()->default(NULL);
            $table->string("last_location")->nullable()->default(NULL);
            $table->string("isOnline")->nullable()->default(NULL);
            $table->string("profilephoto")->nullable()->default(NULL);
            $table->string("status");
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
