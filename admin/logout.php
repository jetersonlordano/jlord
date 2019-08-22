<?php

/**
 * Faz logout no painel de controle
 * @author Jeterson Lordano 01-07-2018
 */

require '../_app/Client.inc.php';

$token = md5(CMSNAME . 'admin' . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
$time = time();
$values = ['token' => $token, 'time' => $time];
$conn = new Conn();
$conn->delete(TBSESSIONS[0], 'WHERE ses_token = :token XOR ses_expire < :time', $values);

$conn->exec() ? destroy() : destroy();
echo 'Redirecionando...';
function destroy()
{
    session_destroy();
    header("Location:" . ADM);
    exit();
}
