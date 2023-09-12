<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Response;

class AccessTokensController extends Controller
{
    public function index(Request $request){
        return  $request->user('sanctum')->tokens; //Auth::guard('sanctum')->
    }
    public function store(Request $request){
        $request->validate([
            'email'=>['required','email'],
            'password'=>['required'],
            'device_name'=>['sometimes','required'],
            'abilities'=>['array']
        ]);
        // Auth::guard('sanctum')->attempt([
        //     'email'
        // ])
        $user=User::whereEmail($request->email)->first();
        if($user && Hash::check($request->password,$user->password)){
           $name=$request->post('device_name',$request->userAgent());
          $abilities=$request->post('abilities',['*']);
           $token=$user->createToken($name,$abilities,now()->addDays(90));//$request->input('abilities'));
         return Response::json([
           
            'token'=>$token->plainTextToken,
            'user'=>$user,
         ],201);
        }
        return Response::json([
            'message'=>__('Invalid credentials')
        ],401);
    }

public function destroy($id=null){
      $user=Auth::guard('sanctum')->user();
      //Revoke (logout froom current device)
      if($id){
             if($id=='current'){ 
            $user->currentAccessToken()->delete();
   }else{
        $user->tokens()->findOrFail($id)->delete();//->destroy($id);//->findOrF()
      }
    }else{
//(logout froom all device)

        $user->tokens()->delete();
    }

}









}
