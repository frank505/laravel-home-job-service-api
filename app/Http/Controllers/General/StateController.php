<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\States;
use Validator;
use App\Http\Controllers\SanitizeController;


class StateController extends Controller
{
    //
    protected $states;
public function __construct()
{
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
