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
use Illuminate\Support\Facades\Mail;
use App\Mail\UserResetPasswordMail;
Use Symfony\Component\HttpFoundation\Response;
Use DB;
use Carbon\Carbon;

class UserAuthController extends Controller
{
    //
    public $loginAfterSignUp = true;
     protected $user;
     protected $base_url;
    public function __construct(UrlGenerator $url){
        $this->middleware("auth:users",['except'=>['login','register','sendResetPasswordLink']]);
        $this->user = new User();
        $this->base_url = $url->to("/");  //this is to make the baseurl available in this controller
    }
    

    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            'firstname' => 'required|string',
        'lastname'=>'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
        'phone'=>'required|string',
        // 'roleid'=>'required|string',
        // 'country_id'=>'required|string',
        'residence'=>'required|string',
        'city'=>'required|string',
        // 'what_you_do'=>'required|string',
        'adress'=>'required|string',
        // 'bank'=>'required|string',
        // 'account_number'=>'required|string',
        // 'gaurantors_name'=>'required|string',
        // 'gaurantors_number'=>'required|string',
        // 'why_you_love_what_you_do'=>'required|string',

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
            [
            'firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'email'=>$request->email,
             'password'=>Hash::make($request->password),
             'phone'=>$request->phone,
             'city_id'=>$request->city,
            // 'roleid'=>$request->roleid,
            //  'what_you_do'=>$request->what_you_do,
              'address'=>$request->adress,
            //  'bank'=>$request->bank,
            //  'account_number'=>$request->account_number,
            //  'gaurantors_name'=>$request->gaurantors_name,
            //  'gaurantors_number'=>$request->gaurantors_number,
            //  'why_you_love_what_you_do'=>$request->why_you_love_what_you_do,
              //'country_id'=>$request->country_id,
              'state_id'=>$request->residence,
            //   'bank'=>$request->bank,
            //   'account_number'=>$request->twitter_handle
            ]);
           
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 

        return response()->json([
            'success' => true,
            'data' => $user,
            'expires_in'=>auth("users")->factory()->getTTL() * 60 * 24 * 30,
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
      
        $token_time_frame = auth("users")->factory()->setTTL(NULL);

          $user = auth("users")->authenticate($request->token);
          $id = $user->id;
          return response()->json([
            'success' => true,
            'token' => $jwt_token,
            'expires_in'=>auth("users")->factory()->getTTL(),
            'id'=>$id
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
    [

        'firstname' => 'required|string',
        'lastname'=>'required|string',
        'password' => 'required|string|min:6',
        'phone'=>'required|string',
        'roleid'=>'required|string',
        'country_id'=>'required|string',
        'state_id'=>'required|string',
        'city_id'=>'required|string',
        'what_you_do'=>'required|string',
        'address'=>'required|string',
        'bank'=>'required|string',
        'account_number'=>'required|string',
        'gaurantors_name'=>'required|string',
        'gaurantors_number'=>'required|string',
        'why_you_love_what_you_do'=>'required|string',

            ]
);
if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
  }
        
            $update = $this->user::where(["id"=>$id])->update(
            [
                'firstname'=>$request->firstname,
             'lastname'=>$request->lastname,
             'password'=>Hash::make($request->password),
             'phone'=>$request->phone,
             'cityid'=>$request->cityid,
             'roleid'=>$request->roleid,
             'what_you_do'=>$request->what_you_do,
             'address'=>$request->address,
             'bank'=>$request->bank,
             'account_number'=>$request->account_number,
             'gaurantors_name'=>$request->gaurantors_name,
             'gaurantors_number'=>$request->gaurantors_number,
             'why_you_love_what_you_do'=>$request->why_you_love_what_you_do,
              'country_id'=>$request->country_id,
              'state_id'=>$request->state_id,
              'bank'=>$request->bank,
              'account_number'=>$request->twitter_handle
            ]);

    if ($update) {
        return response()->json([
            'success' => true,
            "message"=>"profile updated successfully"
        ],200);
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





  public function sendEmailData($user_email)
  {
      $token = $this->createToken($user_email);
  return Mail::to($user_email)->send(new UserResetPasswordMail($token));
 
  }

  public function createToken($user_email)
  {
    //   $prevToken = DB::table("password_resets")->where("email",$user_email)->first();
    //   if($prevToken){
    //       return $prevToken;
    //   }
      $token = str_random(60);
      $this->saveToken($token,$user_email);
      return $token;
  }

  public function saveToken($token,$email)
  {
      DB::table('password_resets')->insert(
          [
              "email"=>$email,"token"=>$token,"created_at"=>Carbon::now()
              ]
          );
  }

    public function sendResetPasswordLink(Request $request)
    {
       // return $request->all();
        $validator = Validator::make($request->only('email'), 
        ['email' => 'required']);
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
    }

    $check_email_exist = $this->user::where(["email"=>$request->email])->count();
    if($check_email_exist==0){
        return response()->json([
            "success"=>false,
            "message"=>"user with this email does not exist"
        ],400);
    }else{
        $this->sendEmailData($request->email);
        return response()->json([
            "success"=>true,
            "message"=>"reset password link has been sent to your email please click on the link to reset your password",
           ],200);    

    }
}


  public function ChangePassword(Request $request)
  {

    $validator = Validator::make($request->all(), 
    ['password' => 'required','token'=>'required']);

    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
}

$user = auth("users")->authenticate($request->token);

  $hash_password = Hash::make($request->password);
 $user->password = $hash_password;
   if($user->save()){
    return response()->json([
        'success' => true,
        "message"=>"password changed sucessfully"
    ],200);
   }
  }

 public function saveLastLocation(Request $request)
 {

  $validator = Validator::make($request->all(),
  [
      "id"=>"required|integer",
      "last_location"=>"required"
  ]);


  if($validator->fails()){
    return response()->json([
     "success"=>false,
     "message"=>$validator->messages()->toArray(),
    ],400);    
}

$user = $this->user->find($id);
if (!$user) {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, user with id ' . $id . ' cannot be found'
    ], 400);
}
 $user->last_location = $request->last_location;
 if($user->save()){
    return response()->json([
        'success' => true,
        "message"=>"user last location updated successfully"
    ],200);
   }
 }


 public function currentlyLoggedInArtisans()
 {
  $getCurrentlyLoggedIn = $this->user::where(["isOnline"=>1,"status"=>0])->get(["firstname","lastname","email","id","isOnline"]);
  return response()->json([
    'success' => true,
    "data"=>$getCurrentlyLoggedIn
],200);
}

