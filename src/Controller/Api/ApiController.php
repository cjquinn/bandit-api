<?php

namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

class ApiController extends Controller
{

    /**
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Auth', [
            'authenticate' => [
                'all' => [
                    'finder' => 'auth',
                    'userModel' => 'Logins'
                ],
                'ADmad/JwtAuth.Jwt' => [
                    'fields' => ['username' => 'id'],
                    'parameter' => 'token',
                    'queryDatasource' => true
                ],
                'Form' => [
                    'fields' => ['username' => 'email']
                ]
            ],
            'authorize' => 'Controller',
            'loginAction' => false,
            'storage' => 'Memory',
            'unauthorizedRedirect' => false
        ]);
        $this->loadComponent('Cookie');
        $this->loadComponent('RequestHandler');
    }

    /**
     * @return bool
     */
    public function isAuthorized(array $user)
    {
        if (!$this->request->is('ajax') ||
            !$this->request->is('json')
        ) {
            return false;
        }

        if (isset($this->request->params['club_id'])) {
            $playerIsAsignedToClub = TableRegistry::get('Players')->isAssignedToClub(
                $this->Auth->user('player.id'),
                $this->request->params['club_id']
            );

            if (!$playerIsAsignedToClub) {
                return false;
            }
        }

        return true;
    }
}
