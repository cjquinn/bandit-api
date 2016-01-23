<?php

$url = [
    'controller' => 'Logins',
    'action' => 'resetPassword',
    '?' => [
        'token' => $login->token
    ],
    '_full' => true
];

?>

To reset your password, please visit <?= $this->Url->build($url) ?> to choose a new password.