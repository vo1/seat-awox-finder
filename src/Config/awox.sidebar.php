<?php

return [
    'awox' => [
        'name' => 'Awox Finder',
        'icon' => 'fas fa-rocket',
        'route_segment' => 'awox',
        'permission' => 'awox.view',
        'entries' => [
            [
                'name' => 'List',
                'icon' => 'fas fa-address-card',
                'route' => 'awox.list',
                'permission' => 'awox.view',
            ],
        ],
    ],
];