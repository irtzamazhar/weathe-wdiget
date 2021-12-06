<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SiteController extends Controller
{
    public function getWeatherData(Request $request)
    {
        $appId = env('OPEN_WEATHER_API_KEY');
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?q={$request->location}&appid={$appId}&units=metric");
        return response()->json($response->json());
    }

    public function getCurrentLocationWeatherData(Request $request)
    {
        $appId = env('OPEN_WEATHER_API_KEY');
        $response = Http::get("https://api.openweathermap.org/data/2.5/weather?lat={$request->lat}&lon={$request->lon}&appid={$appId}&units=metric");
        return response()->json($response->json());
    }
}
