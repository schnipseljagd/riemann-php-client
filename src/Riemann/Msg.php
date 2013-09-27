<?php
namespace Riemann;

use DrSlump\Protobuf\AnnotatedMessage;

class Msg extends AnnotatedMessage
{
    /** @protobuf(tag=2, type=bool, optional) */
    public $ok;

    /** @protobuf(tag=3, type=string, optional) */
    public $error;

    /** @protobuf(tag=4, type=message, reference=Riemann\State, repeated) */
    public $states;

    /** @protobuf(tag=5, type=message, reference=Riemann\Query, optional) */
    public $query;

    /** @protobuf(tag=6, type=message, reference=Riemann\Event, repeated) */
    public $events;
}
