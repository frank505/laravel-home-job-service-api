<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use App\ServiceFormOptions;
use Validator;

class ServiceFormOptionsController extends Controller
{
    //
    protected $ServiceFormOptions;
    public function __construct()
    {
        $this->middleware("auth:admins",['except'=>['index','getServiceOptionsForParticularService']]);
        $this->ServiceFormOptions = new ServiceFormOptions;
    }

    public function index($pagination=null,Request $request)
    {   
    //laravel automatically converts it to json and sends a response text too
    //$auth = auth("admins")->authenticate($request->token);
    if($pagination==null || $pagination==""){
        $ServiceFormOptions =  $this->ServiceFormOptions->get()->toArray();
        return response()->json([
            'success'=>true,
            'data'=>json_decode($ServiceFormOptions),    
            ]);
        
    }else{
        $ServiceFormOptions= $this->ServiceFormOptions->paginate($pagination);
        return response()->json([
            'success'=>true,
            'data'=>json_decode($ServiceFormOptions),    
            ]);
    }
}

public function getServiceOptionsForParticularService(Request $request, $id,$pagination=null)
{
    if($pagination==null || $pagination==""){
        $ServiceFormOptions =  $this->ServiceFormOptions->where(["service_id"=>$id])->get(["options"])->toArray();
        return response()->json([
            'success'=>true,
            'data'=>json_decode($ServiceFormOptions),
        ]);
    }else{
        $ServiceFormOptions= $this->ServiceFormOptions->paginate($pagination);
        return response()->json([
            'success'=>true,
            'data'=>json_decode($ServiceFormOptions),    
            ]);
    }
   
}

public function store(Request $request)
{
 

     $validator = Validator::make($request->all(), 
    [
        'service_id' => 'required|integer',
    'type'=>'required|string',
     'title'=>'required|string',
    'display' => 'required|string',
    'required'=>'required|string',
    'order'=>'required|string',
    'ispublic'=>'required|boolean',
    'price'=>'required|integer',
    'selected'=>'required|string']);
     
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
      $space_checker = preg_match("/\s/",$request->title);
      if($space_checker==0){
          $add_dash_url = $request->title;
      }else{
        $add_dash_url = str_replace(" ","_",$request->title);   
      }

      if($request->options==null){
        $options = null;
    }  else{
      $options = json_encode($request->options);
    }

  

    $created =  $this->ServiceFormOptions::create(
    [
       'service_id'=>$request->service_id,
    'type'=>$request->type,
    'title'=>$request->title,
     'name'=>$add_dash_url,
     'display'=>$request->display,
     'required'=>$request->required,
     'order'=>$request->order,
     'ispublic'=>$request->ispublic,
     'price'=>$request->price,
     'options'=>$options,
     'selected'=>$request->selected
    ]);
      
    if($created){
        return response()->json([
            'success' => true,
            'message' => 'service added successfully'
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
    $ServiceFormOptions = $this->ServiceFormOptions->where(["service_id"=>$id])->count();
    if ($ServiceFormOptions==0) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, ServiceFormOptions with id ' . $id . ' cannot be found'
        ], 400);
    }
    
    $validator = Validator::make($request->all(), 
    [
        'service_id' => 'required|integer',
    'type'=>'required|string',
    'title'=>'required|string',
    'display' => 'required|string',
    'required'=>'required|string',
    'order'=>'required|string',
    'ispublic'=>'required|boolean',
    'price'=>'required|integer',
    'selected'=>'required|string']);
    if($validator->fails()){
        return response()->json([
         "success"=>false,
         "message"=>$validator->messages()->toArray(),
        ],400);    
      }
      $space_checker = preg_match("/\s/",$request->title);
      if($space_checker==0){
          $add_dash_url = $request->title;
      }else{
        $add_dash_url = str_replace(" ","_",$request->title);   
      }
      if($request->options==null){
        $options = null;
    }  else{
      $options = json_encode($request->options);
    }


$update = $this->ServiceFormOptions::where(["service_id"=>$id])->update(
    [
        'service_id'=>$request->service_id,
        'type'=>$request->type,
        'title'=>$request->title;
         'name'=>$add_dash_url,
         'display'=>$request->display,
        'required'=>$request->required,
         'order'=>$request->order,
         'ispublic'=>$request->ispublic,
         'price'=>$request->price,
         'options'=>$options,
         'selected'=>$request->selected
    ]);
   
if ($update) {
return response()->json([
    'success' => true,
    'message'=>'service form options was updated successfully'
]);
} else {
return response()->json([
    'success' => false,
    'message' => 'Sorry, ServiceFormOptions could not be updated'
], 500);
}
}

//this is to delete
public function delete(Request $request,$id)
{
    $ServiceFormOptions = $this->ServiceFormOptions->where(["service_id"=>$id])->count();
 
    if ($ServiceFormOptions==0) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, service form options with id ' . $id . ' cannot be found'
        ], 400);
    }
      $get_content = $this->ServiceFormOptions->where(["service_id"=>$id])->get();
      foreach ($get_content as $key => $value) {
         $content_id = $value->id;
         $this->ServiceFormOptions->where("id",$content_id)->delete();
      }
   
              return response()->json([
            'success' => true,
            'message'=>'delete successful',
        ]);
    
}

//end of this class
}