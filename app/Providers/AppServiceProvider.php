<?php

namespace App\Providers;

use App\Services\CatalogService;
use App\Services\CatalogServiceImpl;
use App\Services\ProjectService;
use App\Services\ProjectServiceImpl;
use App\Services\UserService;
use App\Services\UserServiceImpl;
use App\Utils\FileUtils;
use App\Utils\FileUtilsImpl;
use App\Utils\PackageUtils;
use App\Utils\PackageUtilsImpl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set the default view for the autogenerated pagination
        Paginator::defaultView('pagination::default');
        Paginator::defaultSimpleView('pagination::simple-default');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // Bind implementation to interface in order for the kernel's IoC to work
        $this->app->bind(UserService::class, UserServiceImpl::class);
        $this->app->bind(CatalogService::class, CatalogServiceImpl::class);
        $this->app->bind(ProjectService::class, ProjectServiceImpl::class);

        $this->app->bind(FileUtils::class, FileUtilsImpl::class);
        $this->app->bind(PackageUtils::class, PackageUtilsImpl::class);
    }
}
