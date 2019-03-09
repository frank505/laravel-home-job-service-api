<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UserSkills;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\SanitizeController;

class UserSkillsController extends Controller
{
    //
    protected $user_skills;
public function __construct()
{
    $this->middleware("auth:users");
     $this->user_skills = new user_skills();
}

public function index($pagination=null,Request $request)
{
//laravel automatically converts it to json and sends a response text too
//$auth = auth("admins")->authenticate($request->token);
if($pagination==null || $pagination==""){
    return $this->user_skills->get(['id','category_id','sub_category_id','skill','created_at'])->toArray();
}
    $paginated_user_skills =  $this->user_skills->paginate($pagination,['id','category_id','sub_category_id','skill','created_at']);
    return response()->json([
        'success' => true,
         'data'=>$paginated_user_skills
    ], 200);
}


public function store(Request $request)
{
    $validator = Validator::make($request->all(), 
    [
        'user_id' => 'required|integer',
        'skill_id' => 'required|integer',
        'rating' => 'required|string',
        'years_of_experience' => 'required|integer',
        'isdeleted' => 'required|integer',
    ]
     );
     
    if($validator->fails()){
        return response()->json([
            "success"=>false,
            "message"=>$validator->messages()->toArray(),
           ],400); 
      }   
 
   $create =   $this->user_skills::create(
          [
             // 'category_id'=>$request->category_id,
              'user_id'=>$request->user_id,
              'skill_id'=>$request->skill_id,
              'rating'=>$request->rating,
              'years_of_experience' =>$request->years_of_experience,
              'isdeleted'=>$request->isdeleted
          ]
        );

    if ($create){
        return response()->json([
            'success' => true,
            'user_skills' => $this->user_skills
        ]);
    }   
    else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user_skills could not be added'
        ], 500);
    }
}

public function update(Request $request,$id)
{
    $user_skills = $this->user_skills->find($id);
    if (!$user_skills) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user skills with id ' . $id . ' cannot be found'
        ], 400);
    }

    $validator = Validator::make($request->all(), 
    [
        'user_id' => 'required|integer',
        'skill_id' => 'required|integer',
        'rating' => 'required|string',
        'years_of_experience' => 'required|integer',
        'isdeleted' => 'required|integer',
    ]
     );
     
    if($validator->fails()){
        return response()->json([
            "success"=>false,
            "message"=>$validator->messages()->toArray(),
           ],400); 
      }
 
    $update = $user_skills->fill($request->all())->save();

    if ($update) {
        return response()->json([
            'success' => true,
            "message"=>"user_skills updated successfully"
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user_skills could not be updated'
        ], 500);
    }
}

public function delete(Request $request,$id)
{
    $user_skills = $this->user_skills->find($id);
 
    if (!$user_skills) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user_skills with id ' . $id . ' cannot be found'
        ], 400);
    }
    $user_skills->isdeleted = 1;
    if ($user_skills->save()) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'user_skills could not be deleted'
        ], 500);
    }
}

public function UserSkills(Request $request,$pagination=null)
{

    $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
 
        $user = auth("users")->authenticate($request->token);
        $user_id = $user->id;


    $specific_sub_cat = $this->user_skills->where(["user_id"=>$user_id])->count();
    if($specific_sub_cat==0){
        return response()->json([
            'success' => false,
            'message' => 'Sorry, this user hasnt added any skills just yet'
        ], 400);
    }

    if($pagination==null || $pagination==""){
        return $this->user_skills->where(["user_id"=>$user_id])->
        get(['user_id','skill_id','rating','years_of_experience','isdeleted'])->toArray();
    }
        $paginated_user_skills =  $this->user_skills->where(["user_id"=>$user_id])->
        paginate($pagination,['user_id','skill_id','rating','years_of_experience','isdeleted']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_user_skills
        ], 200);

}
//end of this class

}
