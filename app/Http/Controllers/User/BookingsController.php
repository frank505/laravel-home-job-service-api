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

public function BookingForParticularUser(Request $request,$pagination=null)
{

    $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
 
        $user = auth("users")->authenticate($request->token);
 

    if($pagination==null || $pagination==""){
        $Bookings =  $this->Bookings->where(["user_id"=>$user->id])->get()->toArray();
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,
        ]);          
    }else{
        $Bookings = $this->Bookings->where(["user_id"=>$user->id])->paginate($pagination);
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,    
            ]);
    }
}
public function BookingForParticularArtisan(Request $request, $id,$pagination=null)
{

    if($pagination==null || $pagination==""){
        $Bookings =  $this->Bookings->where(["artisan_id"=>$id])->get()->toArray();
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,
        ]);          
    }else{
        $Bookings = $this->Bookings->where(["artisan_id"=>$id])->paginate($pagination);
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,    
            ]);
    }

}

public function getBooking(Request $request, $id)
{
    $Bookings =  $this->Bookings->where(["id"=>$id])->get()->toArray();
    return response()->json([
        'success'=>true,
        'data'=>$Bookings,
    ]);
}

//booking/ booking_id/status/status_d
//booking/booking_id/set-status/status_id

//this function sets status of booking depending on the parameter passed one or two
public function setBookingStatusId(Request $request,$booking_id,$status_id)
{
  $update_status = "";
  $updateData = $this->Bookings->where(["id"=>$booking_id])->count();
  if($updateData==0){
     return response()->json([
             "success"=>false,
             "message"=>"invalid booking id entered",
            ],400);    
  }

  if($status_id == 1){
    $update_status = 0;
  }else if($status_id==2){
    $update_status = 1;
  }

 
 $booking_data = $this->Bookings::where(["id"=>$booking_id])->update(["status"=>$update_status]);
if($booking_data)
{
   return response()->json([
        'success'=>true,
        "message"=>"bokng set successffully"
    ]);
}

}



//pending booking for artisan  booking/pending-artisan/artisan_id/getstatus/status
public function getPendingBookingForArtisan(Request $request,$status,$artisan_id)
{
 $pending_bookings = $this->Bookings::where(["artisan_id"=>$artisan_id])->count();
 if($pending_bookings==0){
     return response()->json([
             "success"=>false,
             "message"=>"invalid booking artsan id entered",
            ],400);    
  } 

  $getData = $this->Bookings::where(["artisan_id"=>$artisan_id,"status"=>0])->get()->toArray();

   return response()->json([
            'success'=>true,
            'data'=>$getData,
        ]);
}

//pending booking for a user  booking/pending-user/user_id/getstatus/status
public function getpendngBookingForUser(Request $request,$status,$user_id)
{
 $pending_bookings = $this->Bookings::where(["user_id"=>$user_id])->count();
 if($pending_bookings==0){
     return response()->json([
             "success"=>false,
             "message"=>"invalid booking user id entered",
            ],400);    
  } 

  $getData = $this->Bookings::where(["user_id"=>$user_id,"status"=>0])->get()->toArray();

   return response()->json([
            'success'=>true,
            'data'=>$getData,
        ]); 
}


//bookings/get-approved/customers_id
public function getAllwhereUsersIdEqualsCustomersIdAndIsApproved(Request $request,$customers_id)
{
  $check_id = $this->Bookings::where(["user_id"=>$customers_id])->count();
  if($check_id==0){
    return response()->json([
        "success"=>false,
        "message"=>"invalid booking user id entered",
       ],400);    
  }

  $getData = $this->Bookings::where(["user_id"=>$customers_id,"status"=>1])->get()->toArray();

  return response()->json([
           'success'=>true,
           'data'=>$getData,
       ]);
}

}
