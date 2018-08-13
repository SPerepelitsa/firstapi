<?php

namespace App\Services;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class StatService
{

    public function __construct()
    {
        //TODO
    }

    public function getUserLocation($ip)
    {
        $position = Location::get($ip); //https://github.com/stevebauman/location 

        return $position;
    }

    public function getUserData(Request $request)
    {
        $userIp = $request->ip();
        $userAgent = $request->userAgent();
        $position = $this->getUserLocation($userIp);
        if ($position) {
            $countryCode = $position->countryCode;
            $regionName = $position->regionName;
            $cityName = $position->cityName;
        } else {
            $countryCode = null;
            $regionName = null;
            $cityName = null;
        }
        $userData = [
            'ip' => $userIp,
            'agent' => $userAgent,
            'country_code' => $countryCode,
            'region_name' => $regionName,
            'city_name' => $cityName,
            'previous_page'=> \Request::server('HTTP_REFERER') ?? '',
        ];

        return $userData;
    }
}