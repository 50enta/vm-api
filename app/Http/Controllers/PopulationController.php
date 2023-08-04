<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PopulationController extends Controller
{

    public function getPopulationByCountryYear()
    {
        $client = new Client();
        $currentYear = date('Y');
        $currentYear--;
        $response = $client->request('GET', "https://api.worldbank.org/v2/countries/all/indicators/SP.POP.TOTL?format=json&per_page=300&date=$currentYear:$currentYear");

        $data = json_decode($response->getBody())[1];

        $list = [];

        foreach ($data as $key) {
            $aux = $key->countryiso3code;
            $list[$aux] =  $key->value;
        }
        return $list;
    }

    public function getGdpByCountryYear()
    {

        $client = new Client();
        $currentYear = date('Y');
        $currentYear--;
        $response = $client->request('GET', "https://api.worldbank.org/v2/countries/all/indicators/NY.GDP.PCAP.CD?format=json&per_page=300&date=$currentYear:$currentYear");

        $data = json_decode($response->getBody())[1];

        $list = [];

        foreach ($data as $key) {
            $aux = $key->countryiso3code;
            $list[$aux] =  $key->value;
        }
        return $list;
    }
}
