<?php

use Cake\Core\Plugin;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

Router::scope('/', function ($routes) {
    $routes->extensions(['json']);

    /**
     * Clubs
     */
    $routes->resources('Clubs', [
        'only' => [
            'create',
            'index',
            'update',
            'view'
        ]
    ], function ($routes) {
        /**
         * Disputes
         */
        $routes->resources('Disputes', ['only' => 'index']);

        /**
         * Leaderboards
         */
        $routes->resources('Leaderboards', [
            'only' => [
                'all-time',
                'weekly'
            ],
            'map' => [
                'all-time' => ['action' => 'allTime'],
                'weekly' => ['action' => 'weekly']
            ]
        ]);

        /**
         * Matches
         */
        $routes->resources('Matches', [
            'only' => [
                'create',
                'delete',
                'index',
                'view'
            ]
        ], function ($routes) {
            /**
             * Disputes
             */
            $routes->resources('Disputes', [
                'only' => [
                    'create',
                    'delete',
                    'close'
                ],
                'map' => [
                    'close' => [
                        'action' => 'close',
                        'method' => 'PATCH',
                        'path' => ''
                    ],
                    'delete' => [
                        'action' => 'delete',
                        'method' => 'DELETE',
                        'path' => ''
                    ]
                ]
            ]);
        });

        /**
         * Players
         */
        $routes->resources('Players', [
            'only' => [
                'create',
                'index',
                'view'
            ]
        ]);
    });

    /**
     * Users
     */
    $routes->resources('Users', [
        'only' => [
            'activate-account',
            'current',
            'login',
            'request-password-reset',
            'reset-password',
            'update-avatar',
            'update-settings'
        ],
        'map' => [
            'activate-account' => [
                'action' => 'activateAccount',
                'method' => ['GET', 'PATCH']
            ],
            'current' => [
                'action' => 'current',
                'method' => 'GET'
            ],
            'login' => [
                'action' => 'login',
                'method' => 'POST'
            ],
            'request-password-reset' => [
                'action' => 'requestPasswordReset',
                'method' => 'PATCH'
            ],
            'reset-password' => [
                'action' => 'resetPassword',
                'method' => ['GET', 'PATCH']
            ],
            'update-settings' => [
                'action' => 'edit',
                'method' => 'PUT'
            ]
        ]
    ]);
});

Plugin::routes();
