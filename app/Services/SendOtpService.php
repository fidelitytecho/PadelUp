<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\OTP;
use App\Models\User;
use App\Notifications\SendOTP;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class SendOtpService
{
    /**
     * Send OTP
     *
     * @param string $mobile
     * @param string $message
     * @return void
     */
    public function SendOtpFunction(string $mobile)
    {
        $otp = rand(111111,999999);
        // $mobile = "admin@super.admin";
        $sms = OTP::create([
            'mobile' => $mobile,
            'otp' => $otp
        ]);
        Notification::send($sms, new SendOTP('Your OTP is '.$otp));
    }
    public function CheckOtpFunction(string $mobile, string $otp): JsonResponse
    {
        $valid = OTP::where('mobile', $mobile)->latest()->firstOrFail();
        if ($valid->otp == $otp)
        {
            $user = $valid ? User::where('mobile', $valid->mobile)->first() : null;
            OTP::where('mobile', $mobile)->delete();
            if ($token = auth('api')->login($user))
            {

                $userData = auth('api')->user();


                $returnArray = [
                    'success' => true,
                    'token' => $token,
                    'data' => $userData->hasRole(['Customer']) ? new UserResource(auth('api')->user()->load('Customer.Wallet', 'Skill')) : null
                ];

                return response()->json($returnArray);
            }
            return response()->json([
                'success' => false,
                'message'=> 'Unable to login, try again after some time'],
                404);
        }
        return response()->json([
            'success' => false,
            'message' => 'OTP does not exist'
        ]);

    }
}
