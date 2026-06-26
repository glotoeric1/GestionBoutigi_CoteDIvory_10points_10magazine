<?php

namespace App\Http\Controllers;

use App\Models\SmsToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SendSmsController extends Controller
{

    const INITIALIZE_URL = "https://api.orange.com";
    const SENDER_NUMBER = 'tel:+22373231645';
    const SENDERNAME = 'SkillCoding';

    public function sendSms($datas)
    {
        $countryCode = 'tel:+223';

        $url = self::INITIALIZE_URL . '/smsmessaging/v1/outbound/' . urlencode(self::SENDER_NUMBER)
            . '/requests';

        foreach ($datas as $value) {
            # code...
            //dd($value['contact']);

            //First API Call create an array and Make API Token call if expires and redirect user
            $option = array(
                'outboundSMSMessageRequest' => array(
                    'address' => $countryCode . $value['contact'],
                    'senderAddress' => self::SENDER_NUMBER,
                    'senderName' => self::SENDERNAME,
                    'outboundSMSTextMessage' => array(
                        'message' => $value['msg']
                    )
                )
            );

            //dd($option);

            //Get current time and check if token expires.
            $currentTime = Carbon::now();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();

            if ($currentAccessToken == '' || $currentAccessToken == null) {
                $tokenAccess = new SmsTokenController();
                $tokenAccess->getAccessToken();
                $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
            }

            $startTime = Carbon::parse($currentTime);
            $expiresTime = Carbon::parse($currentAccessToken->expiration_time);
            $minutes = $expiresTime->diffInMinutes($startTime);

            //dd($minutes);
            if ($minutes <= 0 || $minutes > 55) {
                //SmsToken::all()->delete();
                $tokenAccess = new SmsTokenController();
                $tokenAccess->getAccessToken();
                $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
            }

            //prepare header check if successful
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $currentAccessToken->access_token,
                'Content-Type' => 'application/json'
            ])->post($url, $option);

            if ($response->successful()) {
                return $response;
            } else {

                $tokenAccess = new SmsTokenController();
                $tokenAccess->getAccessToken();
                $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();

                //prepare header check if successful
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $currentAccessToken->access_token,
                    'Content-Type' => 'application/json'
                ])->post($url, $option);

                if ($response->successful()) {
                    return $response;
                } else {
                    return $response;
                }

            }
        }
    }

    public function getAdminStats(Request $request)
    {

        $url = self::INITIALIZE_URL . '/sms/admin/v1/statistics';

        //Get current time and check if token expires.
        $currentTime = Carbon::now();
        $startTime = Carbon::parse($currentTime);
        $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();

        if ($currentAccessToken === '' || $currentAccessToken === null) {
            $tokenAccess = new SmsTokenController();
            $tokenAccess->getAccessToken();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
        }

        $expiresTime = Carbon::parse($currentAccessToken->expiration_time);
        $minutes = $expiresTime->diffInMinutes($startTime);

        if ($minutes <= 0 || $minutes > 55) {
            $tokenAccess = new SmsTokenController();
            $tokenAccess->getAccessToken();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $currentAccessToken->access_token,
        ])->get($url, $request->all());

        $responseData = $response->json();
        if ($response->successful()) {
            return $responseData['partnerContracts'];
        } elseif ($response->failed()) {
            return $responseData;
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getAdminContracts(Request $request)
    {
        $url = self::INITIALIZE_URL . '/sms/admin/v1/contracts';

        //Get current time and check if token expires.
        $currentTime = Carbon::now();
        $startTime = Carbon::parse($currentTime);
        $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();

        if ($currentAccessToken === '' || $currentAccessToken === null) {
            $tokenAccess = new SmsTokenController();
            $tokenAccess->getAccessToken();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
        }

        $expiresTime = Carbon::parse($currentAccessToken->expiration_time);
        $minutes = $expiresTime->diffInMinutes($startTime);

        if ($minutes <= 0 || $minutes > 55) {
            $tokenAccess = new SmsTokenController();
            $tokenAccess->getAccessToken();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $currentAccessToken->access_token,
        ])->get($url, $request->all());

        $responseData = $response->json();
        if ($response->successful()) {
            return $responseData['partnerContracts'];
        } elseif ($response->failed()) {
            return $responseData;
        }
    }



    public function getAdminPurchasedBundles(Request $request)
    {

        $url = self::INITIALIZE_URL . '/sms/admin/v1/purchaseorders';

        //Get current time and check if token expires.
        $currentTime = Carbon::now();
        $startTime = Carbon::parse($currentTime);
        $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();

        if ($currentAccessToken === '' || $currentAccessToken === null) {
            $tokenAccess = new SmsTokenController();
            $tokenAccess->getAccessToken();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
        }

        $expiresTime = Carbon::parse($currentAccessToken->expiration_time);
        $minutes = $expiresTime->diffInMinutes($startTime);

        if ($minutes <= 0 || $minutes > 55) {
            $tokenAccess = new SmsTokenController();
            $tokenAccess->getAccessToken();
            $currentAccessToken = SmsToken::orderBy('created_at', 'desc')->first();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $currentAccessToken->access_token,
        ])->get($url, $request->all());

        $responseData = $response->json();
        if ($response->successful()) {
            return $responseData;
        } elseif ($response->failed()) {
            return $responseData;
        }
    }

}