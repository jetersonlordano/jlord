<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {Error();die;}

$FIX = TBPAGES[1];
$terms = "{$FIX}name = :name, {$FIX}title = :title, {$FIX}description = :description, {$FIX}file = :file, {$FIX}link = :link, {$FIX}section = :section, {$FIX}theme = :theme, {$FIX}single = :single, {$FIX}type = :type, {$FIX}content = :content, {$FIX}analytics = :analytics, {$FIX}published = :published, {$FIX}addnav = :addnav, {$FIX}header = :header, {$FIX}footer = :footer, {$FIX}lastupdate = :lastupdate WHERE {$FIX}id = :id LIMIT 1";
$values = [
    'name' => strip_tags(trim($POST['name'])),
    'title' => strip_tags(trim($POST['title'])),
    'description' => strip_tags(trim($POST['description'])),
    'file' => strip_tags(trim($POST['file'])),
    'link' => FNC::convertStr($POST['name']),
    'theme' => THEME,    
    'section' => trim($POST['section']),
    'single' => trim($POST['single']),
    'type' => $POST['type'] ?? 'WebSite',
    'content' => '',
    'analytics' => trim($POST['analytics']),
    'published' => isset($POST['published']) ? '1' : '0',
    'addnav' => isset($POST['addnav']) ? '1' : '0',
    'header' => isset($POST['header']) ? '1' : '0',
    'footer' => isset($POST['footer']) ? '1' : '0',
    'id' => $POST['id'],
    'lastupdate' => date('Y-m-d H:i:s'),
];

$conn = new Conn();
$conn->update(TBPAGES[0], $terms, $values);
echo $conn->exec() ? FNC::notify("Atualizado com sucesso.", 'success') : ERROR();
