<?php

namespace App\Containers\AppSection\User\Providers;

use App\Containers\AppSection\User\Models\User;
use App\Ship\Parents\Providers\MainServiceProvider as ParentMainServiceProvider;

/**
 * Class MainServiceProvider.
 *
 * The Main Service Provider of this container, it will be automatically registered in the framework.
 */
class MainServiceProvider extends ParentMainServiceProvider
{
    /**
     * Container Service Providers.
     */
    public array $serviceProviders = [
        // InternalServiceProviderExample::class,
        // ...
    ];

    /**
     * Container Aliases
     */
    public array $aliases = [

    ];

    /**
     * Register anything in the container.
     */
    public function register(): void
    {
        parent::register();

        // do your binding here..
        // $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    public function boot(): void
    {
        parent::boot();
        register_model_type(User::class, [
            'name' => 'user',
            'singular_name' => 'User',
            'plural_name' => 'Users',
            'list_model' => 'Users list',
            'show_in_admin_menu' => true,
            'parent_admin_menu' => 'mn_general_sections',
            'menu_icon' => 'feather icon-user'
        ]);

        register_menu_item([
            'id' => 'hello',
            'priority' => 1,
            'parent_id' => 'user',
            'name' => 'Hello',
            'icon' => 'feather icon-user',
            'url' => '',
            'permissions' => [],
        ]);
    }
}
