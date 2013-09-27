<?php
error_reporting(-1);

use Riemann\Client;

require __DIR__ . '/vendor/autoload.php';

$riemannClient = Client::create('localhost', 5555);

$eventBuilder = $riemannClient->getEventBuilder();
$eventBuilder->setService("php stuff");
$eventBuilder->setMetric(mt_rand(0, 99));
$eventBuilder->addTag('histogram');
$eventBuilder->sendEvent();

$eventBuilder = $riemannClient->getEventBuilder();
$eventBuilder->setService("php stuff2");
$eventBuilder->setMetric(mt_rand(99, 199));
$eventBuilder->addTag('meter');
$eventBuilder->sendEvent();

$riemannClient->flush();
