<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersGeolocationDetails extends Model
{
    //
    protected $table = 'users_geolocation';
    protected $fillable = ['user_id','user_name','longitude','latitude','status'];

}
