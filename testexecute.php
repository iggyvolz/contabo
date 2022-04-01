<?php

use iggyvolz\contabo\Client;
use Nyholm\Psr7\Factory\Psr17Factory;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/secrets.php"; // defines CLIENT_ID, CLIENT_SECRET, API_USERNAME, API_PASSWORD
$psr17Factory = new Psr17Factory();
$client = Client::get(
    CLIENT_ID,
    CLIENT_SECRET,
    API_USERNAME,
    API_PASSWORD,
    new \GuzzleHttp\Client(),
    $psr17Factory,
    $psr17Factory
);
/** @var \iggyvolz\contabo\Instance $instance */
$instance = iterator_to_array($client->listInstances())[0];
$instance->reinstall(\Ramsey\Uuid\Uuid::fromString("66abf39a-ba8b-425e-a385-8eb347ceac10"), userData:
<<<EOT
#!/bin/bash
touch /foo
EOT);