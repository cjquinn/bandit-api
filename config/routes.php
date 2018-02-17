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
                    'index',
                    'update',
                    'view'
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
                ':id/toggle-active',
                'view'
            ],
            'map' => [
                ':id/toggle-active' => [
                    'action' => 'toggleActive',
                    'method' => 'PATCH'
                ]
            ]
        ]);
    });

    /**
     * Users
     */
    $routes->resources('Users', [
        'only' => [
            'activate-account',
            'current-user',
            'login',
            'request-password-reset',
            'reset-password',
            'upload-avatar',
            'update-settings'
        ],
        'map' => [
            'activate-account' => [
                'action' => 'activateAccount',
                'method' => ['GET', 'PATCH']
            ],
            'current-user' => [
                'action' => 'currentUser',
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
            'upload-avatar' => [
                'action' => 'uploadAvatar',
                'method' => 'PATCH'
            ],
            'update-settings' => [
                'action' => 'edit',
                'method' => 'PUT'
            ]
        ]
    ]);
});

Plugin::routes();
