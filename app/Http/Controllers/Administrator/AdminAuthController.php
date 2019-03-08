<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\admin;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use App\ManageUser;
use Illuminate\Routing\UrlGenerator;
use Validator;
use App\Http\Controllers\SanitizeController;

class AdminAuthController extends Controller
{
    //
    protected $base_url;
    public $loginAfterSignUp = true;
   protected $admin;
    public function __construct(UrlGenerator $url){
       $this->middleware("auth:admins",['except'=>['login']]);
       $this->admin = new admin();
       $this->user = new ManageUser();
       $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
   }
    public function register(Request $request)
    {
    $validator = Validator::make($request->all(), 
    ['name' => 'required|string',
    'email' => 'required|email',
    'password' => 'required|string|min:6']);
    if($validator->fails()){
        return $validator->messages()->toArray();
      }

      $check_email = $this->admin->where("email",$request->email)->count();
      if($check_email!=0){
         $taken = array("email"=>"this email is already taken");
         return response()->json($taken, 200);
      }
        $this->admin->name = $request->name;
        $this->admin->email = $request->email;
        $this->admin->password = Hash::make($request->password);
        $this->admin->save();
 
        // if ($this->loginAfterSignUp) {
        //     return $this->login($request);
        // }
 
        return response()->json([
            'success' => true,
            "message"=>"new admin registration successful"
        ], 200);
    }
  


    public function AddProfilePicture(Request $request)
    {
        $validator = Validator::make($request->only('profilephoto','token'),
        [
        'profilephoto.*' => 'required|image|mimes:jpeg,bmp,png|max:8000',
        'token' => 'required'
                ]
    );
          if($validator->fails()){
            return  $validator->messages()->toArray();
          }    

          $admin = auth("admins")->authenticate($request->token);


            $profilephoto = $request->file("profilephoto");
            if($profilephoto==NULL){
                return response()->json([
                    'success' => false,
                    'message' => 'please select an image'
                ], 500);    
            }
        //    var_dump($profilephoto);
        //    return;
        $image_extension = $profilephoto->getClientOriginalExtension();
        if($image_extension==NULL){
            return response()->json([
                'required' => 'please upload an image'
            ], 500);
          }
       
            
         if(SanitizeController::CheckFileExtensions($image_extension,array("png","jpg","jpeg","PNG","JPG","JPEG"))==FALSE){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this is not an image please ensure your images are png or jpeg files'
            ], 500);
          }



          $rename_image = uniqid()."_".time().date("Ymd")."_IMG.".$image_extension; //change file name
         
          $admin_prev_image = $admin->profilephoto;
            if($admin_prev_image==NULL){

            }else{
                unlink(public_path('images/admin/'.$admin_prev_image));
            }
          

          $admin->profilephoto = $rename_image;
          
          $admin->save();
            $admin_dir = "images/admin"; //directory for the image to be uploaded
            $profilephoto->move($admin_dir, $rename_image); //more like the move_uploaded_file in php except that more modifications
            
            
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
            return $validator->messages()->toArray();
          }
    
         $input = $request->only("email","password");
        $jwt_token = null;
 
        if (!$jwt_token = auth("admins")->attempt($input)) {
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
 
    public function logout(Request $request)
    {   
        $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return $validator->messages()->toArray();
          }
 
        try {
            auth("admins")->invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'admin logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the admin cannot be logged out'
            ], 500);

        }


        
    }
   
    public function getAuthadmin(Request $request)
    {
        $validator = Validator::make($request->only('token'), 
        ['token' => 'required']);
        if($validator->fails()){
            return $validator->messages()->toArray();
          }
 
        $admin = auth("admins")->authenticate($request->token);
 
        return response()->json([
            'user' => $admin,
            'image_directory'=>$this->base_url."/images/admin",
            ]);
    }

    public function banUser(Request $request,$id)
    {
        $users = $this->user->find($id);
        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found'
            ], 400);
        }
       $update =  $this->user->where(["id"=>$id])->update(["status"=>"1"]);  
         if($update)
         {
            return response()->json([
                'success' => true,
                'message' => 'user banned successfully'
            ]);
         }else{
            return response()->json([
                'success' => false,
                'message' => 'user ban was not successful'
            ], 500);
         }  
    }


    public function removeBan(Request $request,$id)
    {
        $users = $this->user->find($id);
        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found'
            ], 400);
        }
        $update =  $this->user->where(["id"=>$id])->update(["status"=>"0"]);  
         if($update)
         {
            return response()->json([
                'success' => true,
                'message' => 'ban removed from user successfully '
            ]);
         }else{
            return response()->json([
                'success' => false,
                'message' => 'ban failed to be removed from user'
            ], 500);
         }
    }

    public function ViewUsers(Request $request,$pagination=null)
    {
        if($pagination==null || $pagination==""){
            return response()->json([
                'success' => true,
                 'data'=>$this->user->get()->toArray(),
                 'image_directory'=>$this->base_url."/images/users"
            ], 200);
        }
            $paginated_user =  $this->user->paginate($pagination);
            return response()->json([
                'success' => true,
                 'data'=>$paginated_user,
                 'image_directory'=>$this->base_url."/images/users"
            ], 200);
        }
    
        public function showUserProfile($id)
        {
            $users = $this->user->find($id);
            if (!$users) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, user with id ' . $id . ' cannot be found',
                ], 400);
            }
        
            return response()->json([
                "success"=>true,
                "data"=>$users,
                'image_directory'=>$this->base_url."/images/users",
            ],200);
        }
        
        
}
