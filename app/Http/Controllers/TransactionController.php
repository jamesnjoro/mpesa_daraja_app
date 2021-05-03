<?php

namespace App\Http\Controllers;

use App\Setting;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{

    public function validationUrl(){
        return response()->json([
            'ResponseCode'=>'0',
            'ResponseDesc'=>'Validation successful'
        ]);
    }
    public function confirmationUrl(){
        return response()->json([
            'ResponseCode'=>'0',
            'ResponseDesc'=>'Confirmation successful.'
        ]);
    }
    public function getAuthenticationToken(Request $request){
        $validator = Validator::make($request->all(), [
            'url'=>'required',
            'registerUrl'=>'required',
            'consumerKey'=>'required',
            'consumerSecret'=>'required',
            'shortcode'=>'required'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        return response()->json($this->getToken($request->url,$request->consumerKey,$request->consumerSecret,$request->registerUrl,$request->shortcode));
    }

    public function registerUrl(Request $request){
        $validator = Validator::make($request->all(), [
            'url'=>'required',
            'registerUrl'=>'required',
            'consumerKey'=>'required',
            'consumerSecret'=>'required',
            'shortcode'=>'required'
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }

        $token = $this->getToken($request->url,$request->consumerKey,$request->consumerSecret,$request->registerUrl,$request->shortcode);
        if($token['statusC'] == 1){
            try{
                \Log::info(['val'=>route('validationURL'),'con'=>route('confirmationURL')]);
                $response = Http::withToken($token['token'])
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($request->registerUrl,[
                    'ShortCode'=>$request->shortcode,
                    'ValidationURL'=> route('validationURL'),
                    'ConfirmationURL'=> route('confirmationURL'),
                    'ResponseType'=>'Completed'
                ]);
                if($response->successful()){
                    return response()->json(['status'=>1, $response->json()]);
                }else{
                    return response()->json(['statusC'=>0, 'errorMessage'=>$response['errorMessage']]);
                }
            }catch (\Exception $e){
                return response()->json(['statusC'=>0,'errorMessage'=>"Invalid registeration URL"]);
            }
        }else{
            return response()->json($token);
        }
    }

    public function getToken($url, $consumerKey, $consumerSecret,$registerURL,$shortcode){
        try {
            $response = Http::withBasicAuth($consumerKey, $consumerSecret)
                ->get($url);
            if($response->successful()){
                Setting::create([
                    'ck'=>$consumerKey,
                    'cs'=>Crypt::encryptString($consumerSecret),
                    'aURL'=>$url,
                    'vURL'=>$registerURL,
                    'shortcode'=>$shortcode
                ]);
                return ['statusC'=>1, 'token'=>$response['access_token']];
            }else if($response->clientError()){
                return ['statusC'=>0, 'errorMessage'=>'Check provided credentials'];
            }else{
                return ['statusC'=>0, 'errorMessage'=>'Something went wrong, check URL supplied'];
            }
        } catch (\Exception $e) {
            \Log::info($e);
            return ['statusC'=>0, 'errorMessage'=>'Something went wrong, check URL supplied'];
        }
    }
}
