<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Library\SmsIR_VerificationCode;
use App\Models\Sms;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class phonePasswordResetController extends Controller
{
    public function create()
    {
        return Inertia::render('phoneAuth/passwordReset');
    }

    public function sendCode(Request $request){
        if(!User::where('phone', $request->phone)->count()){
            return Inertia::render('phoneAuth/passwordReset')->with('errors',['This phone is not registered.']);
        }
        $code=rand(10000, 99999);
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
            return Inertia::render('phoneAuth/passwordReset', ['code'=>$code, 'smsSent'=>$result->IsSuccessful]);
        }else{
            Sms::create([
                'from'  =>'US',
                'to'    => $request->phone,
                'message'=> 'faild to send '.' Code IS: '.$code,
                'status' => 0,
                'response'=>'false',
            ]);
            return Inertia::render('phoneAuth/passwordReset', ['code'=>$code, 'smsSent'=>$result]);
        }
    }

    public function setPassword(Request $request){
        if ($request->password == $request->confirmPassword){
            User::where('phone', $request->phone)->update(['password' => Hash::make($request->password) ]);
            return Inertia::render('phoneAuth/Login');
        }else{
            return Inertia::render('phoneAuth/passwordReset')->with('errors', ['Password and confirmation are not same']);
        }
    }
}
