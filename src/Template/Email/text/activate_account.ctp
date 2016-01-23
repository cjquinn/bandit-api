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

To activate your account, please visit <?= $this->Url->build($url) ?> to choose a password.