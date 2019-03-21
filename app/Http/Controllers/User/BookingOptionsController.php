<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BookingOptions;
use DB;
use Validator;
use Carbon\Carbon;

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



    public function MultipleInsert(Request $request)
    {
        $data_store = array();
        $array = $request->all();
        $now = Carbon::now('utc')->toDateTimeString();
        foreach ($array as $key => $array_data) {
            # code...
            $validator = Validator::make($array_data, 
            [
            'service_id' => 'required|integer',
            'booking_id' => 'required|integer',
            'option_id' => 'required|integer',
            'option_value'=>'required|string',
            'total_amount' => 'required|integer',
            'option_cost' =>'required|integer'
            ]        
            );
             $key = $key + 1;
            if($validator->fails()){
                return response()->json([
                 "success"=>false,
                 "row"=>"this error message is coming from row number $key",
                 "message"=>$validator->messages()->toArray(),
                ],400);    
              } 
             
              $service_id = $array_data["service_id"];
              $booking_id = $array_data["booking_id"];
            $option_id = $array_data["option_id"];
            $option_value = $array_data["option_value"];
            $total_amount = $array_data["total_amount"];
            $option_cost = $array_data["option_cost"];

             $row = array("service_id"=>$service_id,"booking_id"=>$booking_id,"option_id"=>$option_id,
              "option_value"=>$option_value, "total_amount"=>$total_amount,"option_cost"=>$option_cost,
              "created_at"=>$now,"updated_at"=>$now);
              $data_store[] = $row;

        }


       $insert_data =  $this->booking_options::insert($data_store);
         if($insert_data)
         {
            return response()->json([
                'success' => true,
                'message' => "data inserted successfully"
            ]);
        
         }

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
        'message' => "data inserted successfully"
    ]);


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
        'booking_options' => $this->booking_options
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
