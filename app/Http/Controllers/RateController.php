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
    $response = $client->request('GET', 'https://api.freecurrencyapi.com/v1/currencies?per_page=200', [
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
