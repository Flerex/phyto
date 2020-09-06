<?php

namespace App\Providers;

use App\Domain\Services\CatalogService;
use App\Domain\Services\CatalogServiceImpl;
use App\Domain\Services\ProjectService;
use App\Domain\Services\ProjectServiceImpl;
use App\Domain\Services\SampleService;
use App\Domain\Services\SampleServiceImpl;
use App\Domain\Services\TaskService;
use App\Domain\Services\TaskServiceImpl;
use App\Domain\Services\TaxonomyService;
use App\Domain\Services\TaxonomyServiceImpl;
use App\Domain\Services\UserService;
use App\Domain\Services\UserServiceImpl;
use App\Domain\Services\Utils\AssignmentManager;
use App\Domain\Services\Utils\AssignmentManagerImpl;
use App\Utils\FileUtils;
use App\Utils\FileUtilsImpl;
use App\Utils\PackageUtils;
use App\Utils\PackageUtilsImpl;
use Carbon\Carbon;
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
        setlocale(LC_ALL, config('app.locale_iso'), $this->app->getLocale(), config('app.fallback_locale'));
        Carbon::setLocale($this->app->getLocale());

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
        $this->app->bind(SampleService::class, SampleServiceImpl::class);
        $this->app->bind(ProjectService::class, ProjectServiceImpl::class);
        $this->app->bind(TaskService::class, TaskServiceImpl::class);
        $this->app->bind(TaxonomyService::class, TaxonomyServiceImpl::class);

        $this->app->bind(FileUtils::class, FileUtilsImpl::class);
        $this->app->bind(PackageUtils::class, PackageUtilsImpl::class);

        $this->app->bind(AssignmentManager::class, AssignmentManagerImpl::class);
    }
}
