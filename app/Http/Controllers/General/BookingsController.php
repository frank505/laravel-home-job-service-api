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



}
