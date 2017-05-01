<?php

use Cake\Core\Plugin;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

Router::scope('/', function ($routes) {
    /**
     * Templates
     */
    $routes->connect('/templates/:template', [
        'controller' => 'Templates',
        'action' => 'display'
    ], [
        'pass' => ['template']
    ]);

    $routes->connect('/templates/login', [
        'controller' => 'Templates',
        'action' => 'login'
    ]);

    $routes->connect('/templates/onboarding', [
        'controller' => 'Templates',
        'action' => 'onboarding'
    ]);
});

/**
 * RESTful API
 */
Router::prefix('api', function ($routes) {
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
         * ClubsPlayers
         */
        $routes->connect('/players/:player_id', [
            'controller' => 'ClubsPlayers',
            'action' => 'add',
            '_method' => 'POST'
        ], [
            'pass' => [
                'club_id',
                'player_id'
            ]
        ]);

        /**
         * Players
         */
        $routes->resources('Players', ['only' => 'create']);

        /**
         * Results
         */
        $routes->resources('Results', [
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
            $routes->resources('Disputes', ['only' => 'create']);

            $routes->scope('/disputes', function ($routes) {
                $routes->connect('/', [
                    'controller' => 'Disputes',
                    'action' => 'delete',
                    '_method' => 'DELETE'
                ]);

                $routes->connect('/', [
                    'controller' => 'Disputes',
                    'action' => 'edit',
                    '_method' => [
                        'PATCH',
                        'PUT'
                    ]
                ]);
            });
        });
    });

    /**
     * Players
     */
    $routes->resources('Players', ['only' => 'update']);

    /**
     * Auth
     */
    $routes->scope('/auth', function ($routes) {
        /**
         * Logins
         */
        $routes->connect('/activate-account', [
            'controller' => 'Users',
            'action' => 'activateAccount',
            '_method' => [
                'GET',
                'PATCH'
            ]
        ]);

        $routes->connect('/request-password-reset', [
            'controller' => 'Users',
            'action' => 'requestPasswordReset',
            '_method' => 'PATCH'
        ]);

        $routes->connect('/reset-password', [
            'controller' => 'Users',
            'action' => 'resetPassword',
            '_method' => [
                'GET',
                'PATCH'
            ]
        ]);

        $routes->connect('/login', [
            'controller' => 'Users',
            'action' => 'login',
            '_method' => 'POST'
        ]);
    });
});

Plugin::routes();
