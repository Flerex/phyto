<?php

namespace Tests\Unit;

use App\Catalog;
use App\Enums\CatalogStatus;
use App\Exceptions\CatalogStatusException;
use App\Services\CatalogService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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


        $catalog->status = CatalogStatus::SEALED;
        $catalog->save();

        $this->assertEquals(CatalogStatus::SEALED, $catalog->fresh()->status);

        $this->catalogService->sealCatalog($catalog->getKey());

    }

    public function test_obsolete_catalog() {

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $catalog->status = CatalogStatus::SEALED;
        $catalog->save();

        $this->catalogService->markCatalogAsObsolete($catalog->getKey());

        $this->assertEquals(CatalogStatus::OBSOLETE, $catalog->fresh()->status);

    }

    public function test_only_sealed_catalogs_can_be_marked_as_obsolete() {

        $this->expectException(CatalogStatusException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->catalogService->markCatalogAsObsolete($catalog->getKey());

    }

    public function test_restore_catalog() {

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $catalog->status = CatalogStatus::OBSOLETE;
        $catalog->save();

        $this->catalogService->restoreCatalog($catalog->getKey());

        $this->assertEquals(CatalogStatus::SEALED, $catalog->fresh()->status);

    }

    public function test_only_obsolete_catalogs_can_be_restored() {

        $this->expectException(CatalogStatusException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->catalogService->restoreCatalog($catalog->getKey());

    }

    public function test_destroy_catalog() {

        $this->expectException(ModelNotFoundException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $this->catalogService->destroyCatalog($catalog->getKey());

        Catalog::findOrFail($catalog->getKey());

    }

    public function test_destroy_catalog_without_nodes() {

        $this->expectException(ModelNotFoundException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect());

        $this->catalogService->destroyCatalog($catalog->getKey());

        Catalog::findOrFail($catalog->getKey());
    }

    public function test_only_editing_catalogs_can_be_destroyed() {

        $this->expectException(CatalogStatusException::class);

        $catalog = $this->catalogService->createCatalog(self::CATALOG_NAME, collect(self::NODE_EXAMPLES));

        $catalog->status = CatalogStatus::SEALED;
        $catalog->save();

        $this->catalogService->destroyCatalog($catalog->getKey());
    }

}
