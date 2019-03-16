<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reviews;
use Validator;
use App\Http\Controllers\SanitizeController;

class ReviewsController extends Controller
{
    //
    
    public function __construct()
    {
     $this->reviews = new Reviews();
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

    public function getReviewsForArtisanId($id,$pagination)
    {
        $paginated_reviews =  $this->reviews->where(["artisan_id"=>$id])->paginate($pagination,['customer_id','service_id',
        'booking_id','artisan_id','review','rating','review_date','created_at']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_reviews,
             
        ], 200);
    }


    //end of this class
}
