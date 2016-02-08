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

    /**
     * Players
     */
    $routes->connect('/invite-player', [
        'controller' => 'Players',
        'action' => 'add'
    ]);

    $routes->connect('/account', [
        'controller' => 'Players',
        'action' => 'edit'
    ]);

    $routes->extensions(['json']);
    
    /**
     * Results
     */
    $routes->resources('Results', [
        'only' => [
            'create',
            'index',
            'view'
        ]
    ]);
});

Plugin::routes();
