<?php
namespace App\Util;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Lunch
{
    public function date($date)
    {

        $client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $response = $client->post('/lunch', ['form_params' => ['date' => $date]]);
        return $response->getBody()->getContents();
        
    }
}