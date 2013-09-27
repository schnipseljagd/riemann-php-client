<?php
namespace Riemann;

class DateTimeProvider
{
    public function now()
    {
        return new \DateTime();
    }
}
