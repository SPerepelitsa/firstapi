<?php

namespace App\Http\Controllers;

use App\Services\StatService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Cookie;
use Illuminate\Support\Str;
use App\UserStat;
use Stevebauman\Location\Facades\Location;

class PagesController extends Controller
{
    public function showFirstPage(Request $request)
    {
        $statService = new StatService();
        if (Auth::check()) {
            $userData = $statService->saveUserData(Auth::id());
            
        } else {
            $tempUserId = $request->cookie('temp_user') ?? Str::uuid()->toString();
            if (!$request->hasCookie('temp_user')) {
                Cookie::queue(Cookie::forever('temp_user', $tempUserId));
            }
            $userData = $statService->saveTempUserData($tempUserId);
        }

        return view('pages/firstpage')->with('userData', $userData);
    }

    public function showSecondPage()
    {
        return view('pages/secondpage');
    }
}
