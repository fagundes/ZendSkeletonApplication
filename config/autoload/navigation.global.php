<?php

return [
    'navigation' => [
        // main menu
        'default'   => [
            [
                'label'    => 'Crud',
                'route'    => 'admin/page',
                'resource' => 'route/admin/page',
                'icon'     => 'fa fa-archive fa-fw',
                'pages'    => [
                    'list'   => [
                        'label'    => 'Crud Read',
                        'route'    => 'admin/page',
                        'resource' => 'route/admin/page',
                    ],
                    'create' => [
                        'label'    => 'Crud Create',
                        'route'    => 'admin/page',
                        'action'   => 'add',
                        'resource' => 'route/admin/page',
                    ],
                ],
            ],
        ],
        'menu-topright' => [
            'user' => [
                'label'    => 'Logout',
                'route'    => 'zfcuser/logout',
                'resource' => 'route/zfcuser/logout',
            ],
        ],
    ],
];
