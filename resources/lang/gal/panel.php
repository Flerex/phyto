<?php

return [
    'panel' => 'Panel de Xestión',
    'managed_projects' => 'Proxectos Xestionados',

    'users' => [
        'management' => 'Xestión',
        'create' => 'Crear novo usuario',
        'create_label' => 'Crear usuario',
        'joined' => 'Entrou o',
        'registered' => 'Rexistrado',
        'user' => 'Usuario',
        'reset_password' => 'Reiniciar contrasinal',
        'reset_password_info' => 'Envia un email ao usuario para reiniciar o contrasinal',
        'reset_password_alert' => 'Ó usuario <strong>:username</strong> foille enviado un email de reinicio do contrasinal.',
    ],

    'species' => [
        'management' => 'Xerarquía',
    ],

    'catalogs' => [
        'management' => 'Catálogos',
        'create' => 'Crear novo catálogo',
        'create_label' => 'Crear catálogo',
        'create_seal_label' => 'Crear e selar',
        'edit_label' => 'Editar catálogo',
        'no_catalogs' => 'Non hai catálogos.',
        'seal' => 'Selar catálogo',
        'sealed_alert' => 'O catálogo “:catalog” foi selado. Non poderá ser editado de aquí en diante.',
        'mark_as_obsolete' => 'Marcar catálogo como obsoleto',
        'obsolete_alert' => 'O catálogo “:catalog” foi marcado como obsoleto. Xa non estará dispoñible de aquí en diante.',
        'restore' => 'Restaurar catálogo',
        'restore_alert' => 'O catálogo “:catalog” foi restaurado. A partir de agora estará dispoñible para o seu uso.',
        'create_from' => 'Crear novo catálogo a partir de “:catalog”',
        'destroyed_alert' => 'O catálogo  “:catalog” foi eliminado.',
        'destroy' => 'Eliminar catálogo',
    ],

    'projects' => [
        'management' => 'Xestión',
        'create' => 'Comezar novo proxecto',
        'no_projects' => 'Non hai proxectos.',
        'create_alert' => 'O proxecto “:name“ foi creado.',
        'showing_everything_message' => 'Debido ós teus altos permisos estas vendo todos os proxectos existentes.',
        'add_users' => 'Engadir membros',
        'no_catalogs' => 'Non hai catálogos. Crea un antes de comezar un novo proxecto.',
        'cannot_be_a_member_yourself' => 'Non podes ser un membro do teu propio proxecto.',
        'go_to_project' => 'Ir ó proxecto',

        'samples' => [
            'label' => 'Mostra|Mostras',
            'create' => 'Engadir mostra',
            'feedback' => 'O proxecto actual é “:project”',
            'no_samples' => 'Non hai mostras.',
        ],

        'images' => [
            'label' => 'Imaxe|Imaxes',
            'normalizing_in_progress' => 'Hai imaxes que están sendo procesadas. Volve máis tarde.',
        ],

        'members' => [
            'label' => 'Membro|Membros',
            'empty' => 'Non hai membros neste proxecto.',
            'disable' => 'Deshabilitar membro',
            'enable' => 'Rehabilitar membro',
            'added_on' => 'Engadido o',
        ],

        'tasks' => [
            'label' => 'Tarefa|Tarefas',
            'empty' => 'Non hai tarefas creadas.',
            'create' => 'Nova tarefa',
            'automated_create' => 'Nova tarefa automatizada',
            'created_alert' => 'Tarefa creada satisfactoriamente.',
            'process_number' => 'Etiquetados por imaxe',
            'description' => 'Descrición',
            'description_explained' => 'Algo que poida identificar de maneira única a esta tarefa.',
            'process_explained' => 'Dende aquí podes elexir cantas veces se requerirá traballar por cada imaxe. Esto pode ser útil para obter segundas opinións.',
            'process_max' => 'Debe de haber alomenos o mesmo número de membros que de procesos (:value).',
            'must_be_members' => 'Os membros teñen que pertencer ó proxecto actual.',
            'compatible_same_sample' => 'As tarefas compatibles deben pertencer á mesma mostra.',
            'compatibility' => 'Compatibilidade cara atrás',
            'compatibility_explained' => 'A compatibilidade con anteriores tarefas asegura que un mesmo membro non voltará ser asignado nunha mesma imaxe.',
            'not_enough_members_for_assignments' => 'Non hai suficientes membros para esta cantidade de etiquetados por imaxe. Por favor, ten en conta que se estás engadindo compatibilidade con anteriores tarefas, o traballo asignado destas tamén será tido en conda. Reduce o número de etiquetados por imaxe ou incrementa o número de membros a asignar.',
        ],

        'processes' => [
            'empty' => 'Non hai procesos aquí.',
        ]
    ],


    'label' => [
        'users' => 'Usuarios',
        'catalogs_species' => 'Catálogos e especies',
        'species' => 'Especies',
        'catalogs' => 'Catálogos',
        'projects' => 'Proxectos',
    ],
];
