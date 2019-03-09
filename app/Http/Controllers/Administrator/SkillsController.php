<?php

namespace App\Http\Controllers\Administrator;

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
    protected $admin;
    protected $skills;
public function __construct()
{
    $this->middleware("auth:admins");
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


public function store(Request $request)
{

    $validator = Validator::make($request->all(), 
    [
        //'category_id' => 'required|integer',
        'sub_category_id' => 'required|integer',
        'skill'=>'required|string'
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
 
   $create =   $this->skills::create(
          [
             // 'category_id'=>$request->category_id,
              'sub_category_id'=>$request->sub_category_id,
              'skill'=>$request->skill
          ]
        );

    if ($create){
        return response()->json([
            'success' => true,
            'skills' => $this->skills
        ]);
    }   
    else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, skills could not be added'
        ], 500);
    }
}

public function update(Request $request,$id)
{
    $skills = $this->skills->find($id);
    if (!$skills) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, skills with id ' . $id . ' cannot be found'
        ], 400);
    }

    $validator = Validator::make($request->all(), 
    [
     //'category_id' => 'required|integer',
     'sub_category_id' => 'required|integer',
     'skill'=>'required|string'
    ]
     );
     
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
 
    $update = $skills->fill($request->all())->save();

    if ($update) {
        return response()->json([
            'success' => true,
            "message"=>"skills updated successfully"
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, skills could not be updated'
        ], 500);
    }
}

public function delete(Request $request,$id)
{
    $skills = $this->skills->find($id);
 
    if (!$skills) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, skills with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    if ($skills->delete()) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'skills could not be deleted'
        ], 500);
    }
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
