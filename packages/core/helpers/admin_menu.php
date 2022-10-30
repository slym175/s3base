<?php

if (!function_exists('get_admin_menu')) {
    function get_admin_menu()
    {
        return app('admin_menu')->getAll();
    }
}

if(!function_exists('register_menu_item')) {
    function register_menu_item($options) {
        return app('admin_menu')->registerItem($options);
    }
}
