<?php

namespace App\Routing\Route;

use Cake\Network\Request;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

class ClubRoute extends DashedRoute
{

    /**
     * @param string $url The URL to parse
     * @return bool|array
     */
    public function parse($url)
    {
        $params = parent::parse($url);
        
        if (!$params) {
            return false;
        }
        
        if ($this->_subdomain() &&
            isset($params['club']) &&
            !$params['club']
        ) {
            return false;
        }

        return $params;
    }

    /**
     * @param array $url The URL array
     * @param array $context The request context
     * @return bool|string
     */
    public function match(array $url, array $context = [])
    {
        if ($this->_subdomain($context['_host'])) {
            if (isset($context['params']['club']) &&
                !$context['params']['club']
            ) {
                return false;
            }
        } else {
            $url['club'] = false;
        }

        return parent::match($url, $context);
    }

    /**
     * @return bool|string
     */
    private function _subdomain($host = '')
    {
        if (empty($host)) {
            $request = Router::getRequest(true);
            $host = $request->host();
        }

        $parts = explode('.', $host);
        $subdomain = current(array_slice($parts, 0, -2));
        return $subdomain ? $subdomain : false;
    }
}
