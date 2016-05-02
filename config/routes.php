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
    $routes->resources(
        'Clubs',
        [
            'only' => [
                'create'
            ]
        ],
        function ($routes) {
            /**
             * Players
             */
            $routes->resources('Players', ['only' => 'create']);

            /**
             * Results
             */
            $routes->resources(
                'Results',
                [
                    'only' => [
                        'create',
                        'delete',
                        'index',
                        'view'
                    ]
                ],
                function ($routes) {
                    /**
                     * Disputes
                     */
                    $routes->resources('Disputes', [
                        'only' => [
                            'create',
                            'delete',
                            'update'
                        ]
                    ]);
                }
            );
        }
    );

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
            '_method' => 'PUT'
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
            '_method' => 'PUT'
        ]);

        $routes->connect('/reset-password', [
            'controller' => 'Logins',
            'action' => 'resetPassword',
            '_method' => 'PUT'
        ]);

        $routes->connect(
            '/activate-account/validate-token',
            [
                'controller' => 'Logins',
                'action' => 'validateToken',
                '_method' => 'GET',
                'parentAction' => 'activateAccount'
            ],
            [
                'pass' => ['parentAction']
            ]
        );

        $routes->connect(
            '/reset-password/validate-token',
            [
                'controller' => 'Logins',
                'action' => 'validateToken',
                '_method' => 'GET',
                'parentAction' => 'resetPassword'
            ],
            [
                'pass' => ['parentAction']
            ]
        );
    });
});

Plugin::routes();
