<?php

return [
    'panel' => 'Management Panel',

    'users' => [
        'management' => 'User management',
        'create' => 'Create new user',
        'create_label' => 'Create user',
        'joined' => 'Joined',
        'registered' => 'Registered',
        'reset_password' => 'Reset password',
        'reset_password_info' => 'Sends an email to set a new password',
        'reset_password_alert' => 'The user <strong>:username</strong> has been sent a password reset email.',
    ],

    'species' => [
        'management' => 'Species management',
        'hierarchy_selector' => 'Hierarchy',
    ],

    'catalogs' => [
        'management' => 'Catalog management',
        'create' => 'Create new catalog',
        'create_label' => 'Create catalog',
        'create_seal_label' => 'Create & seal',
        'edit_label' => 'Edit catalog',
        'no_catalogs' => 'There are no catalogs.',
        'seal' => 'Seal catalog',
        'sealed_alert' => 'Catalog “:catalog” has been sealed. It will not be editable anymore.',
        'mark_as_obsolete' => 'Mark catalog as obsolete',
        'obsolete_alert' => 'Catalog “:catalog” has been marked as obsolete. It will not be available anymore.',
        'restore' => 'Restore catalog',
        'restore_alert' => 'Catalog “:catalog” has been restored. It will now be available for use.',
        'create_from' => 'Create new catalog from “:catalog”',
        'destroyed_alert' => 'Catalog “:catalog” has been destroyed.',
        'destroy' => 'Destroy catalog',
        'obsolete_warning' => 'Are you sure you want to mark this catalog as obsolete?',
        'seal_warning' => 'Are you sure you want to seal this catalog?',
        'destroy_warning' => 'Are you sure you want to destroy this catalog?',
    ],

    'projects' => [
        'management' => 'Projects management',
        'create' => 'Start new project',
        'no_projects' => 'There are no projects.',
        'create_alert' => 'Project “:name“ has been created.',
        'showing_everything_message' => 'Due to your high-ranking permissions you are currently viewing all existing projects.',
        'add_users' => 'Add members',
        'no_catalogs' => 'There are no catalogs. Create one before being able to start a new project.',

        'samples' => [
            'label' => 'Sample|Samples',
            'create' => 'Add new sample',
            'feedback' => 'Current project is “:project”',
            'no_samples' => 'No samples',
        ],

        'images' => [
            'label' => 'Image|Images',
            'normalizing_in_progress' => 'There are images currently being processed. Please come back later.',
        ],

        'members' => [
            'label' => 'Member|Members',
            'empty' => 'There are no members in this project.',
            'disable' => 'Disable member',
            'enable' => 'Enable member',
            'added_on' => 'Added on',
        ]
    ],

    'label' => [
        'users' => 'Users',
        'catalogs_species' => 'Catalogs & Species',
        'species' => 'Species',
        'catalogs' => 'Catalogs',
        'projects' => 'Projects',
    ],
];
