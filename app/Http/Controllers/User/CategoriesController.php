<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\categories;
use Illuminate\Routing\UrlGenerator;
use App\Http\Controllers\SanitizeController;
use Validator;
use JWTAuth;

class CategoriesController extends Controller
{
    //
    protected $base_url;
    protected $categories;
    public function __construct(UrlGenerator $url)
    {
        $this->middleware("auth:users");
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


}
