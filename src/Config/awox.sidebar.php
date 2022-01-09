<?php

return [
    'awox' => [
        'name' => 'Awox Finder',
        'icon' => 'fas fa-skull',
        'route_segment' => 'awox',
        'permission' => 'awox.read',
        'route' => 'awox.list',
    ],
    'settings'    => [
        'entries' => [
            'awox.settings' => [
                'name' => 'Awox Finder',
                'icon' => 'fas fa-skull',
                'route_segment' => 'configuration',
                'permission' => 'awox.read',
                'route' => 'awox.settings',
            ],
        ]
    ]
];