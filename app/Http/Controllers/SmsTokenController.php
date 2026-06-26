<?php

namespace App\Http\Controllers;

use App\Models\SmsToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SmsTokenController extends Controller
{
    //const GET_TOKEN_URL = "https://api.orange.com/orange-money-webpay/dev/v1/webpayment";

    const GET_TOKEN_URL = "https://api.orange.com/oauth/v3/token";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccessToken()
    {

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('app.authorization'),
        ])->asForm()->post(self::GET_TOKEN_URL, [
                    'grant_type' => 'client_credentials'
                ]);

        if ($response->successful()) {

            $responseData = $response->json();

            $expires_in = $responseData['expires_in'] - 300;
            $newDateTime = Carbon::now()->addSeconds($expires_in);

            $saveTokenToDb = SmsToken::create([
                'access_token' => $responseData['access_token'],
                'expires_in' => $responseData['expires_in'],
                'expiration_time' => $newDateTime,
            ]);

            return $response->json();

        } elseif ($response->failed()) {

            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . config('app.authorization'),
            ])->asForm()->post(self::GET_TOKEN_URL, [
                        'grant_type' => 'client_credentials'
                    ]);

            if ($response->successful()) {

                $responseData = $response->json();

                $expires_in = $responseData['expires_in'] - 300;
                $newDateTime = Carbon::now()->addSeconds($expires_in);

                $saveTokenToDb = SmsToken::create([
                    'access_token' => $responseData['access_token'],
                    'expires_in' => $responseData['expires_in'],
                    'expiration_time' => $newDateTime,
                ]);

                return $response->json();

            } elseif ($response->failed()) {
                return $response->json();
            }
        }

    }
}