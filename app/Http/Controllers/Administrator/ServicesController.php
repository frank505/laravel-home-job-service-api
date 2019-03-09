<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers\SanitizeController;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ServicesController extends Controller
{
    //
    protected $base_url;
    protected $services;
    public function __construct(UrlGenerator $url)
    {
        $this->middleware("auth:admins");
        $this->services = new Services();
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
    }
    public function index($pagination=null,Request $request)
{
    $file_directory = $this->base_url."/images/services_image";
//laravel automatically converts it to json and sends a response text too
//$auth = auth("admins")->authenticate($request->token);
if($pagination==null || $pagination==""){
    $services =  $this->services->get(['id', 'service','category_id','isdeleted','description','image','created_at'])->toArray();
    return response()->json([
        'success'=>true,
        'data'=>$services,
        'file_directory'=>$file_directory
    ]);
    
}
    $paginated_services =  $this->services->paginate($pagination,['id', 'service','category_id','isdeleted',
    'description','image','created_at']);
    return response()->json([
        'success' => true,
         'data'=>$paginated_services,
         'file_directory'=>$file_directory
    ], 200);
}


public function store(Request $request)
{

    $validator =   $this->validate($request, [
        
    ]);
    
    $validator = Validator::make($request->all(), 
    ['service' => 'required|string',
    'category_id'=>'required|integer',
    'description'=>'required|string',
    'image.*' => 'image|mimes:jpeg,bmp,png|max:8000'
    ]);
    
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
   $rename_image = uniqid()."_".time().date("Ymd")."_SERVICES.".$image_extension; //change file name
   $this->services::create(
    ['service'=>$request->service,
    'category_id'=>$request->category_id,
    'isdeleted'=>0,
    'description'=>$request->description,
     'image'=>$rename_image,
    ]);
            $services_dir = "images/services_image"; //directory for the image to be uploaded
            $image->move($services_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
    
 
        return response()->json([
            'success' => true,
            'message' => 'service added successfully'
        ], 200);
}

public function update(Request $request,$id)
{
    $services = $this->services->find($id);
    if (!$services) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, services with id ' . $id . ' cannot be found'
        ], 400);
    }
  $validator = Validator::make($request->all(),
[
    'service' => 'required|string',
    'category_id'=>'required|string',
    'description'=>'required|string',
    'image.*' => 'image|mimes:jpeg,bmp,png|max:8000',

]);
  
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
  $rename_image = uniqid()."_".time().date("Ymd")."_SERVICES.".$image_extension; //change file name
$update = $this->services::where(["id"=>$id])->update(
    [
        'service'=>$request->service,
    'category_id'=>$request->category_id,
    'description'=>$request->description,
     'image'=>$rename_image
    ]);
    $services_prev_image = $services->image;
    unlink(public_path('images/services_image/'.$services_prev_image));
    $services_dir = "images/services_image"; //directory for the image to be uploaded
            $image->move($services_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications

if ($update) {
return response()->json([
    'success' => true,
    "message"=>"category updated successfully"
]);
} else {
return response()->json([
    'success' => false,
    'message' => 'Sorry, services could not be updated'
], 500);
}
}


public function delete(Request $request,$id)
{
    $services = $this->services->find($id);
 
    if (!$services) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, services with id ' . $id . ' cannot be found'
        ], 400);
    }
     $delete_content = $services::where(["id"=>$id])->update(["isdeleted"=>"1"]);
    if ($delete_content) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'services could not be deleted'
        ], 500);
    }
}

public function GetServiceByCategory(Request $request,$id,$pagination=null)
{
    $file_directory = $this->base_url."/images/services_image";
    //laravel automatically converts it to json and sends a response text too
    //$auth = auth("admins")->authenticate($request->token);
    if($pagination==null || $pagination==""){
        $services =  $this->services->where(["category_id"=>$id])->get(['id', 'service','category_id','isdeleted','description','image','created_at'])->toArray();
        return response()->json([
            'success'=>true,
            'data'=>$services,
            'file_directory'=>$file_directory
        ]);
        }
        $paginated_services =  $this->services->where(["category_id"=>$id])->paginate($pagination,['id', 'service','category_id','isdeleted',
        'description','image','created_at']);
        return response()->json([
            'success' => true,
             'data'=>$paginated_services,
             'file_directory'=>$file_directory
        ], 200);

}


//end of this class

}
