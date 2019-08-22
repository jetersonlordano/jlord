<?php

require '../../_app/Client.inc.php';

if (!isset($_SESSION['admin' . SESSIONUSERID])) {die('Accesso restrito!');}
if ($_SESSION['admin' . SESSIONUSERID]['user_accesslevel'] < 6) {die('Accesso restrito!');}

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);
$usersData = $obj['usersdata'] ?? null;

$FIX = TBONLINE[1];
$fields = $usersData ? '*' : "count({$FIX}id) as total";
$terms = "WHERE {$FIX}end >= :now";
$values = ['now' => date('Y-m-d H:i:s')];

usleep(50000);

$conn = new Conn();
$conn->select($fields, TBONLINE[0], $terms, $values);
$conn->exec();
$result = $conn->fetchAll();
$result = $usersData ? $result : $result[0]['total'];

switch (strlen($result)) {

    case 0:
        $result = '0000' . $result;
        break;
    case 1:
        $result = '000' . $result;
        break;
    case 2:
        $result = '00' . $result;
        break;
    case 3:
        $result = '0' . $result;
        break;
    default:
        $result = $result;
        break;
}

$fnListUsers = $usersData ? 'uploadListUsersOn' : 'showUsersOnline';
$callback = [
    'action' => 'function',
    'fn' => $fnListUsers,
    'data' => $result,
];
echo json_encode($callback);
