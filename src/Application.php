<?php

namespace App;

use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    /**
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware($middleware)
    {
        $middleware
            ->add(ErrorHandlerMiddleware::class)
            ->add(AssetMiddleware::class)
            ->add(RoutingMiddleware::class);

        return $middleware;
    }
}
