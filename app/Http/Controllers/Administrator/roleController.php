<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\role;
use JWTAuth;

class roleController extends Controller
{
    //
    protected $admin;
    protected $role;
public function __construct()
{
    $this->middleware("auth:admins");
     $this->role = new role();
    
}

public function index($pagination=null,Request $request)
{
//laravel automatically converts it to json and sends a response text too
//$auth = auth("admins")->authenticate($request->token);
if($pagination==null || $pagination==""){
    return $this->role->get(['id', 'rolename', 'isdefault','created_at'])->toArray();
}
    $paginated_roles =  $this->role->paginate($pagination,["id","rolename","isdefault","created_at"]);
    return response()->json([
        'success' => true,
         'data'=>$paginated_roles
    ], 200);
}

public function show($id)
{
    $role = $this->role->find($id);
    if (!$role) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, role with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    return $role;
}

public function store(Request $request)
{

    $this->validate($request, [
        'rolename' => 'required',
        'isdefault' => 'required',
    ]);
 
    $this->role->rolename = $request->rolename;
    $this->role->isdefault = $request->isdefault;
 
    if ($this->role->save()){
        return response()->json([
            'success' => true,
            'role' => $this->role
        ]);
    }   
    else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, role could not be added'
        ], 500);
    }
}

public function update(Request $request,$id)
{
    $role = $this->role->find($id);
    if (!$role) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, role with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    $update = $role->fill($request->all())->save();

    if ($update) {
        return response()->json([
            'success' => true
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, role could not be updated'
        ], 500);
    }
}

public function delete(Request $request,$id)
{
    $role = $this->role->find($id);
 
    if (!$role) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, role with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    if ($role->delete()) {
        return response()->json([
            'success' => true,
            'message'=>'delete successful'
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'role could not be deleted'
        ], 500);
    }
}

public function default(Request $request,$id)
{
    $role = $this->role->find($id);
 
    if (!$role) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, role with id ' . $id . ' cannot be found'
        ], 400);
    }
   $set_default = $this->role::where(["id"=>$id])->update(["rolename"=>"default"]);
   if($set_default)
   {
    return response()->json([
        'success' => true,
        'message'=>'this role has been set as default successfully'
    ]);
} else {
    return response()->json([
        'success' => false,
        'message' => 'role could not be set as default'
    ], 500);
}
}
//end of this class
}
