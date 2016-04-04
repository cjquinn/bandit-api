<?php

use Cake\Core\Plugin;
use Cake\Routing\Router;

Router::defaultRouteClass('DashedRoute');

Router::scope('/', function ($routes) {
    /**
     * Logins
     */
    $routes->connect('/activate-account', [
        'controller' => 'Logins',
        'action' => 'activateAccount'
    ]);

    $routes->connect('/login', [
        'controller' => 'Logins',
        'action' => 'login'
    ]);

    $routes->connect('/logout', [
        'controller' => 'Logins',
        'action' => 'logout'
    ]);

    $routes->connect('/request-password-reset', [
        'controller' => 'Logins',
        'action' => 'requestPasswordReset'
    ]);

    $routes->connect('/reset-password', [
        'controller' => 'Logins',
        'action' => 'resetPassword'
    ]);
});

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
        ['only' => ''],
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
});

Plugin::routes();
