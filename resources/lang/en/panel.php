<?php

return [
    'panel' => 'Management Panel',
    'managed_projects' => 'Managed projects',

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
    ],

    'projects' => [
        'management' => 'Project management',
        'create' => 'Start new project',
        'no_projects' => 'There are no projects.',
        'create_alert' => 'Project “:name“ has been created.',
        'showing_everything_message' => 'Due to your high-ranking permissions you are currently viewing all existing projects.',
        'add_users' => 'Add members',
        'no_catalogs' => 'There are no catalogs. Create one before being able to start a new project.',
        'cannot_be_a_member_yourself' => 'You cannot be a member of your own project.',
        'go_to_project' => 'Go to project',

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
        ],

        'tasks' => [
            'label' => 'Task|Tasks',
            'empty' => 'There are no tasks created.',
            'create' => 'New task',
            'automated_create' => 'New automated task',
            'created_alert' => 'Task successfully created.',
            'process_number' => 'Labeling per image',
            'description' => 'Description',
            'description_explained' => 'Something that can uniquely identify this task.',
            'process_explained' => 'Here you can select how many times an image will be worked on. For every labeling you add, another member will work in the image. This can be useful to reduce the margin error.',
            'process_max' => 'There must be at least the same number of members as the the number of processes (:value).',
            'must_be_members' => 'Members must belong to the current project.',
            'compatible_same_sample' => 'Compatible tasks must be from the same sample.',
            'compatibility' => 'Backwards compatibility',
            'compatibility_explained' => 'Backwards compatibility with previous tasks ensures that members will not be assigned to the same image if they were already assigned in compatible tasks.',
            'not_enough_members_for_assignments' => 'There are not enough members for this amount of labeling per image. Please take into account that if you are setting compatibility with previous tasks, previous assignments will be taken into account. Please reduce the number of labeling per image or increase the number of members.',
        ],

        'processes' => [
            'empty' => 'There are no processes here.',
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