public function loggedIn(Request $request)
{ 
    $validator = Validator::make($request->all(),
    [
        "id"=>"required|integer",
    ]);
  
  
    if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
  }
  
  $user = $this->user->find($id);
  if (!$user) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, user with id ' . $id . ' cannot be found'
      ], 400);
  }
   $user->isOnline = 1;
   if($user->save()){
      return response()->json([
          'success' => true,
          "message"=>"user last location updated successfully"
      ],200);
     }
}

public function loggedOut()
{
    $validator = Validator::make($request->all(),
    [
        "id"=>"required|integer"
    ]);
  
  
    if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
  }
  
  $user = $this->user->find($id);
  if (!$user) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, user with id ' . $id . ' cannot be found'
      ], 400);
  }
   $user->isOnline = 0;
   if($user->save()){
      return response()->json([
          'success' => true,
          "message"=>"user last location updated successfully"
      ],200);
     }
}



public function AddSocialMediaAccount(Request $request,$id)
{
    $validator = Validator::make($request->all(),
    [
        "facebook_handle"=>"required|string",
        "instagram_handle"=>"required|string"
    ]);
  
  
    if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
  }
  
  $user = $this->user->find($id);
  if (!$user) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, user with id ' . $id . ' cannot be found'
      ], 400);
  }


 $user->facebook_handle = $request->facebook_handle;
 $user->instagram_handle = $request->instagram_handle;
 if($user->save()){
    return response()->json([
        'success' => true,
        "message"=>"social media account updated"
    ],200);
   }    
}


public function GetSocialMediaAccount($id)
{
    $user = $this->user->find($id);
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user with id ' . $id . ' cannot be found'
        ], 400);
    }

 $facebook_handle = $user->facebook_handle;
 $instagram_handle = $user->instagram_handle;
 $data = array("facebook_handle"=>$facebook_handle,"instagram_handle"=>$instagram_handle);
    return response()->json([
        'success' => true,
        "data"=>$data
    ],200);
}



public function AddBankDetails(Request $request,$id)
{
    $validator = Validator::make($request->all(),
    [
        "account_number"=>"required|integer",
        "gaurantors_name"=>"required|string",
        "bank_name"=>"required|string"
    ]);
  
  
    if($validator->fails()){
      return response()->json([
       "success"=>false,
       "message"=>$validator->messages()->toArray(),
      ],400);    
  }
  
  $user = $this->user->find($id);
  if (!$user) {
      return response()->json([
          'success' => false,
          'message' => 'Sorry, user with id ' . $id . ' cannot be found'
      ], 400);
  }


 $user->gaurantors_name = $request->gaurantors_name;
 $user->bank = $request->bank_name;
$user->account_number = $request->account_number; 
 if($user->save()){
    return response()->json([
        'success' => true,
        "message"=>"social media account updated"
    ],200);
   } 
}

public function GetBankDetails($id)
{
    $user = $this->user->find($id);
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, user with id ' . $id . ' cannot be found'
        ], 400);
    }

 $bank = $user->bank;
 $account_number = $user->account_number;
 $gaurantors_name = $user->gaurantors_name;
 $data = array("bank"=>$bank,"account_number"=>$account_number,"gaurantors_name"=>$gaurantors_name);
    return response()->json([
        'success' => true,
        "data"=>$data
    ],200);
}




 }


