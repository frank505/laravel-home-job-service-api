<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategories extends Model
{
    //
    protected $table = 'sub_categories';
    protected $fillable = ['category_id','sub_category','image'];
}
