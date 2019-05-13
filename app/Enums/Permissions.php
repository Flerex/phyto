<?php

namespace App\Enums;

class Permissions extends Enum
{
    public const PANEL_ACCESS = 'panel.access';
    public const USER_MANAGEMENT = 'user.management';
    public const SPECIES_MANAGEMENT = 'species.management';
    public const CATALOG_MANAGEMENT = 'catalog.management';
    public const PROJECT_MANAGEMENT = 'projects.management';
    public const MANAGE_ALL_PROJECTS = 'projects.management.all';
}
