<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\BookingServiceOptions;
use Validator;

class BookingServiceOptionsController extends Controller
{
    //
        //
        protected $BookingServiceOptions;
        public function __construct()
        {
            $this->middleware("auth:admins");
            $this->BookingServiceOptions = new BookingServiceOptions;
        }
    
        public function index($pagination=null,Request $request)
        {   
        //laravel automatically converts it to json and sends a response text too
        //$auth = auth("admins")->authenticate($request->token);
        if($pagination==null || $pagination==""){
            $BookingServiceOptions =  $this->BookingServiceOptions->where(["ispublic"=>1,"service_id"=>0])->get()->toArray();
            return response()->json([
                'success'=>true,
                'data'=>$BookingServiceOptions),    
                ]);
            
        }else{
            $BookingServiceOptions =  $this->BookingServiceOptions->where(["ispublic"=>1,"service_id"=>0])->
            paginate($pagination);
            return response()->json([
                'success'=>true,
                'data'=>$BookingServiceOptions,
            ]); 
        }
    }
    
    public function getServiceOptionsForParticularService(Request $request, $id,$pagination=null)
    {
        if($pagination==null || $pagination==""){
            $BookingServiceOptions =  $this->BookingServiceOptions->where(["service_id"=>$id])->get()->toArray();
            return response()->json([
                'success'=>true,
                'data'=>$BookingServiceOptions,    
                ]);
            
        }else{
            $BookingServiceOptions =  $this->BookingServiceOptions->where(["service_id"=>$id])->get()->toArray();
            return response()->json([
                'success'=>true,
                'data'=>$BookingServiceOptions,
            ]); 
        }
    }
    
    public function store(Request $request)
    {
        
      $validator  =  Validator::make($request->all(),
    [
    'service_id' => 'required|integer',
    'description' => 'required|string',
    'title'=>'required|string',
    'type'=>'required|string',
    'required'=>'required|string',
    'selected'=>'required|string',
    'display'=>'required|string',
    'order'=>'required|string',
    'price'=>'required|string'
    ]);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }

      if($request->options==null){
        $options = null;
    }  else{
      $options = json_encode($request->options);
    }

    $space_checker = preg_match("/\s/",$request->title);
    if($space_checker==0){
        $add_dash_url = $request->title;
    }else{
      $add_dash_url = str_replace(" ","_",$request->title);   
    }
        $created =  $this->BookingServiceOptions::create(
        [
            'service_id'=>$request->service_id,
        'description'=>$request->description,
        'title'=>$request->title,
        'name'=>$add_dash_url,
        'type'=>$request->type,
        'required'=>$request->required,
         'display'=>$request->display,
         "selected"=>$request->selected,
         'order'=>$request->order,
         'options'=>$options,
         'price'=>$request->price,
        ]);
          
        if($created){
            return response()->json([
                'success' => true,
                'message' => 'booking service option added successfully'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Sorry, content couldnt be deleted'
            ], 500);
        }
            
    }
    
    public function update(Request $request,$id)
    {
        $BookingServiceOptions = $this->BookingServiceOptions->where(["service_id"=>$id])->count();
        if($BookingServiceOptions==0){
            return response()->json([
                'success' => false,
                'message' => 'Sorry, BookingServiceOptions with id ' . $id . ' cannot be found'
            ], 400);
        }


       
    $validator =    Validator::make($request->all(),
    [
      //  'service_id' => 'required|integer',
    'description' => 'required|string',
    'title'=>'required|string',
    'type'=>'required|string',
    'required'=>'required|string',
    'selected'=>'required|string',
    'display'=>'required|string',
    'order'=>'required|string',
    'price'=>'required|string'
        ]);
       
        if($validator->fails()){
            return response()->json([
             "success"=>false,
             "message"=>$validator->messages()->toArray(),
            ],400);    
          }          
          if($request->options==null){
            $options = null;
        }  else{
          $options = json_encode($request->options);
        }
    
        $space_checker = preg_match("/\s/",$request->title);
        if($space_checker==0){
            $add_dash_url = $request->title;
        }else{
          $add_dash_url = str_replace(" ","_",$request->title);   
        }

    $update = $this->BookingServiceOptions::where(["service_id"=>$id])->update(
        [
            //'service_id'=>$request->service_id,
            'description'=>$request->description,
        'title'=>$request->title,
        'name'=>$add_dash_url,
        'type'=>$request->type,
        'required'=>$request->required,
         'display'=>$request->display,
         "selected"=>$request->selected,
         'order'=>$request->order,
         'options'=>$options,
         'price'=>$request->price,

        ]);
       
    if ($update) {
    return response()->json([
        'success' => true,
        'message'=>'service id contents updated successfully'
    ]);
    } else {
    return response()->json([
        'success' => false,
        'message' => 'Sorry, BookingServiceOptions could not be updated'
    ], 500);
    }
    }
    
    //this is to delete
    public function delete(Request $request,$id)
    {
        $BookingServiceOptions = $this->BookingServiceOptions->where(["service_id"=>$id])->count();
     
        if ($BookingServiceOptions==0) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, service form options with id ' . $id . ' cannot be found'
            ], 400);
        }
        $get_content = $this->BookingServiceOptions->where(["service_id"=>$id])->get();
        foreach ($get_content as $key => $value) {
           $content_id = $value->id;
           $this->BookingServiceOptions->where("id",$content_id)->delete();
        }
     
                return response()->json([
              'success' => true,
              'message'=>'delete successful',
          ]);
        
    }
    
}


