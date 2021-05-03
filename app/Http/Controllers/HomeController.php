<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $setting = DB::table('settings')->latest('updated_at')->first();
        if(!$setting){
            $setting = new \stdClass();
            $setting->ck = "";
            $setting->cs = "";
            $setting->aURL = "";
            $setting->vURL = "";
            $setting->shortcode = "";
        }
        try {
            $setting->cs = Crypt::decryptString($setting->cs);
        } catch (DecryptException $e) {}

        return view('home',compact(['user','setting']));
    }
}
