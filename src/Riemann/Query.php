<?php
namespace Riemann;

use DrSlump\Protobuf\AnnotatedMessage;

class Query extends AnnotatedMessage
{
    /** @protobuf(tag=1, type=string, optional) */
    public $string;
}
