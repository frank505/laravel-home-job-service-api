<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceFormOptions extends Model
{
    protected $table = 'service_form_options';
    protected $fillable = ["service_id","type","title","name","display","required","order","ispublic","price","options","selected"];
    //
}
