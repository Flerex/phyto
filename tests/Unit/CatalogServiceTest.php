<?php

namespace Tests\Unit;

use App\Notifications\ActivateAccount;
use App\Role;
use App\Services\CatalogService;
use App\Services\UserService;
use App\User;
use App\Utils\Roles;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Auth\Events\Registered;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CatalogServiceTest extends TestCase
{


    // Testing values
    private const CATALOG_NAME = "testing catalog";
    private const NODE_EXAMPLES = [
        'domain' => [1, 2, 3],
        'classis' => [1, 2, 3],
        'genus' => [1, 2, 3],
        'species' => [1, 2, 3],
    ];

    /** @var CatalogService $catalogService */
    protected $catalogService;

    /**
     * Initial configuration for this testing class.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Inject UserService from the service container
        $this->catalogService = $this->app->make(CatalogService::class);
    }

    public function test_create_catalog()
    {

        $nodes = collect(self::NODE_EXAMPLES)->map(function ($arr) {
            return collect($arr);
        });

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, $nodes);

        $this->assertEquals(self::CATALOG_NAME, $catalog->name);


        $actualNodes = collect($catalog->nodes())->map(function ($el) {
            return $el->map(function ($node) {
                return $node->getKey();
            });
        });

        // TODO: assert both arrays are equal
        $this->assertSame($nodes, $actualNodes);

    }

}
