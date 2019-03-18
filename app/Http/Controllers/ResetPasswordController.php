<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //
    
    public function showResetPasswordView(Request $request)
    {
        return view("users.reset-password.resetpassword")->with("token",$request->query("token"));
    }
    public function ResetPasswordAction(Request $request)
    {
       
        $validator = $this->validate($request, [
            'email' => 'required|email',
        'password' => 'required|string|min:6',
        'password_confirmation' => 'required|string|min:6',
        ]);
    
    
    $token = $request->token;
       if($request->password != $request->password_confirmation)
       {
        return redirect("/reset-password?token=".$token)->with("custom_error","password and password confirmation must be the same");
       }else{
           
          $check_data_from_db =  DB::table("password_resets")->where(["token"=>$token,"email"=>$request->email])->count();
          if($check_data_from_db!==0){
              $hash_password = Hash::make($request->password);
              DB::table('users')->where(["email"=>$request->email])->update([
                  "password"=>$hash_password,
              ]);
         //later please delete this token
              return redirect("/reset-password?token=".$token)->with("success","your password was successfully reset");
          }else{
            return redirect("/reset-password?token=".$token)->with("custom_error","invalid email or token is expired or invalid");
          }
       }
         
    }
}