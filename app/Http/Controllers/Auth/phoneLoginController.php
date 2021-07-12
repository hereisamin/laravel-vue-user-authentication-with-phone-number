<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class phoneLoginController extends Controller
{
    public function create()
    {
        return Inertia::render('phoneAuth/Login');
    }

    public function setLogin(Request $request){

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password], $request->remember)) {
            return redirect(RouteServiceProvider::HOME);
        }else{
            return Inertia::render('phoneAuth/Login', ['msg'=>'Wrong Input']);
        }
//    $user=User::firstWhere('phone', $request->phone);
//        if (Hash::check($request->password, $user['password']))
//        {
//            Auth::login($user);
//            return redirect(RouteServiceProvider::HOME);
//        }else{
//            return Inertia::render('phoneAuth/Login', ['msg'=>'Wrong Input']);
//        }
    }
}
