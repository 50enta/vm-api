<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CountryController extends Controller
{

    public function getCountries()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://api.worldbank.org/v2/country?format=json&per_page=300');

        $data = json_decode($response->getBody())[1];

        $countries = [];

        foreach ($data as $country) {
            if (!preg_match('/^[a-zA-Z][0-9]/', $country->iso2Code)) {
                $countries[] = [
                    'code' => $country->id,
                    'name' => $country->name
                ];
            }
        }

        return $countries;
    }



    public function getPopulation($countryCode)
    {
        $client = new Client();
        $response = $client->request('GET', 'https://api.worldbank.org/v2/countries/CA/indicators/SP.POP.TOTL');

        $data = json_decode($response->getBody());

        return end($data[1][0]->data)->value;
    }

    public function getCountriesData(Request $request)
    {

        $countriesData = [];
        $countriesCollection = $this->getCountries();
        $populations = $this->getPopulationByCountryYear();
        $gdps = $this->getGdpByCountryYear();
        $currencies = $this->getExchangeRates();

        foreach ($countriesCollection as $country) {

            $countr = $country['name'];
            $code = $country['code'];


            // var_dump($populations);

            if (array_key_exists($code, $populations) && array_key_exists($code, $gdps) && array_key_exists($code, $currencies)) {
                $countriesData[] = [
                    'code' => $code,
                    'country' => $countr,
                    'population' => $populations[$code],
                    'gdp' => $gdps[$code],
                    'exchange' => $currencies[$code],
                    // 'weather' => "max = 22, min = 12"
                ];
            }
        }

        return response()->json(["countries" => $countriesData]);
    }


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


    public function getExchangeRates()
    {

        $client = new Client();
        $response = $client->request('GET', 'https://restcountries.com/v3.1/all');

        $data = json_decode($response->getBody(), true);

        $rates = [];

        foreach ($data as $country) {
            $cca3 = $country['cca3'];

            if (isset($country['currencies'])) {
                $currency = reset($country['currencies']);
                if (isset($currency['symbol'])) {
                    $symbol = $currency['symbol'];
                    $name = $currency['name'];
                    
                    $rates[$cca3] = $name.' - '.$symbol;
                    
                }
            }
        }

        return $rates;
    }
}
