<?php

namespace S3base\Core\Facades;

use Illuminate\Support\Facades\Facade;

class HookFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'hook';
    }
}
