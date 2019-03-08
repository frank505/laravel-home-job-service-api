<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingServiceOptions extends Model
{
    protected $fillable = ["service_id","description","name","type","required",
"selected","display","order","options","price"];
    protected $table = 'booking_service_options';
    //
    
}
