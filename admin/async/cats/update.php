<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

$post = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$post) {echo ERROR();die;}

$FIX = TBCATS[1];
$terms = "WHERE {$FIX}section = :section AND {$FIX}title = :title AND {$FIX}id != :id";
$values = ['section' => trim($post['section']), 'title' => trim($post['name']), 'id' => $post['id']];

// Verifica se categoria já existe no banco para criar dados duplicados
$conn = new Conn();
$conn->select("{$FIX}id", TBCATS[0], $terms, $values);
$conn->exec();
$resultCat = $conn->fetchAll();
if ($resultCat) {echo FNC::notify("Esta categoria já existe!", 'warning');die;}

$terms = "{$FIX}section = :section, {$FIX}link = :link, {$FIX}title = :title, {$FIX}description = :description, {$FIX}icon = :icon WHERE {$FIX}id = :id LIMIT 1";
$values = [
    'section' => trim($post['section']),
    'link' => FNC::convertStr($post['name']),
    'title' => trim($post['name']),
    'description' => trim($post['description']),
    'icon' => trim($post['icon']),
    'id' => $post['id'],
];

$conn->update(TBCATS[0], $terms, $values);
echo $conn->exec() ? FNC::notify("Categoria atualizada com sucesso.", 'success') : ERROR();
