<?php

namespace Tests\Feature;

use App\Services\CatalogService;
use App\User;
use App\Utils\Roles;
use Tests\TestCase;

class PanelCatalogTest extends TestCase
{

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

    private function login_as_supervisor()
    {
        $user = factory(User::class)->create();

        $user->assignRole(Roles::SUPERVISOR);

        $user->save();

        $this->be($user);
    }

    public function test_only_supervisors_and_greater_can_manage_catalogs()
    {

        $this->be(factory(User::class)->create());

        $response = $this->get(route('panel.catalogs.index'));

        $response->assertStatus(403);
    }


    public function test_list_supervisor()
    {

        $this->login_as_supervisor();

        $node = collect([
            'domain' => [1],
        ]);

        $this->catalogService->createCatalog('newCatalog', $node);

        $response = $this->get(route('panel.catalogs.index'));


        $response->assertSee('newCatalog');
        $response->assertSee('Editing');

    }
}
