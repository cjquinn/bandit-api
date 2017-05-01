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
         * Players
         */
        $routes->resources('Players', [
            'only' => [
                'create',
                'delete'
            ]
        ]);

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
            $routes->resources('Disputes', [
                'only' => [
                    'create',
                    'update',
                    'delete'
                ]
            ]);
        });
    });

    /**
     * Users
     */
    $routes->resources('Users', ['only' => 'update']);

    /**
     * Auth
     */
    $routes->scope('/auth', function ($routes) {
        /**
         * Users
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
