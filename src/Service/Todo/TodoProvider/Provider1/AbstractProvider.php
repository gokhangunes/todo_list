<?php

namespace App\Service\Todo\TodoProvider\Provider1;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractProvider
{
    const BASE_URI = 'https://www.mocky.io/';

    protected HttpClientInterface $provider1Service;

    public function __construct()
    {
        $this->provider1Service = HttpClient::create([
            'base_uri' => self::BASE_URI,
        ]);
    }
}
