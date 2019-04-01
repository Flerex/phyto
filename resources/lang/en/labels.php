<?php

return [
    'id' => 'ID',
    'name' => 'Name',
    'created_at' => 'Created',

    'user' => [
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
    ]
];
