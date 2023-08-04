<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class WeatherController extends Controller
{

    public function getWeather($city) {

        $apiKey = env('WEATHER_API_KEY'); 

        $client = new Client();

        $endpoint = 'https://api.openweathermap.org/data/2.5/weather?q=' . $city . '&appid=' . $apiKey;
        $response = $client->request('GET', $endpoint);
        $weatherData = json_decode($response->getBody()->getContents(), true);

        return response()->json($weatherData);
    }

}
