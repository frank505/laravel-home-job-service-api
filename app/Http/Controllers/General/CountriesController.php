<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Countries;
use Validator;
use App\Http\Controllers\SanitizeController;


class CountriesController extends Controller
{
    //
    protected $countries;
    public function __construct()
    {
     
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
}
