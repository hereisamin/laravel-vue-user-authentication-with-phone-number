<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\SmsIR_VerificationCode;
use App\Models\Sms;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class phoneRegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('phoneAuth/Register');
    }

    public function sendCode(Request $request){
        $request->validate([
            'phone' => 'required|unique:users|numeric',
        ]);
        $code=rand(00000, 99999);
        $sendSms= new SmsIR_VerificationCode(config('smsir.API'),config('smsir.SEC'),config('smsir.URL'));
        $result = $sendSms->verificationCode($code, $request->phone);
        if($result){
            Sms::create([
                'from'  =>'US',
                'to'    => $request->phone,
                'message'=> $result->Message.' Code IS: '.$code,
                'status' => $result->IsSuccessful,
                'response'=>$result->VerificationCodeId,
            ]);
            return Inertia::render('phoneAuth/Register', ['code'=>$code, 'smsSent'=>$result->IsSuccessful]);
        }else{
            Sms::create([
                'from'  =>'US',
                'to'    => $request->phone,
                'message'=> 'faild to send '.' Code IS: '.$code,
                'status' => 0,
                'response'=>'false',
            ]);
            return Inertia::render('phoneAuth/Register', ['code'=>$code, 'smsSent'=>$result]);
        }
    }

    public function store(Request $request){
        $user=User::create([
            'phone'      =>$request->phone,
            'name'          =>$request->name,
            'password'      => Hash::make($request->password),
            'registryType'  =>0,
            'phone_verified_at'   =>now(),
        ]);
        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
