<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RateController extends Controller
{

  public function getRates()
  {
    $apiKey = env('CURRENCY_API_KEY');

    $client = new Client();
    $response = $client->request('GET', 'https://api.freecurrencyapi.com/v1/currencies', [
      'query' => [
        'apikey' => $apiKey
      ]
    ]);

    return json_decode($response->getBody(), true)['data'];
  }

  public function convert($amount, $from, $to)
  {

    $apiKey = env('CURRENCY_API_KEY');

    $client = new Client();

    $response = $client->request('GET', 'https://api.freecurrencyapi.com/v1/latest', [
      'query' => [
        'apikey' => $apiKey,
        'base_currency' => $from
      ]
    ]);

    $rates = json_decode($response->getBody(), true)['data'];

    $converted = $amount * $rates[$to];

    return [
      'amount' => $amount,
      'from' => $from,
      'to' => $to,
      'converted' => $converted
    ];
  }


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
}
