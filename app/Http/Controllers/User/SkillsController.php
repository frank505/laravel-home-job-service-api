<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Skills;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\SanitizeController;

class SkillsController extends Controller
{
    //
    protected $skills;
public function __construct()
{
    $this->middleware("auth:users");
     $this->skills = new Skills();
}

public function index($pagination=null,Request $request)
{
//laravel automatically converts it to json and sends a response text too
//$auth = auth("admins")->authenticate($request->token);
if($pagination==null || $pagination==""){
    return $this->skills->get(['id','category_id','sub_category_id','skill','created_at'])->toArray();
}
    $paginated_skills =  $this->skills->paginate($pagination,['id','category_id','sub_category_id','skill','created_at']);
    return response()->json([
        'success' => true,
         'data'=>$paginated_skills
    ], 200);
}

public function viewSkillsForSubCategory(Request $request,$id,$pagination=null)
{
    $specific_sub_cat = $this->skills->where(["sub_category_id"=>$id])->count();
    if($specific_sub_cat==0){
        return response()->json([
            'success' => false,
            'message' => 'Sorry, skills with sub category id ' . $id . ' cannot be found'
        ], 400);
    }
 
    if($pagination==null || $pagination==""){
        return $this->skills->where(["sub_category_id"=>$id])->
        get(['id','category_id','sub_category_id','skill','created_at'])->toArray();
    }
        $paginated_skills =  $this->skills->where(["sub_category_id"=>$id])->paginate($pagination,['id','category_id','sub_category_id','skill','created_at']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_skills
        ], 200);

}
//end of this class

}
