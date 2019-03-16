<?php

namespace App\Http\Controllers\General;

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
    protected $cities;
    public function __construct()
    {
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

    //end of this class
}
