<?php

if (function_exists('router_url')) {
    function router_url($name, $parameters = [], $absolute = true)
    {
        if (!Route::has($name)) return 'javascript:;';
        return route($name, $parameters, $absolute);
    }
}
