<?php

use Cake\Core\Plugin;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

/**
 * RESTful API
 */
Router::scope('/api', function ($routes) {
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
        $connectOptions = [
            'club_id' => '[0-9]+',
            'player_id' => '[0-9]+',
            'pass' => [
                'club_id',
                'player_id'
            ]
        ];

        $routes->connect('/:player_id', [
            'controller' => 'ClubsPlayers',
            'action' => 'add',
            '_method' => 'POST'
        ], $connectOptions);

        $routes->connect('/:player_id', [
            'controller' => 'ClubsPlayers',
            'action' => 'delete',
            '_method' => 'DELETE'
        ], $connectOptions);

        /**
         * Disputes
         */
        $routes->resources('Disputes', ['only' => 'create']);

        $routes->scope('/disputes', function ($routes) {
            $connectOptions = [
                'player_id' => '[0-9]+',
                'result_id' => '[0-9]+',
                'pass' => [
                    'player_id',
                    'result_id'
                ]
            ];

            $routes->connect('/:player_id/:result_id', [
                'controller' => 'Disputes',
                'action' => 'delete',
                '_method' => 'DELETE'
            ], $connectOptions);

            $routes->connect('/:player_id/:result_id', [
                'controller' => 'Disputes',
                'action' => 'edit',
                '_method' => [
                    'PATCH',
                    'PUT'
                ]
            ], $connectOptions);
        });

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
        ]);
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
