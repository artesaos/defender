<?php

namespace Artesaos\Defender\Facades;

use Illuminate\Support\Facades\Facade;

class Defender extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'defender';
    }
}
