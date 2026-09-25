<?php

namespace App\Services;

use GuzzleHttp\Client;

class ApiClient
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://localhost:8000',
        ]);
    }

    public function client(): Client
    {
        return $this->client;
    }
}
