<?php


namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Interface UserService
 * @package App\Services
 */
interface CatalogService
{

    /**
     * Create a catalog with a set of nodes.
     *
     * @param string $name
     * @param Collection $nodes
     * @return mixed
     */
    public function createCatalog(string $name, Collection $nodes);

    /**
     * Updates all data of a Catalog
     *
     * @param int $id
     * @param string $name
     * @param Collection $nodes
     * @return mixed
     */
    public function overrideCatalog(int $catalogId, string $name, Collection $nodes);

    /**
     * Changes the status of a catalog to sealed, so it cannot be opened again
     *
     * @param int $catalogId
     * @return mixed
     */
    public function sealCatalog(int $catalogId);

    /**
     * Changes the status of a catalog to obsolete, so it cannot be used again
     *
     * @param int $catalogId
     * @return mixed
     */
    public function markCatalogAsObsolete(int $catalogId);

    /**
     * Changes the status of a catalog to sealed back from Obsolete.
     *
     * @param int $catalogId
     * @return mixed
     */
    public function restoreCatalog(int $catalogId);
}