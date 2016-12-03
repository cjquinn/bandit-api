<?php

use Cake\Core\Plugin;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

Router::connect('/', [
    'controller' => 'App',
    'action' => 'display'
], [
    '_name' => 'home'
]);

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
            'controller' => 'Logins',
            'action' => 'activateAccount',
            '_method' => [
                'PATCH',
                'PUT'
            ]
        ]);

        $routes->connect('/login', [
            'controller' => 'Logins',
            'action' => 'login',
            '_method' => 'POST'
        ]);

        $routes->connect('/logout', [
            'controller' => 'Logins',
            'action' => 'logout',
            '_method' => 'GET'
        ]);

        $routes->connect('/request-password-reset', [
            'controller' => 'Logins',
            'action' => 'requestPasswordReset',
            '_method' => [
                'PATCH',
                'PUT'
            ]
        ]);

        $routes->connect('/reset-password', [
            'controller' => 'Logins',
            'action' => 'resetPassword',
            '_method' => [
                'PATCH',
                'PUT'
            ]
        ]);

        $routes->connect('/activate-account/validate-token', [
            'controller' => 'Logins',
            'action' => 'validateToken',
            '_method' => 'GET',
            'parentAction' => 'activateAccount'
        ], [
            'pass' => ['parentAction']
        ]);

        $routes->connect('/reset-password/validate-token', [
            'controller' => 'Logins',
            'action' => 'validateToken',
            '_method' => 'GET',
            'parentAction' => 'resetPassword'
        ], [
            'pass' => ['parentAction']
        ]);
    });
});

Plugin::routes();
