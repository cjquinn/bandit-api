<?php

use Cake\Core\Plugin;
use Cake\Routing\Router;

Router::defaultRouteClass('ClubRoute');

Router::scope('/', function ($routes) {
    /**
     * Logins
     */
    $routes->connect('/activate-account', [
        'controller' => 'Logins',
        'action' => 'activateAccount',
        'club' => false
    ]);

    $routes->connect('/login', [
        'controller' => 'Logins',
        'action' => 'login',
        'club' => false
    ]);

    $routes->connect('/logout', [
        'controller' => 'Logins',
        'action' => 'logout',
        'club' => false
    ]);

    $routes->connect('/request-password-reset', [
        'controller' => 'Logins',
        'action' => 'requestPasswordReset',
        'club' => false
    ]);

    $routes->connect('/reset-password', [
        'controller' => 'Logins',
        'action' => 'resetPassword',
        'club' => false
    ]);

    /**
     * Players
     */
    $routes->connect('/account', [
        'controller' => 'Players',
        'action' => 'edit'
    ]);

    $routes->connect('/invite-player', [
        'controller' => 'Players',
        'action' => 'add'
    ]);

    $routes->extensions(['json']);
    
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
            $routes->resources('Disputes', [
                'only' => [
                    'create',
                    'delete',
                    'update'
                ]
            ]);
        }
    );
});

Plugin::routes();
