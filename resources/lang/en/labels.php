<?php

return [
    'id' => 'ID',
    'name' => 'Name',
    'created_at' => 'Created',
    'description' => 'Description',
    'files' => 'Files',
    'status' => 'Status',

    'user' => [
        'users' => 'User|Users',
        'name' => 'Name',
        'email' => 'Email',
        'role' => 'Role',
    ],

    'species' => [
        'domain' => 'Domain',
        'classis' => 'Class',
        'genus' => 'Genus',
    ],

    'catalog' => [
        'species' => 'Species',
        'status_label' => 'Status',
        'status' => [
            'editing' => 'Editing',
            'sealed' => 'Sealed',
            'obsolete' => 'Obsolete',
        ],
    ],

    'projects' => [
        'catalogs' => 'Catalogs',
        'manager' => 'Manager',
        'members' => 'Member|Members',
        'samples' => 'Samples',
    ],

    'samples' => [
        'sample' => 'Sample',
        'taken_on' => 'Taken on',
    ],

    'image' => [
        'images' => 'Image|Images',
    ],

    'task' => [
        'progress' => 'Progress',
        'finished' => 'Finished',
        'pending' => 'Pending',
        'processes' => 'Process|Processes',
        'unfinished_assignments' => 'Pending assignments',
        'assignments' => 'Assignments',
        'assignees' => 'Assignees',
    ],
];
