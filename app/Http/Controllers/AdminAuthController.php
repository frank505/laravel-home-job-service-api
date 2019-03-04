<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\admin;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterAuthRequest;
use Illuminate\Support\Facades\Hash;
use App\ManageUser;

class AdminAuthController extends Controller
{
    //
    public $loginAfterSignUp = true;
   protected $admin;
    public function __construct(){
       $this->middleware("auth:admins",['except'=>['login']]);
       $this->admin = new admin();
       $this->user = new ManageUser();
   }
    public function register(Request $request)
    {
        $this->validate($request,
        ['name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|max:10']);

        $this->admin->name = $request->name;
        $this->admin->email = $request->email;
        $this->admin->password = Hash::make($request->password);
        $this->admin->save();
 
        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }
 
        return response()->json([
            'success' => true,
            'data' => $admin
        ], 200);
    }
 
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
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
        $this->validate($request, [
            'token' => 'required'
        ]);
 
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
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $admin = auth("admins")->authenticate($request->token);
 
        return response()->json(['admin' => $admin]);
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
            return $this->user->get()->toArray();
        }
            $paginated_user =  $this->user->paginate($pagination);
            return response()->json([
                'success' => true,
                 'data'=>$paginated_user
            ], 200);
        }
    
        public function showUserProfile($id)
        {
            $users = $this->user->find($id);
            if (!$users) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, user with id ' . $id . ' cannot be found',
                    'img_path'=>'images/user'
                ], 400);
            }
         
            return $users;
        }
        
        
}
