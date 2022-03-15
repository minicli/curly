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
    expect($headers)
        ->toBeArray()
        ->toContain('User-Agent: Curly 0.2');
});

it('returns empty array when obtaining request info before a request is made', function() {
    $client = new Client();
    expect($client->getRequestInfo())
        ->toBeArray()
        ->toBeEmpty();
});

it('makes a successful GET request to the GitHub API and returns request info', function() {
    $client = new Client();
    $response = $client->get(
        'https://api.github.com/rate_limit',
        [ 'Accept: application/vnd.github.v3+json' ]
    );
    //expect($response)->dd();
    expect($response)->toBeArray()->toHaveKeys(['code', 'body']);
    expect($response['code'])->toBeIn([200, 301, 302]);
    expect($client->getRequestInfo())
        ->toBeArray()
        ->not()->toBeEmpty();
});