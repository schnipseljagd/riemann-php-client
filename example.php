<?php
require __DIR__ . '/vendor/autoload.php';

class State extends \DrSlump\Protobuf\AnnotatedMessage
{
    /** @protobuf(tag=1, type=int64, optional) */
    public $time;

    /** @protobuf(tag=2, type=string, optional) */
    public $state;

    /** @protobuf(tag=3, type=string, optional) */
    public $service;

    /** @protobuf(tag=4, type=string, optional) */
    public $host;

    /** @protobuf(tag=5, type=string, optional) */
    public $description;

    /** @protobuf(tag=6, type=bool, optional) */
    public $once;

    /** @protobuf(tag=7, type=string, repeated) */
    public $tags;

    /** @protobuf(tag=8, type=float, optional) */
    public $ttl;

    /** @protobuf(tag=15, type=float, optional) */
    public $metric_f;
}

class Event extends \DrSlump\Protobuf\AnnotatedMessage
{
    /** @protobuf(tag=1, type=int64, optional) */
    public $time;

    /** @protobuf(tag=2, type=string, optional) */
    public $state;

    /** @protobuf(tag=3, type=string, optional) */
    public $service;

    /** @protobuf(tag=4, type=string, optional) */
    public $host;

    /** @protobuf(tag=5, type=string, optional) */
    public $description;

    /** @protobuf(tag=7, type=string, repeated) */
    public $tags;

    /** @protobuf(tag=8, type=float, optional) */
    public $ttl;

    /** @protobuf(tag=9, type=message, reference=Attribute, repeated) */
    public $attributes;

    /** @protobuf(tag=13, type=sint64, optional) */
    public $metric_sint64;

    /** @protobuf(tag=14, type=double, optional) */
    public $metric_d;

    /** @protobuf(tag=15, type=float, optional) */
    public $metric_f;
}

class Query extends \DrSlump\Protobuf\AnnotatedMessage
{
    /** @protobuf(tag=1, type=string, optional) */
    public $string;
}

class Msg extends \DrSlump\Protobuf\AnnotatedMessage
{
    /** @protobuf(tag=2, type=bool, optional) */
    public $ok;

    /** @protobuf(tag=3, type=string, optional) */
    public $error;

    /** @protobuf(tag=4, type=message, reference=State, repeated) */
    public $states;

    /** @protobuf(tag=5, type=message, reference=Query, optional) */
    public $query;

    /** @protobuf(tag=6, type=message, reference=Event, repeated) */
    public $events;
}

class Attribute extends \DrSlump\Protobuf\AnnotatedMessage
{
    /** @protobuf(tag=1, type=string, required) */
    public $key;

    /** @protobuf(tag=2, type=string, optional) */
    public $value;
}

$message = new Msg();
$message->ok = true;
$message->error = '';
$state = new State();
$state->time = time();
$message->states = array($state);
$query = new Query();
$query->string = '/huhu';
$message->query = $query;
$event = new Event();
$event->time = time();
$message->events = array($event);

$protoMessage = \DrSlump\Protobuf::encode($message);
var_dump($protoMessage);
