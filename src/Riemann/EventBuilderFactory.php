<?php
namespace Riemann;

class EventBuilderFactory
{
    public function create()
    {
        return new EventBuilder(
            new DateTimeProvider(),
            php_uname('n'),
            array('www')
        );
    }
}
