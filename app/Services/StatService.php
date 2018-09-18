<?php

namespace App\Services;

use App\UserStat;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use Jenssegers\Agent\Agent;
use Auth;
use Cookie;

class StatService
{
    private function getIpAddress()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddresses = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return trim(end($ipAddresses));
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    private function getUserLocation($ip)
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

    private function getUserAgentData()
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

    private function getUserData()
    {
        $userIp = $this->getIpAddress();
        $userAgent = $this->getUserAgentData();
        $position = $this->getUserLocation($userIp);

        $mainUserData = [
            'ip' => $userIp,
            'previous_page'=> \Request::server('HTTP_REFERER') ?? '',
            'visited_at' => time(),
        ];

        $userData = array_merge($mainUserData, $userAgent, $position);

        return $userData;
    }

    public function saveUserData($userId)
    {
        $userData = $this->getUserData();
        $userStat = new UserStat();
        $userStat->user_id = $userId;
        $userStat->temp_user_id = null;
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

        return $userStat->save() ? $userData : null;
    }

    public function saveTempUserData($tempUserId)
    {
        $userData = $this->getUserData();
        $userStat = new UserStat();
        $userStat->user_id = null;
        $userStat->temp_user_id = $tempUserId;
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

        return $userStat->save() ? $userData : null;
    }

    public function rewriteStatAfterLogin($userId, $tempUserId)
    {
        $result = UserStat::where('temp_user_id', $tempUserId )->update(['user_id' => $userId, 'temp_user_id' => null]);

        return $result ?: false;
    }

    public function clearOldStat()
    {
        $weekAgo = strtotime("-1 week");
        UserStat::whereNotNull('temp_user_id')->where('visited_at', '<=', $weekAgo)->delete();
    }
}