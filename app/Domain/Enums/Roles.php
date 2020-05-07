<?php

namespace App\Domain\Enums;

use MyCLabs\Enum\Enum;


/**
 * @method static Roles ADMIN()
 * @method static Roles SUPERVISOR()
 * @method static Roles MANAGER()
 * @method static Roles TAGGER()
 */
final class Roles extends Enum
{
    private const ADMIN = 'admin';
    private const SUPERVISOR = 'supervisor';
    private const MANAGER = 'manager';
    private const TAGGER = 'tagger';
}
