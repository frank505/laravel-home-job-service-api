<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BookingOptions;
use DB;
use Validator;
class BookingOptionsController extends Controller
{
    //
    protected $booking_options;
    public function __construct()
    {
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


    public function store(Request $request)
    {   
    $validator = Validator::make($request->all(), 
    [
        'service_id' => 'required|integer',
        'booking_id' => 'required|integer',
        'option_id' => 'required|integer',
        'option_value'=>'required|string',
        'total_amount' => 'required|integer',
        'option_cost' =>'required|integer'
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
     
    $this->booking_options::create([
        "service_id"=>$request->service_id,
        "booking_id"=>$request->booking_id,
        "option_id"=>$request->option_id,
        "option_value"=>$request->option_value,
        "total_amount"=>$request->total_amount,
        "option_cost"=>$request->option_cost
    ]);

    return response()->json([
        'success' => true,
        'message' => "booking options created successfully"
    ],200);


    }




    public function update(Request $request,$id)
    {
        $booking_options = $this->booking_options->find($id);
        if (!$booking_options) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, booking_options with id ' . $id . ' cannot be found'
            ], 400);
        }
     
        $validator = Validator::make($request->all(), 
    [
        'service_id'=>'required|integer',
        'booking_id' => 'required|integer',
        'option_id' => 'required|integer',
        'option_value'=>'required|string',
        'total_amount' => 'required|integer',
        'option_cost' =>'required|integer'
    ]
     );
     
     if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
     
    $this->booking_options::where("id",$id)->update([
        "booking_id"=>$request->booking_id,
        "option_id"=>$request->option_id,
        "option_value"=>$request->option_value,
        "total_amount"=>$request->total_amount,
        "option_cost"=>$request->option_cost,
        "service_id"=>$request->service_id,
    ]);

    return response()->json([
        'success' => true,
        'booking_options' =>" booking options updated successfully"
    ]);

    }


 public function delete(Request $request,$id)
 {
    $booking_options = $this->booking_options->find($id);
 
    if (!$booking_options) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, booking_options with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    if ($booking_options->delete()) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'booking_options could not be deleted'
        ], 500);
    }
 }
}
