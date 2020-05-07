<?php

namespace App\Domain\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static CatalogStatus EDITING()
 * @method static CatalogStatus SEALED()
 * @method static CatalogStatus OBSOLETE()
 */
final class CatalogStatus extends Enum
{
    private const EDITING = 'editing';
    private const SEALED = 'sealed';
    private const OBSOLETE = 'obsolete';
}
