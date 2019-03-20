<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SanitizeController;
use Validator;
use JWTAuth;

class BookingOptionsController extends Controller
{
    //
    //
    protected $booking_options;
    public function __construct()
    {
        $this->middleware("auth:admins");
     $this->booking_options = new BookingOptions;
    }


    public function index()
    {
     $db_query = DB::SELECT("SELECT booking_options.id,booking_options.service_id FROM booking_options 
     INNER JOIN booking_service_options ON booking_options.service_id = booking_service_options.service_id");
     return response()->json([
        'success' => true,
        'data'=>$db_query
     ],200);

    }
}
