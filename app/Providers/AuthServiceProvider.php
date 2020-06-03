<?php

namespace App\Providers;

use App\Domain\Models\Catalog;
use App\Domain\Models\Image;
use App\Domain\Models\Project;
use App\Domain\Models\Sample;
use App\Domain\Models\TaskAssignment;
use App\Policies\CatalogPolicy;
use App\Policies\ImagePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SamplePolicy;
use App\Policies\TaskAssignmentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        TaskAssignment::class => TaskAssignmentPolicy::class,
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
