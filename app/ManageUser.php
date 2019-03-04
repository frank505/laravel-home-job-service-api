<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManageUser extends Model
{
    //
    protected $table = 'users';
    protected $fillable = ["status"];
}
