<?php

require '../../_app/Client.inc.php';

header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);
if(!isset($obj['urlpage'])){die;}

$URL = strip_tags($obj['urlpage']);
$FIX = TBONLINE[1];
$time = time() + USERSTIMEEXPIRE;
$end = date('Y-m-d H:i:s', $time);
$terms = "{$FIX}end = :end, {$FIX}url = :url WHERE {$FIX}id = :id LIMIT 1";
$values = ['end' => $end, 'url' => $URL, 'id' => SESSIONUSERID];
$conn = new Conn();
$conn->update(TBONLINE[0], $terms, $values);
print_r($conn->exec());