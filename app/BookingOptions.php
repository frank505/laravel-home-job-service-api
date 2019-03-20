<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingOptions extends Model
{
    //

    protected $table  ="booking_options";
    protected $fillable = ["service_id","booking_id","option_id","option_value","total_amount","option_cost"];

}

