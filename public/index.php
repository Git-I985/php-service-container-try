<?php

declare(strict_types=1);

namespace App;

require "../vendor/autoload.php";

use App\Container;
use App\Format\JSON;
use App\Format\Format;

$container = new Container();

$container->addService('format.json', function () {
    return new JSON();
});

$container->addService('format', function () use ($container) {
    return $container->getService('format.json');
}, Format::class);

$container->loadServices('App\\Service');
$container->loadServices('App\\Controller');

dump($container->getService('App\\Controller\\IndexController')->index());
