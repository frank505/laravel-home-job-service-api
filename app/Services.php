<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    //
    protected $table = 'services';
    protected $fillable = ['service','category_id','isdeleted','description','image'];

}
