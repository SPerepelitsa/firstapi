<?php

namespace App\Http\Controllers;

use App\Services\StatService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Stevebauman\Location\Facades\Location;

class PagesController extends Controller
{
    public function showFirstPage(Request $request)
    {
        $userData = [];
        if (Auth::check()) {
            $s = new StatService();
            $userData = $s->getUserData($request);
        }

        return view('pages/firstpage')->with('userData', $userData);
    }

    public function showSecondPage()
    {
        return view('pages/secondpage');
    }
}
