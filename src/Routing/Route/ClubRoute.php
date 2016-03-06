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
        
        if (!$this->_isValidSubdomain($params)) {
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
        if (!$this->_isValidSubdomain($context['params'], $context['_host'])) {
            return false;
        }

        return parent::match($url, $context);
    }

    /**
     * @return bool
     */
    private function _isValidSubdomain(array $params, $host = '')
    {
        if (empty($host)) {
            $request = Router::getRequest(true);
            $host = $request->host();
        }

        $parts = explode('.', $host);
        $subdomain = current(array_slice($parts, 0, -2));

        if (!$subdomain && !isset($params['withoutClub'])) {
            return false;
        }

        return true;
    }
}
