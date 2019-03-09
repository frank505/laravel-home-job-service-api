<?php

namespace App\Http\Controllers\Administrator;

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
     //
     protected $base_url;
     protected $sub_categories;
     public function __construct(UrlGenerator $url)
     {
         $this->middleware("auth:admins");
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
 
 
 public function store(Request $request)
 {
 
     $validator = Validator::make($request->all(), 
     [
         'category_id'=> 'required|integer',
         'sub_category' => 'required|string',
         'image.*' => 'required|image|mimes:jpeg,bmp,png|max:8000',
     ]
      );
      
      if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }  
 
     $image = $request->file("image");
     if($image==NULL){
         return response()->json([
             'success' => false,
             'message' => 'please select an image'
         ], 500);    
     }
     // var_dump($image);
    //  return;
      $image_extension = $image->getClientOriginalExtension();
   if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
      return response()->json([
          'success' => false,
          'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
      ], 500);
    }
    $rename_image = uniqid()."_".time().date("Ymd")."_ICO.".$image_extension; //change file name
    $this->sub_categories::create(
     ['category_id'=>$request->category_id,
     'sub_category'=>$request->sub_category,
      'image'=>$rename_image,
     ]);
             $icon_dir = "images/sub_cat_icon"; //directory for the image to be uploaded
             $image->move($icon_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
     
  
         return response()->json([
             'success' => true,
             'message' => 'sub category added successfully'
         ], 200);
 }
 
 public function update(Request $request,$id)
 {
     $sub_categories = $this->sub_categories->find($id);
     if (!$sub_categories) {
         return response()->json([
             'success' => false,
             'message' => 'Sorry, sub_categories with id ' . $id . ' cannot be found'
         ], 400);
     }
 
     $validator = Validator::make($request->all(), 
     [
        'category_id'=> 'required|integer',
        'sub_category' => 'required|string',
        'image.*' => 'required|image|mimes:jpeg,bmp,png|max:8000',
     ]
      );
      
      if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      } 
 
     $image = $request->file("image");
     if($image==NULL){
         return response()->json([
             'success' => false,
             'message' => 'please select an image'
         ], 500);    
     }
     $image_extension = $image->getClientOriginalExtension();
  if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
     return response()->json([
         'success' => false,
         'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
     ], 500);
   }
   $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
 $update = $this->sub_categories::where(["id"=>$id])->update(
     ['category_id'=>$request->category_id,
     'sub_category'=>$request->sub_category,
      'image'=>$rename_image,
     ]);
     $sub_categories_prev_image = $sub_categories->image;
     unlink(public_path('images/sub_cat_icon/'.$sub_categories_prev_image));
     $icon_dir = "images/sub_cat_icon"; //directory for the image to be uploaded
     $image->move($icon_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
 
 if ($update) {
 return response()->json([
     'success' => true,
     'message'=>" sub category updated successfully"
 ]);
 } else {
 return response()->json([
     'success' => false,
     'message' => 'Sorry, sub_categories could not be updated'
 ], 500);
 }
 }
 
 public function delete(Request $request,$id)
 {
     $sub_categories = $this->sub_categories->find($id);
  
     if (!$sub_categories) {
         return response()->json([
             'success' => false,
             'message' => 'Sorry, sub_categories with id ' . $id . ' cannot be found'
         ], 400);
     }
  
     $sub_categories_prev_image = $sub_categories->image;
     if ($sub_categories->delete()) {
         unlink(public_path('images/sub_cat_icon/'.$sub_categories_prev_image));
         return response()->json([
             'success' => true,
             'message'=>'delete successful'
         ]);
     } else {
         return response()->json([
             'success' => false,
             'message' => 'sub_categories could not be deleted'
         ], 500);
     }
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
 
 //end of this class
}
