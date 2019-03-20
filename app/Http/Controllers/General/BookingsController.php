<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Bookings;
use Validator;

class BookingsController extends Controller
{
    //

    protected $Bookings;
    public function __construct()
    {
        $this->Bookings = new Bookings;
    }

    public function index($pagination=null,Request $request)
    {   
    //laravel automatically converts it to json and sends a response text too
    //$auth = auth("admins")->authenticate($request->token);
    if($pagination==null || $pagination==""){
        $Bookings =  $this->Bookings->get()->toArray();
        return response()->json([
            'success'=>true,
            'data'=>$Bookings,    
            ]);
        
    }else{
        $Bookings = $this->Bookings->paginate($pagination);
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
