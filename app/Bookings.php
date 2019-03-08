<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings extends Model
{
    //
    protected $fillable = ["user_id","service_id","location","artisan_id","time","address",
    "total_cost","status","scheduledate","completedate"];
    protected $table = 'bookings';
}
