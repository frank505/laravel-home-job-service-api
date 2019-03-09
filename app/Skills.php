<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
{
    //
    protected $table = 'skills';
    protected $fillable = ['category_id','sub_category_id','skill']; 
}
