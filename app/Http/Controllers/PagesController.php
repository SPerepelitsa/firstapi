<?php

namespace App\Http\Controllers;

use App\Services\StatService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Cookie;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class PagesController extends Controller
{
    public function showFirstPage(Request $request)
    {
        $userData = [];
        // if the current user is authenticated
        if (Auth::check()) {
            $userId = Auth::id();
            $s = new StatService($userId);
            $userData = $s->getUserData();
            $s->saveUserData();
        // if the current user isn't authenticated
        } else {
            if ($request->hasCookie('temp_user')) {
                $tempUserId = $request->cookie('temp_user'); // gets cookies value
                $s = new StatService($tempUserId);
                $userData = $s->getUserData();
                $s->saveUserData();
            } else {
                $tempUserId = (string) Str::uuid();
                Cookie::queue(Cookie::forever('temp_user', $tempUserId)); // sets cookie
                $s = new StatService($tempUserId);
                $userData = $s->getUserData();
                $s->saveUserData();
            }
        }

        return view('pages/firstpage')->with('userData', $userData);
    }

    public function showSecondPage()
    {
        return view('pages/secondpage');
    }
}
