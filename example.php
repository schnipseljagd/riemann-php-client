<?php

use Riemann\Client;

require __DIR__ . '/vendor/autoload.php';

$riemannClient = new Client();

$eventBuilder = $riemannClient->getEventBuilder();
$eventBuilder->setService("php stuff");
$eventBuilder->setMetric(mt_rand(0, 99));
$eventBuilder->addTag('histogram');
$eventBuilder->sendEvent();

$eventBuilder = $riemannClient->getEventBuilder();
$eventBuilder->setService("php stuff");
$eventBuilder->setMetric(mt_rand(99, 199));
$eventBuilder->addTag('meter');
$eventBuilder->sendEvent();

$riemannClient->flush();