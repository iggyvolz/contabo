<?php

use iggyvolz\contabo\Client;
use iggyvolz\contabo\Image;
use iggyvolz\contabo\Instance;
use iggyvolz\contabo\Tag;
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
$instances = array_map(fn(Instance $i): array => [
    "Instance Name" => $i->name . (empty($i->displayName) ? "" : " ($i->displayName)"),
    "Region" => $i->region,
    "Product ID" => $i->productId,
    "IPv4" => $i->ipConfig->v4->ip,
    "IPv6" => $i->ipConfig->v6->ip,
    "MAC Address" => $i->macAddress,
    "RAM (GB)" => $i->ramMb / 1000,
    "CPU Cores" => $i->cpuCores,
    "OS" => $i->osType,
    "Disk (GB)" => $i->diskMb / 1000,
    "Created" => $i->createdDate->format("Y-m-d H:i:s"),
    "Cancelled" => is_null($i->cancelDate) ? "No" : $i->cancelDate->format("Y-m-d H:i:s"),
    "Status" => $i->status,
    "VHost ID" => $i->vHostId,
], iterator_to_array($client->listInstances()));
$images = array_map(fn(Image $i): array => [
    "ID" => $i->imageId,
    "Name" => $i->name,
    "Description" => $i->description,
    "url" => $i->url,
    "Size (GB)" => $i->sizeMb / 1000,
    "OS" => $i->osType,
    "Version" => $i->version,
    "Format" => $i->format,
    "Status" => $i->status,
    "Custom image" => $i->standardImage ? "No" : "Yes",
    "Created" => $i->creationDate->format("Y-m-d H:i:s"),
    "Last Modified" => $i->lastModifiedDate->format("Y-m-d H:i:s"),
    "Tags" => implode(", ", array_map(fn(Tag $t): string => $t->tagName, $i->tags))
], iterator_to_array($client->listImages()));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Contabo Status Page</title>
    <style>
        table, th, td {
            border: 1px solid;
            border-collapse: collapse;
            text-align: center;
        }
        table {
            width: 100%;
        }
    </style>
</head>
<body>
<table>
    <h1>Instances</h1>
    <table>
        <tr>
            <?php foreach(array_keys($instances[0]) as $key): ?>
                <th><?= htmlspecialchars($key) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach($instances as $instance): ?>
            <tr>
                <?php foreach($instance as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
    <h1>Images</h1>
    <table>
        <tr>
            <?php foreach(array_keys($images[0]) as $key): ?>
                <th><?= htmlspecialchars($key) ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach($images as $image): ?>
            <tr>
                <?php foreach($image as $value): ?>
                    <td><?= htmlspecialchars($value) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </table>
</table>
</body>
</html>
