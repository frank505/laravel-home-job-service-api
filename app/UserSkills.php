<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkills extends Model
{
    //
    protected $table = 'user_skills';
    protected $fillable = ['user_id','skill_id','rating','years_of_experience','isdeleted'];

    
}
