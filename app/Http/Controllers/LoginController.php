<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        $result = Auth::attempt($credentials, $request->boolean('remember'));
        // $user = User::where('email', '=', $request->email)->first();
        // // ->where('password', '=',md5( $request->password));//عنا ال md5 بترجعه مشفر لو احنا مستخدمين md5
        // if($user && Hash::check($request->password,$user->password))
        // {
        //     Auth::login($user, $request->boolean('remember'));
        if ($result) {
            // return redirect(route('calssrooms.index'));
            return redirect()->intended('/');//رح يرجعه عالصفحة يلي طلبها قبل ال login ولو ما في صفحة بيوديه عالهوم
        }

        return back()->withInput()->withErrors([
            'email' => __('Invalid Credentails'),
        ]);
    }
}
