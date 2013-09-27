<?php
namespace Riemann;

use DrSlump\Protobuf\AnnotatedMessage;

class Attribute extends AnnotatedMessage
{
    /** @protobuf(tag=1, type=string, required) */
    public $key;

    /** @protobuf(tag=2, type=string, optional) */
    public $value;
}
