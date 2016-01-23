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

<p>To reset your password, please click <?= $this->Html->link('here', $url) ?> to choose a new password.</p>