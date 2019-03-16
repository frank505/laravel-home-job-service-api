<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\States;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\SanitizeController;

class StateController extends Controller
{
    //
    protected $states;
public function __construct()
{
    $this->middleware("auth:admins");
     $this->states = new States();
}

public function index(Request $request,$id)
{
    //the id is the country id
  $get_states = $this->states->where(["country_id"=>$id])->get();
  return response()->json([
    'success' => true,
     'data'=>$get_states
], 200);

}



public function store(Request $request)
{

    $validator = Validator::make($request->only("country_id","state"), 
    [
        'country_id' => 'required|integer',
        'state' =>'required|string'
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
    $this->states->country_id = $request->country_id;
    $this->states->isdeleted = 0;
    $this->states->state = $request->state;
    if ($this->states->save()){
        return response()->json([
            'success' => true,
            'states' => $this->states
        ]);
    }   
    else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, states could not be added'
        ], 500);
    }
}

public function update(Request $request,$id)
{
    $states = $this->states->find($id);
    if (!$states) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, states with id ' . $id . ' cannot be found'
        ], 400);
    }

    $validator = Validator::make($request->only("country_id","state"), 
    [
        'country_id' => 'required|string',
         'state'=>'required|string'
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
 
    $update = $this->states::where(["id"=>$id])->update(["country_id"=>$request->country_id,"state"=>$request->state]);

    if ($update) {
        return response()->json([
            'success' => true,
            "message"=>"states updated successfully"
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, states could not be updated'
        ], 500);
    }
}

public function delete(Request $request,$id)
{
    $states = $this->states->find($id);
 
    if (!$states) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, states with id ' . $id . ' cannot be found'
        ], 400);
    }
    //set is deleted property to one meaning it has been deleted
     $states->isdeleted = 1;
   $saved =   $states->save();
    if ($saved) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'states could not be deleted'
        ], 500);
    }
}

}