<?php

namespace App\Http\Controllers\User;

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
    $this->middleware("auth:users");
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

}

