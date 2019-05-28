<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers\SanitizeController;
use Validator;
use JWTAuth;
use App\UsersGeolocationDetails;

class GeolocationController extends Controller
{
    //
    private $geolocator;
    public function __construct()
    {
        $this->middleware("auth:users");
        $this->geolocator = new UsersGeolocationDetails;
    }


    public function ArtisanGeoLocationUpdate(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'token' => 'required|string',
            'latitude'=>'required|string',
            'longitude'=>'required|string'
                ]
    );
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
     
      $user = auth("users")->authenticate($request->token);
        $user_id = $user->id;

     $check_if_user_online_saved = $this->geolocator::where(["user_id"=>$user_id])->count();
    if($check_if_user_online_saved==0)
    {
        $this->geolocator::create(
            [
           "user_id"=>$user_id,
           "user_name"=>$user->firstname,
           "longitude"=> $request->latitude,
           "latitude"=> $request->longitude,
           "status"=>"0"                
            ]);
            return response()->json([
                'success' => true,
                'data' => "geolocation successfully created"
            ], 200);
    }

   $this->geolocator::where(["user_id"=>$user_id])->update(
       [
        "user_name"=>$user->firstname,
        "longitude"=> $request->latitude,
        "latitude"=> $request->longitude,
       ]);

       return response()->json([
        'success' => true,
        'data' => "geolocation successfully updated"
    ], 200);

    }


    public function loadArtisanCurrentStatus(Request $request)
    {

        $validator = Validator::make($request->all(),
        [
            'token' => 'required|string',
                ]
    );
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

      $user = auth("users")->authenticate($request->token);
      $user_id = $user->id;
      $check_if_user_online_saved = $this->geolocator::where(["user_id"=>$user_id])->count();   
      if($check_if_user_online_saved==0)
    {
        return response()->json([
            'success' => true,
              'status'=> 0
        ], 200);
    }else{
      $getUser = $this->geolocator::where(["user_id"=>$user_id])->get();
      foreach ($getUser as $key => $value) {
          # code...
          $user_status = $value->status;
      }

        return response()->json([
            'success'=>true,
            'status'=>$user_status
        ]);
    }


    }

    public function updateArtisanCurrentStatus(Request $request)
    {

        $validator = Validator::make($request->all(),
        [
            'token' => 'required|string',
            'status'=>'required|integer'
                ]
    );
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
     
      $user = auth("users")->authenticate($request->token);
        $user_id = $user->id;

     $check_if_user_online_saved = $this->geolocator::where(["user_id"=>$user_id])->count();
    if($check_if_user_online_saved==0)
    {
        $this->geolocator::create(
            [
           "user_id"=>$user_id,
           "user_name"=>$user->firstname,
           "longitude"=>"null",
           "latitude"=>"null",
           "status"=> $request->status                
            ]);
            return response()->json([
                'success' => true,
                'message' => "availabilty status updated successfully",
                'online'=>false
            ], 200);
    }
   $this->geolocator->update(
       [
       "status"=>$request->status
       ]);

       return response()->json([
        'success' => true,
        'message' => "availabilty status updated successfully",
        'online'=>false
    ], 200);
    }



}
