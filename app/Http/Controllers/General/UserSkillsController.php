<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserSkills;
use Validator;
use App\Http\Controllers\SanitizeController;

class UserSkillsController extends Controller
{
    //
    protected $user_skills;
    public function __construct()
    {
         $this->user_skills = new user_skills();
    }

    
    public function UserSkills(Request $request,$id,$pagination=null)
{
    $specific_sub_cat = $this->user_skills->where(["user_id"=>$id])->count();
    if($specific_sub_cat==0){
        return response()->json([
            'success' => false,
            'message' => 'Sorry, this user hasnt added any skills just yet'
        ], 400);
    }
 
    if($pagination==null || $pagination==""){
        return $this->user_skills->where(["user_id"=>$id])->
        get(['user_id','skill_id','rating','years_of_experience','isdeleted'])->toArray();
    }
        $paginated_user_skills =  $this->user_skills->where(["user_id"=>$id])->
        paginate($pagination,['user_id','skill_id','rating','years_of_experience','isdeleted']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_user_skills
        ], 200);

}


}
