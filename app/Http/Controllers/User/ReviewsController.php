<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reviews;
use Validator;
use App\Http\Controllers\SanitizeController;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class ReviewsController extends Controller
{
   protected $reviews;
    public function __construct()
    {
        $this->middleware("auth:users");
        $this->reviews = new Reviews();
    }
    //
    public function create(Request $request,$id)
    {

     
            
            $validator = Validator::make($request->all(), 
            ['customer_id' => 'required|string',
            'artisan_id'=>'required|integer',
            'booking_id'=>'required|integer',
            'review'=>'required|string',
            'rating'=>'required|string',
            'review_date'=>'required|string'
            ]);
            
            if($validator->fails()){
                return response()->json([
                 "success"=>false,
                 "message"=>$validator->messages()->toArray(),
                ],400);    
              }
        
           $this->reviews::create(
            ['customer_id'=>$request->customer_id,
            'artisan_id'=>$request->artisan_id,
            'booking_id'=>$request->booking_id,
            'service_id'=>$id,
            'review'=>$request->review,
            'rating'=>$request->rating,
             'review_date'=>$request->review_date,
            ]);
                   
                return response()->json([
                    'success' => true,
                    'message' => 'service added successfully'
                ], 200);
    }


    public function getReviews($pagination)
    {
        $paginated_reviews =  $this->reviews->paginate($pagination,['customer_id','service_id',
        'booking_id','artisan_id','review','rating','review_date','created_at']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_reviews,
             
        ], 200);
    }

    public function getReviewsForServiceId($pagination,$id)
    {
        $paginated_reviews =  $this->reviews->where(["service_id"=>$id])->paginate($pagination,['customer_id','service_id',
        'booking_id','artisan_id','review','rating','review_date','created_at']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_reviews,
             
        ], 200);
    }

    public function getReviewsForArtisanId($pagination,$id)
    {
        $paginated_reviews =  $this->reviews->where(["artisan_id"=>$id])->paginate($pagination,['customer_id','service_id',
        'booking_id','artisan_id','review','rating','review_date','created_at']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_reviews,
             
        ], 200);
    }



   
}
