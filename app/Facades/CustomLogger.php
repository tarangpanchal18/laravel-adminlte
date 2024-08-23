<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CustomLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'customlogger';
    }
}
