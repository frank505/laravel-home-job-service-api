<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SanitizeController extends Controller
{
    //
    public static function SanitizeString($string)
    {
       return filter_var($string, FILTER_SANITIZE_STRING);
    }
    public static function ValidateEmail($email)
    {
       return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function XssClean($data)
    {
      return htmlspecialchars($data);
    }
    public static function isNumber($number)
    {
        if(!is_numeric($number)){
            return FALSE;
        }else{
            return TRUE;
        }
    }
    public static function CheckIfEmpty($value)
    {
     if($value==""){
         return TRUE;
     }
    }
    
    //enciode data to make it saveable in db
    public static function ConvertHtmlCharactersToSaveToDb($html)
    {
        return htmlentities($html);
    }
    //decode data already encoded using the htmlentities function
    public static function RetrieveEncodedHtmlFromDb($html_encoded_data)
    {
        return html_entity_decode($html_encoded_data);
    }
    //available video extensions
    public static function CheckFileExtensions($file_ext,$array)
    {
      if(in_array($file_ext, $array)){
          return TRUE;
      }else{
          return FALSE;
      }
    }
    //check for image extenstions

//end of this class
}
