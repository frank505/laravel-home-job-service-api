<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Countries;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\SanitizeController;

class CountriesController extends Controller
{

    protected $countries;
public function __construct()
{
    $this->middleware("auth:admins");
     $this->countries = new Countries();
}

public function index($pagination=null,Request $request)
{
//laravel automatically converts it to json and sends a response text too
//$auth = auth("admins")->authenticate($request->token);
if($pagination==null || $pagination==""){
    return $this->countries->get(['id', 'country', 'isdeleted','created_at'])->toArray();
}
    $paginated_countries =  $this->countries->paginate($pagination,['id', 'country', 'isdeleted','created_at']);
    return response()->json([
        'success' => true,
         'data'=>$paginated_countries
    ], 200);
}



public function store(Request $request)
{

    $validator = Validator::make($request->only("country"), 
    [
        'country' => 'required|string',
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
    $this->countries->country = $request->country;
    $this->countries->isdeleted = 0;
 
    if ($this->countries->save()){
        return response()->json([
            'success' => true,
            'countries' => $this->countries
        ]);
    }   
    else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, countries could not be added'
        ], 500);
    }
}

public function update(Request $request,$id)
{
    $countries = $this->countries->find($id);
    if (!$countries) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, countries with id ' . $id . ' cannot be found'
        ], 400);
    }

    $validator = Validator::make($request->all(), 
    [
        'country' => 'required|string',
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
 
    $update = $countries->fill($request->all())->save();

    if ($update) {
        return response()->json([
            'success' => true,
            "message"=>"countries updated successfully"
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, countries could not be updated'
        ], 500);
    }
}

public function delete(Request $request,$id)
{
    $countries = $this->countries->find($id);
 
    if (!$countries) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, countries with id ' . $id . ' cannot be found'
        ], 400);
    }
    //set is deleted property to one meaning it has been deleted
     $countries->isdeleted = 1;
   $saved =   $countries->save();
    if ($saved) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'countries could not be deleted'
        ], 500);
    }
}

//end of this class
}
