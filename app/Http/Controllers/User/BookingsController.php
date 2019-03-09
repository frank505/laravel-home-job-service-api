<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\Bookings;
use Validator;

class BookingsController extends Controller
{
    //
    protected $Bookings;
    public function __construct()
    {
        $this->middleware("auth:users");
        $this->Bookings = new Bookings;
    }

    public function store(Request $request)
{
    
  $validator =  Validator::make($request->all(),
[
    'user_id' => 'required|integer',
    'service_id' => 'required|integer',
    'location'=>'required|string',
    'artisan_id'=>'required|string',
    'time' => 'required|date',
    'address'=>'required|string',
    'total_cost'=>'required|string',
    'status'=>'required|string',
    'scheduledate'=>'required|date',
    'completedate'=>'required|date',    

]);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
    $created =  $this->Bookings::create(
    ['user_id'=>$request->user_id,
    'service_id'=>$request->service_id,
      'location'=>$request->location,
      'artisan_id'=>$request->artisan_id,
      'time'=>$request->time,
       'artisan_id'=>$request->artisan_id,
       'time'=>$request->time,
       'address'=>$request->address,
       'total_cost'=>$request->total_cost,
       'status'=>$request->status,
       'scheduledate'=>$request->scheduledate,
       'completedate'=>$request->completedate
    ]);
      
    if($created){
        return response()->json([
            'success' => true,
            'message' => 'new booking added successfully'
        ], 200);
    }else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, content couldnt be deleted'
        ], 500);
    }
        
}

public function update(Request $request,$id)
{
    $Bookings = $this->Bookings->find($id);
    if (!$Bookings) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, Bookings with id ' . $id . ' cannot be found'
        ], 400);
    }

    
  $validator =  Validator::make($request->all(), 
[
    'user_id' => 'required|integer',
    'service_id' => 'required|integer',
    'location'=>'required|string',
    'artisan_id'=>'required|string',
    'time' => 'required|date',
    'address'=>'required|string',
    'total_cost'=>'required|string',
    'status'=>'required|string',
    'scheduledate'=>'required|date',
    'completedate'=>'required|date',
]);
    
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }   
$update = $this->Bookings::where(["id"=>$id])->update(
    [
        'user_id'=>$request->user_id,
        'service_id'=>$request->service_id,
          'location'=>$request->location,
          'artisan_id'=>$request->artisan_id,
          'time'=>$request->time,
           'artisan_id'=>$request->artisan_id,
           'time'=>$request->time,
           'address'=>$request->address,
           'total_cost'=>$request->total_cost,
           'status'=>$request->status,
           'scheduledate'=>$request->scheduledate,
           'completedate'=>$request->completedate
    
    ]);
   
if ($update) {
return response()->json([
    'success' => true,
    "message"=>"boooking updated successfully"
]);
} else {
return response()->json([
    'success' => false,
    'message' => 'Sorry, Bookings could not be updated'
], 500);
}
}

//this is to delete
public function delete(Request $request,$id)
{
    $Bookings = $this->Bookings->find($id);
 
    if (!$Bookings) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, service form options with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    if ($Bookings->delete()) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'service form options could not be deleted'
        ], 500);
    }
}

public function BookingForParticularUser(Request $request,$id,$pagination=null)
{
    
    if($pagination==null || $pagination==""){
        $Bookings =  $this->Bookings->where(["user_id"=>$id])->get()->toArray();
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,
        ]);          
    }else{
        $Bookings = $this->Bookings->where(["user_id"=>$id])->paginate($pagination);
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,    
            ]);
    }
}
public function BookingForParticularArtisan(Request $request, $id)
{
    $Bookings =  $this->Bookings->where(["artisan_id"=>$id])->get()->toArray();
    return response()->json([
        'success'=>true,
        'data'=>$Bookings,
    ]);
}

}
