<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\User;


use App\Http\Controllers\user\UserAdminController;

class UserController extends UserAdminController
{
    
     public function suggestlist(Request $request){
          if($request->input != '/'  &&  $request->input != '.')
                    $users['results'] = User::join('sponsortree','sponsortree.user_id','=','users.id')->where('sponsortree.sponsor','=',Auth::user()->id)->where('username','like',"%".trim($request->input)."%")->select('users.id','username as value','email as info')->get();
           else   
                    $users['results'] = User::join('sponsortree','sponsortree.user_id','=','users.id')->where('sponsortree.sponsor','=',Auth::user()->id)->select('users.id','username as value','email as info')->get();

          echo json_encode($users);

     }
}
