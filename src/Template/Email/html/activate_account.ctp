<?php

$url = [
    'controller' => 'Logins',
    'action' => 'activateAccount',
    '?' => [
        'token' => $login->token
    ],
    '_full' => true,
    'prefix' => false
];

?>

<p>To activate your account, please click <?= $this->Html->link('here', $url) ?> to choose a password.</p>