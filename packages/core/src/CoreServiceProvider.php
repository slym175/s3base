<?php

namespace S3base\Core;

use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\File;
use S3base\Core\Classes\AdminMenu;
use S3base\Core\Classes\Hook;
use S3base\Core\Classes\ModelType;
use S3base\Core\Http\Middleware\LanguageMiddleware;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class CoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('s3base/core')
            ->hasConfigFile()
            ->hasRoutes(['web'])
            ->hasViews('s3base@core')
            ->hasAssets()
            ->hasTranslations();
    }

    public function packageRegistered()
    {
        $this->app->singleton('hook', function ($app) {
            return new Hook();
        });

        $this->app->singleton('model_type', function ($app) {
            return new ModelType();
        });

        $this->app->singleton('admin_menu', function ($app) {
            return new AdminMenu();
        });

        $helpers = File::glob(__DIR__.'/../helpers/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    public function bootingPackage()
    {
        $kernel = app('Illuminate\Contracts\Http\Kernel');
        $kernel->prependMiddleware(AuthenticateSession::class);
        $kernel->prependMiddleware(StartSession::class);
        $kernel->pushMiddleware(LanguageMiddleware::class);
    }
}
