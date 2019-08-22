<?php

require '../../_app/Client.inc.php';

if (!Check::TimeAction()) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {die;}

$user = $POST['email'] ?? null;
$pass = $POST['password'] ?? null;

// Verifica credenciais
if(!$user || !$pass){die('error');}

$access = new Access('admin', ADM . '/',  ADM . '/login.php', 8);
$access->login($user, $pass);

$callback = [
    'action' => 'dialog', 
    'type' => 'info',
    'header' => 'Acesso',
    'message' => $access->log
];

echo $access->check ? json_encode(['action' => 'reload']) : json_encode($callback);