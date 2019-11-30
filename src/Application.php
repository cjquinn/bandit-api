<?php

namespace App;

use App\Routing\Middleware\CorsMiddleware;

use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\RoutingMiddleware;

class Application extends BaseApplication
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap()
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }

        $this->addPlugin('ADmad/JwtAuth');
        $this->addPlugin('Queue');
    }

    /**
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            ->add(new CorsMiddleware())
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))
            ->add(new RoutingMiddleware($this));

        return $middlewareQueue;
    }

    /**
     * @return void
     */
    protected function bootstrapCli()
    {
        try {
            $this->addPlugin('Bake');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        $this->addPlugin('Migrations');
    }
}
