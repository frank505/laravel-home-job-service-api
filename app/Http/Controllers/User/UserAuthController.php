<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SanitizeController;
use Illuminate\Routing\UrlGenerator;

class UserAuthController extends Controller
{
    //
    public $loginAfterSignUp = true;
     protected $user;
     protected $base_url;
    public function __construct(UrlGenerator $url){
        $this->middleware("auth:users",['except'=>['login','register']]);
        $this->user = new User();
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
    }

    public function register(Request $request)
    {

        $this->validate($request,
            ['firstname' => 'required|string',
            'lastname'=>'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:10',
            'phone'=>'required|string',
            'cityid'=>'required|string',
            'occupationid'=>'required|string',
            'rating'=>'required|integer',
            'profilephoto.*' => 'image|mimes:jpeg,bmp,png|max:8000',
                    ]);
          
            $profilephoto = $request->file("profilephoto");
           // var_dump($profilephoto);
          //  return;
            $image_extension = $profilephoto->getClientOriginalExtension();
         if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
            ], 500);
          }
          $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
        $this->user::create(
            ['firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'email'=>$request->email,
             'password'=>Hash::make($request->password),
             'phone'=>$request->phone,
             'cityid'=>$request->cityid,
             'occupationid'=>$request->occupationid,
             'rating'=>$request->rating,
             'roleid'=>$request->roleid,
             'profilephoto'=>$rename_image
            ]);
            $users_dir = "images/users"; //directory for the image to be uploaded
            $profilephoto->move($users_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
    

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }
 
    public function login(Request $request)
    {
        $input = $request->only('email','password');
        $jwt_token = null;
 
        if (!$jwt_token = auth('users')->attempt($input)) {
            return response()->json([
                'success' => false,
            'message' => 'Invalid Email or Password',
            ], 401);
        }
 
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
       }
       
       public function editProfile(Request $request,$id)
       {
        $user = $this->user->find($id);
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user with id ' . $id . ' cannot be found'
        ], 400);
    }
    
    $this->validate($request,
            ['firstname' => 'required|string',
            'lastname'=>'required|string',
            'password' => 'required|string|min:6|max:10',
            'phone'=>'required|string',
            'cityid'=>'required|string',
            'occupationid'=>'required|string',
            'rating'=>'required|integer',
            'roleid'=>'required|string',
            'profilephoto.*' => 'image|mimes:jpeg,bmp,png|max:8000',
                    ]);
        
            $profilephoto = $request->file("profilephoto");
            $image_extension = $profilephoto->getClientOriginalExtension();
         if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
            ], 500);
          }
          $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
    $update = $this->user::where(["id"=>$id])->update(
            ['firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'password'=>Hash::make($request->password),
             'phone'=>$request->phone,
             'cityid'=>$request->cityid,
             'occupationid'=>$request->occupationid,
             'rating'=>$request->rating,
             'roleid'=>$request->roleid,
             'profilephoto'=>$rename_image
            ]);
            $user_prev_image = $user->profilephoto;
            unlink(public_path('images/users/'.$user_prev_image));
            $users_dir = "images/users"; //directory for the image to be uploaded
            $profilephoto->move($users_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications

    if ($update) {
        return response()->json([
            'success' => true
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user could not be updated'
        ], 500);
    }
       }
     
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        try {
            auth("users")->invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }
 
    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = auth("users")->authenticate($request->token);
 
        return response()->json([
            'user' => $user,
            'image_directory'=>$this->base_url."/images/users",
            ]);
    }
}
