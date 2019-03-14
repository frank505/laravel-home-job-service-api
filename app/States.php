<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    //
    protected $table = "states";
    protected $fillable = ['country_id','isdeleted','states'];
}
