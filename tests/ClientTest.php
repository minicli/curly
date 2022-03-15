<?php

use Minicli\Curly\Client;
use Minicli\Curly\CurlAgent;

it('creates a client with a curl agent by default', function () {
    $client = new Client();
    expect($client->agent)->toBeInstanceOf(CurlAgent::class);
});

it('sets default headers with User-Agent', function () {
    $client = new Client();
    $headers = $client->getHeaders();
    expect($headers)->toBeArray();
    expect($headers)->toContain('User-Agent: Curly 0.2');
});
