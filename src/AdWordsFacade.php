<?php

namespace SchulzeFelix\AdWords;

use Illuminate\Support\Facades\Facade;

/**
 * @see \SchulzeFelix\AdWords\AdWords
 */
class AdWordsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-adwords-targeting-idea-service';
    }
}