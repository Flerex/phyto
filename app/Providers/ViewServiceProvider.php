<?php

namespace App\Providers;

use App\Domain\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->sortableLinks();
        $this->panelSidebarProjects();
        $this->taggerNotificationsInProject();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }


    /**
     * For usability reasons, we automatically pass the sorting variable from the parent caller view
     * to the sortable-links.
     */
    private function sortableLinks()
    {
        ViewFacade::composer('partials.sortable-link', function (View $view) {
            $currentUrl = URL::full();
            $viewData = $view->gatherData();

            $sortBy = Request::get('sortBy');
            $order = Request::get('order') !== 'asc' ? 'asc' : 'desc';

            $currentQuery = parse_url($currentUrl, PHP_URL_QUERY);

            // We assume an array with the current URL's query parameters.
            $query = [];
            if ($currentUrl) {
                parse_str($currentQuery, $query);
            }

            // We override (or set if they don't exist) the sortBy and order parameters..
            $query['sortBy'] = $viewData['attr'];
            $query['order'] = $order;


            // Turn query back into an URL friendly string
            $query = http_build_query($query);

            // Replace it back in the original URL
            $route = $currentQuery ? str_replace($currentQuery, $query, $currentUrl) : $currentUrl.'?'.$query;

            $view->with(compact('route', 'sortBy', 'order'));
        });
    }

    /**
     * Show a list of managed projects in the Panel's sidebar.
     */
    private function panelSidebarProjects()
    {
        ViewFacade::composer('panel.master', function (View $view) {

            $user = Auth::user();
            $projects = $user->managedProjects;


            /*
             *  We also pass to the view a variable to check if we're in the project management page.
             *
             * This is because we have to consider being in one of the managed projects as an exceptions,
             * as those will have their own link in the sidebar and will be marked as active there.
             */

            $notInAnyOfTheManagedProjects = $projects->every(function (Project $project) {

                $relativeRoute = route('panel.projects.show', compact('project'), false);
                return !Request::is(trim($relativeRoute, '/').'*');
            });

            $projectManagementIsActive = Str::startsWith(Route::currentRouteName(), 'panel.projects.')
                && $notInAnyOfTheManagedProjects;

            $view->with(compact('projects', 'projectManagementIsActive'));
        });
    }

    /**
     * Make the unfinished assignments count available to the project views.
     */
    private function taggerNotificationsInProject()
    {
        ViewFacade::composer('projects.partials.layout', function (View $view) {

            $request = $this->app->request;

            $project = $request->route('project');


            // We assume only logged in users can access a project.
            $unfinishedAssignments = Auth::user()
                ->unfinishedAssignments()
                ->where('project_id', $project->getKey())->count();

            $view->with(compact('unfinishedAssignments'));
        });

    }
}
