<?php

namespace SchulzeFelix\AdWords\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function developerTokenNotSpecified()
    {
        return new static('There was no developer token specified. You must provide a valid developer token to execute querys on Google AdWords.');
    }
}