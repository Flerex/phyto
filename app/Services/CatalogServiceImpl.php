<?php


namespace App\Services;


use App\Catalog;
use App\Utils\CatalogStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CatalogServiceImpl implements CatalogService
{


    public function createCatalog(string $name, Collection $nodes)
    {
        $catalog = Catalog::create([
            'name' => $name,
            'status' => CatalogStatus::EDITING,
        ]);

        foreach ($nodes as $nodeType => $list) {
            $list = collect($list)->map(function ($id) use ($nodeType) {
                return [
                    'node_type' => $nodeType,
                    'node_id' => $id,
                ];
            });

            $catalog->$nodeType()->attach($list);
        }

        return $catalog;
    }

    public function overrideCatalog(int $catalogId, string $name, collection $nodes)
    {

        $catalog = Catalog::findOrFail($catalogId);

        $catalog->name = $name;

        $catalog->save();

        $catalog->empty();

        foreach ($nodes as $nodeType => $list) {
            $list = collect($list)->map(function ($id) use ($nodeType) {
                return [
                    'node_type' => $nodeType,
                    'node_id' => $id,
                ];
            });

            $catalog->$nodeType()->attach($list);
        }

    }

    /**
     * Changes the status of a catalog to sealed, so it cannot be opened again
     *
     * @param int $catalogId
     * @return mixed
     */
    public function sealCatalog(int $catalogId)
    {
        $catalog = Catalog::findOrFail($catalogId);

        if ($catalog->status != CatalogStatus::EDITING) {
            return; // TODO: throw?
        }

        $catalog->status = CatalogStatus::SEALED;
        $catalog->save();
    }

    /**
     * Changes the status of a catalog to obsolete, so it cannot be used again
     *
     * @param int $catalogId
     * @return mixed
     */
    public function markCatalogAsObsolete(int $catalogId)
    {
        $catalog = Catalog::findOrFail($catalogId);

        if ($catalog->status != CatalogStatus::SEALED) {
            return; // TODO: throw?
        }

        $catalog->status = CatalogStatus::OBSOLETE;
        $catalog->save();
    }

    /**
     * Changes the status of a catalog to sealed back from Obsolete.
     *
     * @param int $catalogId
     * @return mixed
     */
    public function restoreCatalog(int $catalogId)
    {
        $catalog = Catalog::findOrFail($catalogId);

        if ($catalog->status != CatalogStatus::OBSOLETE) {
            return; // TODO: throw?
        }

        $catalog->status = CatalogStatus::SEALED;
        $catalog->save();
    }

    /**
     * Completely removes a catalog.
     *
     * @param int $catalogId
     * @return mixed
     */
    public function destroyCatalog(int $catalogId)
    {
        $catalog = Catalog::findOrFail($catalogId);

        if ($catalog->status != CatalogStatus::EDITING) {
            return; // TODO: throw?
        }

        $nodes = $catalog->nodes();

        foreach (array_reverse($nodes) as $nodeType => $list) {

            if ($list->isEmpty()) {
                continue;
            }

            $catalog->$nodeType()->detach($list->map(function ($el) {
                return $el->getKey();
            }));
        }

        $catalog->delete();

    }
}