<?php

namespace App\Domain\Enums;

use MyCLabs\Enum\Enum;


/**
 * @method static Permissions PANEL_ACCESS()
 * @method static Permissions USER_MANAGEMENT()
 * @method static Permissions SPECIES_MANAGEMENT()
 * @method static Permissions CATALOG_MANAGEMENT()
 * @method static Permissions PROJECT_MANAGEMENT()
 * @method static Permissions MANAGE_ALL_PROJECTS()
 */
class Permissions extends Enum
{
    private const PANEL_ACCESS = 'panel.access';
    private const USER_MANAGEMENT = 'user.management';
    private const SPECIES_MANAGEMENT = 'species.management';
    private const CATALOG_MANAGEMENT = 'catalog.management';
    private const PROJECT_MANAGEMENT = 'projects.management';
    private const MANAGE_ALL_PROJECTS = 'projects.management.all';
}
