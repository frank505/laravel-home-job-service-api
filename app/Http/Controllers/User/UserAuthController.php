<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\SanitizeController;
use Illuminate\Routing\UrlGenerator;
use Validator;
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
        $validator = Validator::make($request->all(),
        ['firstname' => 'required|string',
        'lastname'=>'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
        'phone'=>'required|string',
        'cityid'=>'required|string',
        'roleid'=>'required|string'
                ]
    );
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }  
   $check_email = $this->user->where("email",$request->email)->count();
   if($check_email!=0){
      $taken = array("email"=>"this email is already taken");
      return response()->json($taken, 200);
   }
        $this->user::create(
            ['firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'email'=>$request->email,
             'password'=>Hash::make($request->password),
             'phone'=>$request->phone,
             'cityid'=>$request->cityid,
             'roleid'=>$request->roleid,
            ]);
           
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function AddProfilePicture(Request $request)
    {
        $validator = Validator::make($request->only('profilephoto','token'),
        [
            'token' => 'required',
        'profilephoto.*' => 'required|image|mimes:jpeg,bmp,png|max:8000'
                ]
    );

          if($validator->fails()){
             return $validator->messages()->toArray();
          }    
          $user = auth("users")->authenticate($request->token);

            $profilephoto = $request->file("profilephoto");
            if($profilephoto==NULL){
                return response()->json([
                    'success' => false,
                    'message' => 'please select an image'
                ], 500);    
            }
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
         
          $user_prev_image = $user->profilephoto;
            if($user_prev_image==NULL){

            }else{
                unlink(public_path('images/users/'.$user_prev_image));
            }
          

          $user->profilephoto = $rename_image;
          
          $user->save();

            $users_dir = "images/users"; //directory for the image to be uploaded
            $profilephoto->move($users_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
            $user_prev_image = $user->profilephoto;
       
        return response()->json([
            'success' => true,
            'data' => "profile photo updated successfully"
        ], 200);
    }
 
    public function login(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password'), 
        ['email' => 'required|email',
        'password' => 'required|string|min:6']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
    
         $input = $request->only("email","password");

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
     
    $validator = Validator::make($request->all(),
    ['firstname' => 'required|string',
    'lastname'=>'required|string',
    'password' => 'required|string|min:6',
    'phone'=>'required|string',
    'cityid'=>'required|string',
    'roleid' =>'required|string'
            ]
);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
        
            $update = $this->user::where(["id"=>$id])->update(
            ['firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'password'=>Hash::make($request->password),
             'phone'=>$request->phone,
             'cityid'=>$request->cityid,
             'roleid'=>$request->roleid
            ]);

    if ($update) {
        return response()->json([
            'success' => true,
            "message"=>"profile updated successfully"
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
        $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
 
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
        $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }
 
        $user = auth("users")->authenticate($request->token);
 
        return response()->json([
            'user' => $user,
            'image_directory'=>$this->base_url."/images/users",
            ]);
    }
}
