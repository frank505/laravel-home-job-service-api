<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cities;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\SanitizeController;

class CityController extends Controller
{
    //
        //
        protected $cities;
        public function __construct()
        {
            $this->middleware("auth:admins");
             $this->cities = new Cities();
        }
        
        public function index(Request $request,$id)
        {
            //the id is the city id
          $get_cities = $this->cities->where(["state_id"=>$id])->get();
          return response()->json([
            'success' => true,
             'data'=>$get_cities
        ], 200);
        
        }
        
        
        
        public function store(Request $request)
        {
        
            $validator = Validator::make($request->only("city","state_id"), 
            [
                'state_id' => 'required|integer',
                'city' =>'required|string'
            ]
             );
             
             if($validator->fails()){
                return response()->json([
                 "success"=>false,
                 "message"=>$validator->messages()->toArray(),
                ],400);    
              } 
            $this->cities->state_id = $request->state_id;
            $this->cities->isdeleted = 0;
            $this->cities->city = $request->city;
            if ($this->cities->save()){
                return response()->json([
                    'success' => true,
                    'cities' => $this->cities
                ]);
            }   
            else{
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, cities could not be added'
                ], 500);
            }
        }
        
        public function update(Request $request,$id)
        {
            $cities = $this->cities->find($id);
            if (!$cities) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, cities with id ' . $id . ' cannot be found'
                ], 400);
            }
        
            $validator = Validator::make($request->all(), 
            [
                'state_id' => 'required|string',
                 'city'=>'required|string'
            ]
             );
             
             if($validator->fails()){
                return response()->json([
                 "success"=>false,
                 "message"=>$validator->messages()->toArray(),
                ],400);    
              }
         
            $update = $cities->fill($request->all())->save();
        
            if ($update) {
                return response()->json([
                    'success' => true,
                    "message"=>"cities updated successfully"
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, cities could not be updated'
                ], 500);
            }
        }
        
        public function delete(Request $request,$id)
        {
            $cities = $this->cities->find($id);
         
            if (!$cities) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, cities with id ' . $id . ' cannot be found'
                ], 400);
            }
            //set is deleted property to one meaning it has been deleted
             $cities->isdeleted = 1;
           $saved =   $cities->save();
            if ($saved) {
                return response()->json([
                    'success' => true,
                    'message'=>'delete successful'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'cities could not be deleted'
                ], 500);
            }
        }
        
        //end of this class
}
