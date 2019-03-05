<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\categories;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers\SanitizeController;

class CategoriesController extends Controller
{
    //
    protected $base_url;
    protected $categories;
    public function __construct(UrlGenerator $url)
    {
        $this->middleware("auth:admins");
        $this->categories = new categories();
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
    }
    public function index($pagination=null,Request $request)
{
    $file_directory = $this->base_url."/images/cat_icon";
//laravel automatically converts it to json and sends a response text too
//$auth = auth("admins")->authenticate($request->token);
if($pagination==null || $pagination==""){
    $categories =  $this->categories->get(['id', 'category', 'image','created_at'])->toArray();
    return response()->json([
        'success'=>true,
        'data'=>$categories,
        'file_directory'=>$file_directory
    ]);
    
}
    $paginated_categories =  $this->categories->paginate($pagination,['id', 'category', 'image','created_at']);
    return response()->json([
        'success' => true,
         'data'=>$paginated_categories,
         'file_directory'=>$file_directory
    ], 200);
}


public function store(Request $request)
{

    $this->validate($request, [
        'category' => 'required|string',
        'image.*' => 'image|mimes:jpeg,bmp,png|max:8000',
    ]);
 
    $image = $request->file("image");
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
   $this->categories::create(
    ['category'=>$request->category,
     'image'=>$rename_image,
    ]);
            $icon_dir = "images/cat_icon"; //directory for the image to be uploaded
            $image->move($icon_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
    
 
        return response()->json([
            'success' => true,
            'message' => 'category added successfully'
        ], 200);
}

public function update(Request $request,$id)
{
    $categories = $this->categories->find($id);
    if (!$categories) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, categories with id ' . $id . ' cannot be found'
        ], 400);
    }

    $this->validate($request,
    ['category'=>'required|string',
    'image.*' => 'image|mimes:jpeg,bmp,png|max:8000']);

    $image = $request->file("image");
    $image_extension = $image->getClientOriginalExtension();
 if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
    return response()->json([
        'success' => false,
        'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
    ], 500);
  }
  $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
$update = $this->categories::where(["id"=>$id])->update(
    ['category'=>$request->category,
     'image'=>$rename_image
    ]);
    $categories_prev_image = $categories->image;
    unlink(public_path('images/cat_icon/'.$categories_prev_image));
    $icon_dir = "images/cat_icon"; //directory for the image to be uploaded
    $image->move($icon_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications

if ($update) {
return response()->json([
    'success' => true
]);
} else {
return response()->json([
    'success' => false,
    'message' => 'Sorry, categories could not be updated'
], 500);
}
}

public function delete(Request $request,$id)
{
    $categories = $this->categories->find($id);
 
    if (!$categories) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, categories with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    if ($categories->delete()) {
        $categories_prev_image = $categories->image;
        unlink(public_path('images/cat_icon/'.$categories_prev_image));
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'categories could not be deleted'
        ], 500);
    }
}

public function GetServiceByCategory(Request $request)
{

}
//end of this class
}
