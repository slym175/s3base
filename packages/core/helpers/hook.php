<?php

if (!function_exists('hook')) {
    function hook()
    {
        return app('hook');
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $arguments = 1) {
        return hook()->addAction($hook, $callback, $priority, $arguments);
    }
}

if (!function_exists('do_action')) {
    function do_action($action, ...$parameters) {
        return hook()->action($action, ...$parameters);
    }
}

if (!function_exists('remove_action')) {
    function remove_action($hook, $callback, $priority = 10) {
        return hook()->removeAction($hook, $callback, $priority);
    }
}

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $arguments = 1) {
        return hook()->addFilter($hook, $callback, $priority, $arguments);
    }
}

if (!function_exists('apply_filter')) {
    function apply_filter($action, $value, ...$parameters) {
        return hook()->filter($action, $value, ...$parameters);
    }
}

if (!function_exists('remove_filter')) {
    function remove_filter($hook, $callback, $priority = 10) {
        return hook()->removeFilter($hook, $callback, $priority);
    }
}
