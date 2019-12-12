<?php

namespace App\Enums;

use Spatie\Permission\Models\Role;

final class Roles extends Enum
{
    public const ADMIN = 'admin';
    public const SUPERVISOR = 'supervisor';
    public const MANAGER = 'manager';
    public const TAGGER = 'tagger';
}
