<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //
    public function showResetPasswordView()
    {
        return view("users.reset-password.resetpassword");
    }
    public function ResetPasswordAction(Request $request)
    {
       
        $validator = $this->validate($request, [
            'email' => 'required|email',
        'password' => 'required|string|min:6',
        'password_confirmation' => 'required|string|min:6',
        ]);

    
    
    //   $prevToken = DB::table("password_resets")->where("email",$user_email)->first();
    //   if($prevToken){
    //       return $prevToken;
    //   }
       if($request->password != $request->password_confirmation)
       {
        return redirect("/reset-password")->with("error_password_same","password and password confirmation must be the same");
       }else{
           
          $token =  DB::table("password-resets")->where("token",$token)->first();
          if($token){
              DB::table("users")->where(["email"=>$request->email])->update([
                  "password"=>Hash::make($request->password)
              ]);
              return redirect("/reset-password")->with("success","password reset successfully");
          }
       }
         
    }
}
