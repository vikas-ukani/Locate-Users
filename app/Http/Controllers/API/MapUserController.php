<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\FileUsers;
use Illuminate\Support\Facades\Cache;

class MapUserController extends Controller
{
    use FileUsers;

    public const RADIUS = 2000; // 2000 KM RADIUS, (Increase if you want more data on map, ex. 2500, 3000, 4000 )

    /**
     * Get All User API based on Location.
     *
     * @return void
     */
    public function getUsers()
    {
        $ttl = now()->addMinutes(60); // Set time for Cache expiry // default to 1 hour

        // Cache::forget('users'); // Developer only.
        // Get Cached Data Otherwise get data from File.
        $data = Cache::remember('users', $ttl, function () {
            return $this->getUsersFromFile();
        });

        /** Gender Filter */
        if (request('gender')) {
            $data = collect($data)->where('gender', request('gender'))->all();
        }

        /** First Name Filter */
        if (request('first_name')) {
            $searchFirstName = request('first_name');
            $data = collect($data)->filter(function ($item) use ($searchFirstName) {
                return stripos($item['first_name'], $searchFirstName) !== false;
            });
        }
        /** Last Name Filter */
        if (request('last_name')) {
            $searchLasttName = request('last_name');
            $data = collect($data)->filter(function ($item) use ($searchLasttName) {
                return stripos($item['last_name'], $searchLasttName) !== false;
            });
        }

        /** Latitude and longitude Filter radius of 2000KM. */
        if (request('lat') && request('lon')) {
            $lat = request('lat');
            $lon = request('lon');
            $data = $this->getLatLongWiseUsers($data, $lat, $lon);
        }

        $data = array_values($data);
        return response()->json($data, 200);
    }

    /**
     * Get Latitude and longitude wise users with radius of 2000km
     *
     * @param [type] $data
     * @param [type] $lat
     * @param [type] $lon
     * @return void
     */
    public function getLatLongWiseUsers($data, $lat, $lon)
    {
        return collect($data)->filter(function ($user) use ($lat, $lon) {
            $userLat = $user['lat'];
            $userLon = $user['lon'];

            $a = $lat - $userLat;
            $b = $lon - $userLon;
            $distance = sqrt(($a ** 2) + ($b ** 2));

            $lat1 = deg2rad($userLat);
            $lon1 = deg2rad($userLon);
            $lat2 = deg2rad($lat);
            $lon2 = deg2rad($lon);

            $delta_lat = $lat2 - $lat1;
            $delta_lng = $lon2 - $lon1;

            $hav_lat = (sin($delta_lat / 2)) ** 2;
            $hav_lng = (sin($delta_lng / 2)) ** 2;

            $distance = 2 * asin(sqrt($hav_lat + cos($lat1) * cos($lat2) * $hav_lng));

            $earth_radius_km = 6371.009;
            $actual_distance = $earth_radius_km * sqrt($distance);
            return $actual_distance < self::RADIUS;
        })->all();
    }
}
