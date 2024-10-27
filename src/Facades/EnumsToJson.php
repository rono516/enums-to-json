<?php

namespace rono516\EnumsToJson\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \rono516\EnumsToJson\EnumsToJson
 */
class EnumsToJson extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \rono516\EnumsToJson\EnumsToJson::class;
    }
}
