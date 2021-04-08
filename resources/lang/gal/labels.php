<?php

return [
    'id' => 'ID',
    'name' => 'Nome',
    'created_at' => 'Creado no',
    'description' => 'Descrición',
    'files' => 'Arquivos',
    'status' => 'Estado',

    'user' => [
        'users' => 'Usuario|Usuarios',
        'name' => 'Nome',
        'email' => 'Email',
        'role' => 'Rol',
    ],

    'species' => [
        'domain' => 'Dominio',
        'classis' => 'Clase',
        'genus' => 'Xénero',
    ],

    'catalog' => [
        'species' => 'Especies',
        'status_label' => 'Estado',
        'status' => [
            'editing' => 'En edición',
            'sealed' => 'Selado',
            'obsolete' => 'Obsoleto',
        ],
    ],

    'projects' => [
        'projects' => 'Proxecto|Proxectos',
        'catalogs' => 'Catálogos',
        'manager' => 'Xestor',
        'members' => 'Membro|Membros',
        'samples' => 'Mostras',
    ],

    'samples' => [
        'sample' => 'Mostra',
        'taken_on' => 'Tomada o',
    ],

    'image' => [
        'images' => 'Imaxe|Imaxes',
    ],

    'task' => [
        'automated' => 'Tarefa automatizada',
        'manual' => 'Tarefa manual',
        'progress' => 'Progreso',
        'finished' => 'Rematada',
        'pending' => 'Pendente',
        'processes' => 'Proceso|Procesos',
        'unfinished_assignments' => 'Tarefa pendente|Tarefas pendentes',
        'assignments' => 'Tarefas',
        'assignees' => 'Membros asignados',
        'services' => 'Servizo|Servizos',
    ],
];
