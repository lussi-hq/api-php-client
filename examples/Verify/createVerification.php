<?php
require_once './../../vendor/autoload.php';

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Lussi\Client;

$cache = new FilesystemAdapter();
$client = new Client($cache, 'YOUR-CLIENT-ID', 'YOUR-CLIENT-SECRET', 'http://localhost');

$response = $client->verify
    ->services('0195bebd-57f8-754b-afe1-3ccb0a06686c')
    ->createVerification(
        '+243899999999',
        'CD',
        'sms'
    );

dd($response->toArray());
