<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers\SanitizeController;
use Validator;
use JWTAuth;
use App\SubCategories;

class SubCategoriesController extends Controller
{
    //
    protected $base_url;
     protected $sub_categories;
     public function __construct(UrlGenerator $url)
     {
         $this->middleware("auth:users");
         $this->sub_categories = new SubCategories();
         $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
     }
     public function index($pagination=null,Request $request)
 {
     $file_directory = $this->base_url."/images/sub_cat_icon";
 //laravel automatically converts it to json and sends a response text too
 //$auth = auth("admins")->authenticate($request->token);
 if($pagination==null || $pagination==""){
     $sub_categories =  $this->sub_categories->get(['id','category_id','sub_category','image','created_at'])->toArray();
     return response()->json([
         'success'=>true,
         'data'=>$sub_categories,
         'file_directory'=>$file_directory
     ]);
     
 }
     $paginated_sub_categories =  $this->sub_categories->paginate($pagination,['id', 'category_id','sub_category', 'image','created_at']);
     return response()->json([
         'success' => true,
          'data'=>$paginated_sub_categories,
          'file_directory'=>$file_directory
     ], 200);
 }
 
 public function getSubCategoriesFromCategories(Request $request,$id,$pagination=null)
 {
     $check = $this->sub_categories::where(["category_id"=>$id])->count();
     if($check==0){
         return response()->json([
         "success"=>false,
         "message"=>"this category doesnt have any sub category"
         ],400);
     }

     $file_directory = $this->base_url."/images/sub_cat_icon";
     //laravel automatically converts it to json and sends a response text too
     //$auth = auth("admins")->authenticate($request->token);
     if($pagination==null || $pagination==""){
         $sub_categories =  $this->sub_categories->where(["category_id"=>$id])->get(['id','category_id','sub_category','image','created_at'])->toArray();
         return response()->json([
             'success'=>true,
             'data'=>$sub_categories,
             'file_directory'=>$file_directory
         ],200);
         
     }
         $paginated_sub_categories =  $this->sub_categories->where(["category_id"=>$id])->paginate($pagination,['id', 'category_id','sub_category', 'image','created_at']);
         return response()->json([
             'success' => true,
              'data'=>$paginated_sub_categories,
              'file_directory'=>$file_directory
         ], 200);
    

 }
 
}
