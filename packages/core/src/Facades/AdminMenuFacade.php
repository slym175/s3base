<?php

namespace S3base\Core\Facades;

use Illuminate\Support\Facades\Facade;

class AdminMenuFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'admin_menu';
    }
}
