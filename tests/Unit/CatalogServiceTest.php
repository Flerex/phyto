<?php

namespace Tests\Unit;

use App\Exceptions\CatalogStatusException;
use App\Notifications\ActivateAccount;
use App\Role;
use App\Services\CatalogService;
use App\Services\UserService;
use App\User;
use App\Utils\CatalogStatus;
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
        'domain' => [1, 2],
        'classis' => [1, 2, 3],
        'genus' => [1, 2, 3],
        'species' => [1, 2, 3],
    ];

    private const NODE_OVERRIDE = [
        'domain' => [1],
        'classis' => [1],
        'genus' => [1],
        'species' => [1],
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

        $nodes = collect(self::NODE_EXAMPLES);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, $nodes);

        $this->assertEquals(self::CATALOG_NAME, $catalog->name);

        foreach ($nodes as $type => $l) {
            $this->assertEqualsCanonicalizing($l, $catalog->$type->pluck('id')->toArray());
        }
    }

    public function test_override_catalog()
    {

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->assertEquals(self::CATALOG_NAME, $catalog->name);


        $nodes = collect(self::NODE_OVERRIDE);

        $newCatalog = $this->catalogService->overrideCatalog($catalog->getKey(), 'newName', $nodes);

        $this->assertEquals('newName', $newCatalog->name);

        foreach ($nodes as $type => $l) {
            $this->assertEqualsCanonicalizing($l, $catalog->$type->pluck('id')->toArray());
        }
    }


    public function test_catalog_override_is_not_possible_if_sealed()
    {
        $this->expectException(CatalogStatusException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->assertEquals(self::CATALOG_NAME, $catalog->name);

        $this->catalogService->sealCatalog($catalog->getKey());


        $nodes = collect(self::NODE_OVERRIDE);

        $this->catalogService->overrideCatalog($catalog->getKey(), 'newName', $nodes);
    }

    public function test_seal_catalog() {

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->assertEquals(CatalogStatus::EDITING, $catalog->status);

        $this->catalogService->sealCatalog($catalog->getKey());


        $this->assertEquals(CatalogStatus::SEALED, $catalog->fresh()->status);

    }

    public function test_catalog_sealing_is_only_possible_if_editing() {

        $this->expectException(CatalogStatusException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->assertEquals(CatalogStatus::EDITING, $catalog->status);

        $this->catalogService->sealCatalog($catalog->getKey());

        $this->assertEquals(CatalogStatus::SEALED, $catalog->fresh()->status);

        $this->catalogService->sealCatalog($catalog->getKey());

    }

}
