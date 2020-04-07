<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
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
        /**
         * For usability reasons, we automatically pass the sorting variable from the parent caller view
         * to the sortable-links.
         */
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
            $route = $currentQuery ? str_replace($currentQuery, $query, $currentUrl) : $currentUrl . '?' . $query;

            $view->with(compact('route', 'sortBy', 'order'));
        });
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
}
