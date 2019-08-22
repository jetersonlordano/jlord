<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

// JSON
header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$FIX = TBCATS[1];
$conn = new Conn();
$terms = "WHERE {$FIX}id = :id";
$conn->select('*', TBCATS[0], $terms, ['id' => $obj['id']]);
$conn->exec();
$category = $conn->fetchAll();
if (!$category) {echo ERROR();die;}
$category = $category[0];

// Seções com categórios
$SECTIONS = [];
if (POSTS) {$SECTIONS['posts'] = ['Posts', TBPOSTS];}

// Sections config.inc.php
$sect = $SECTIONS[$category['cat_section']];

$FIX = $sect[1][1];
// Verifica se existe post cadastrado na categoria
$conn->select("{$FIX}id", $sect[1][0], "WHERE {$FIX}category = :id", ['id' => $obj['id']]);
$conn->exec();
$result = $conn->fetchAll();
if ($result) {echo FNC::notify("Impossível excluir!, Existem {$sect[0]} dependentes.", 'warning');die;}

// Exclui categoria da base de dados e retira elemento da página
$callback = [
    'action' => 'removed',
    'element' => 'cat' . $obj['id'],
    'message' => "Categoria excluida com sucesso.",
];
$callback = json_encode($callback);
$FIX = TBCATS[1];
$conn->delete(TBCATS[0], "WHERE {$FIX}id = :id", ['id' => $obj['id']]);
echo $conn->exec() ? $callback : ERROR();
