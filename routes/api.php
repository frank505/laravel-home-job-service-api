<?php

use Illuminate\Http\Request;





Route::group(['prefix' => 'admin',
'middleware' => ['CORS']],function ()
{
    Route::post('/login', 'AdminAuthController@login');
     //this might be useful though an admin registering another admin
   //  Route::post("/register","AdminAuthController@register");
    //this is to display all the available roles
    Route::post("/logout","AdminAuthController@logout");
    //display admin profile
    Route::get("/profile","AdminAuthController@getAuthadmin");
    Route::get('role/all-content/{pagination?}', 'roleController@index');
    //this is to display individual roles
    Route::get('role/show/{id}', 'roleController@show');
    //this is to store roles
    Route::post('role/store', 'roleController@store');
    //this is to update roles
    Route::put('role/update/{id}', 'roleController@update');
    //this is to set default roles
    Route::post("role/default/{id}",'roleController@default');
    //this is to delete a role
    Route::delete('role/delete/{id}', 'roleController@delete');
    //this routes are for admin relationship with users
    Route::post('/user/ban/{id}','AdminAuthController@banUser');
    Route::post('/user/remove-ban/{id}','AdminAuthController@removeBan');   
    Route::get('user/all/{pagination?}','AdminAuthController@ViewUsers');
    Route::get('user/single/{id}','AdminAuthController@showUserProfile');
});

Route::group(["prefix"=>"user","middleware"=>["CORS"]],function(){
    Route::post('register', 'UserAuthController@register');    
    Route::post('login', 'UserAuthController@login');
    Route::post("edit/{id}","UserAuthController@editProfile");
    Route::post("logout","UserAuthController@logout");
    //here we pass the post data which is the token through the body
    Route::post("/profile","UserAuthController@getAuthUser");
});


