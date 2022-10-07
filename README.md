# curly
A tiny experimental curl client built for Minicli (but can be also used standalone). 

## Requirements

Requires `curl` and `php-curl`.

## Installation

To include Curly in your app, first require the dependency with Composer:

```shell
composer require minicli/curly
```

## Usage

Use the `Minicli/Curl/Client` class to make GET and POST requests using the `get()` and `post()` methods, respectively.

This example queries the DEV API and gets the latest posts from a user:

```php
$crawler = new Client();

$articles_response = $crawler->get('https://dev.to/api/articles?username=erikaheidi');

if ($articles_response['code'] !== 200) {
    $app->getPrinter->error('Error while contacting the dev.to API.');
    return 1;
}

$articles = json_decode($articles_response['body'], true);
print_r($articles);
```

The following single-command Minicli application will fetch a user's latest stats from DEV.to using Curly (this requires `minicli/minicli`):

```php
#!/usr/bin/env php
<?php
if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use Minicli\App;
use Minicli\Exception\CommandNotFoundException;
use Minicli\Curly\Client;

$app = new App([
    'debug' => true,
    'theme' => '\Unicorn'
]);

$app->registerCommand('devto', function () use ($app) {
    $app->getPrinter()->display('Fetching from DEV...');
    $crawler = new Client();

    $articles_response = $crawler->get('https://dev.to/api/articles?username=DEVUSERNAME');

    if ($articles_response['code'] !== 200) {
        $app->getPrinter->error('Error while contacting the dev.to API.');
        return 1;
    }

    $articles = json_decode($articles_response['body'], true);
    $table[] = ['Title', 'Reactions'];
    foreach($articles as $article) {
        $table[] = [$article['title'], $article['positive_reactions_count']];
    }
    $app->getPrinter()->printTable($table);
    return 0;
});

try {
    $app->runCommand($argv);
} catch (CommandNotFoundException $notFoundException) {
    $app->getPrinter()->error("Command Not Found.");
    return 1;
}

return 0;
```
