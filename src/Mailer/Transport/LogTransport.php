<?php
namespace App\Mailer\Transport;

use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Mailer\Transport\DebugTransport;

class LogTransport extends DebugTransport
{
    public function send(Email $email)
    {
        $return = parent::send($email);

        Log::debug($return);

        return $return;
    }
}
