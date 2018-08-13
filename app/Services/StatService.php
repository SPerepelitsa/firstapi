<?php

namespace App\Services;

use App\UserStat;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Jenssegers\Agent\Agent;
use Auth;


class StatService
{
    private $userIp;
    public $userAgent;
    public $position;
    private $userData;

    public function __construct(Request $request)
    {
        $this->userIp = $request->ip();
        $this->userAgent = $this->getUserAgentData();
        $this->position = $this->getUserLocation($this->userIp);
        $this->userData = $this->getUserData();
    }

    public function getUserLocation($ip)
    {
        $position = Location::get($ip); //https://github.com/stevebauman/location

        if ($position) {
            $countryCode = $position->countryCode;
            $regionName = $position->regionName;
            $cityName = $position->cityName;
        } else {
            $countryCode = null;
            $regionName = null;
            $cityName = null;
        }
        return [
            'country_code' => $countryCode,
            'region_name' => $regionName,
            'city_name' => $cityName,
        ];
    }

    public function getUserAgentData()
    {
        $agent = new Agent(); //https://github.com/jenssegers/agent
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $platform = $agent->platform();
        $osVersion = $agent->version($platform);

        return  [
            'browser' => $browser,
            'browser_version' => $browserVersion,
            'os' => $platform,
            'os_version' => $osVersion,
        ];
    }

    public function getUserData()
    {
        $userIp = $this->userIp;
        $userAgent = $this->userAgent;
        $position = $this->position;

        $mainUserData = [
            'ip' => $userIp,
            'previous_page'=> \Request::server('HTTP_REFERER') ?? '',
            'visited_at' => date('Y-m-d H:i:s A'),
        ];

        $userData = array_merge($mainUserData, $userAgent, $position);

        return $userData;
    }

    public function saveUserData()
    {
        $userData = $this->userData;
        $userStat = new UserStat();
        $userStat->user_id = Auth::id();
        $userStat->ip = $userData['ip'];
        $userStat->country_code = $userData['country_code'];
        $userStat->region_name = $userData['region_name'];
        $userStat->city_name = $userData['city_name'];
        $userStat->browser = $userData['browser'];
        $userStat->browser_version = $userData['browser_version'];
        $userStat->os = $userData['os'];
        $userStat->os_version = $userData['os_version'];
        $userStat->previous_page = $userData['previous_page'];
        $userStat->visited_at = $userData['visited_at'];

        return $userStat->save();
    }
}