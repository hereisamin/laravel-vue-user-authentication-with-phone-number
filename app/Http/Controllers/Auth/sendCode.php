<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class sendCode extends Controller
{
    public function sendCode(Request $request){
        $request->validate([
            'phone' => 'required|numeric|min:11'
        ]);
        return Inertia::render('phoneAuth/Register', ['code'=>1234]);
    }
}
