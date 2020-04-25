<?php

namespace App\Providers;

use App\BoundingBox;
use App\Catalog;
use App\Enums\Permissions;
use App\Enums\Roles;
use App\Image;
use App\Policies\BoundingBoxPolicy;
use App\Policies\CatalogPolicy;
use App\Policies\ImagePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SamplePolicy;
use App\Project;
use App\Sample;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Catalog::class => CatalogPolicy::class,
        Project::class => ProjectPolicy::class,
        Sample::class => SamplePolicy::class,
        Image::class => ImagePolicy::class,
        BoundingBox::class => BoundingBoxPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
