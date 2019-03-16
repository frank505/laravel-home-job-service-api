<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    //
    protected $table = 'reviews';    
 protected $fillable = ['customer_id','artisan_id','service_id','booking_id','review','rating','review_date'];
}
