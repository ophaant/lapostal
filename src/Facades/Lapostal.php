<?php

namespace Ophaant\Lapostal\Facades;

use Illuminate\Support\Facades\Facade;

class Lapostal extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lapostal';
    }
}
