<?php

namespace App\Enums;

use Spatie\Permission\Models\Role;

final class CatalogStatus extends Enum
{
    public const EDITING = 'editing';
    public const SEALED = 'sealed';
    public const OBSOLETE = 'obsolete';
}
