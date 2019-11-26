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
         * Challenges
         */
        $routes->resources('Challenges', [
            'only' => [
                'accept',
                'create',
                'delete',
                'index',
                'report',
                'view',
                'withdraw'
            ],
            'map' => [
                'accept' => [
                    'action' => 'accept',
                    'method' => 'PATCH',
                    'path' => ':id/accept'
                ],
                'report' => [
                    'action' => 'report',
                    'method' => 'PATCH',
                    'path' => ':id/report'
                ],
                'withdraw' => [
                    'action' => 'withdraw',
                    'method' => 'PATCH',
                    'path' => ':id/withdraw'
                ]
            ]
        ]);

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
                'unranked',
                'weekly'
            ],
            'map' => [
                'all-time' => ['action' => 'allTime'],
                'unranked' => ['action' => 'unranked'],
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
            'current',
            'current/accept-terms',
            'create',
            'login',
            'request-password-reset',
            'reset-password',
            'update'
        ],
        'map' => [
            'current' => [
                'action' => 'current',
                'method' => 'GET'
            ],
            'current/accept-terms' => [
                'action' => 'acceptTerms',
                'method' => 'PATCH'
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
            'update' => [
                'action' => 'edit',
                'method' => 'PUT',
                'path' => ''
            ]
        ]
    ]);
});

Plugin::routes();
