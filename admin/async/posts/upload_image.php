<?php

require '../Control.inc.php';
if (!PERMISSION(8)) {die;}

// Verifica se o diretório path existe ou tenta criar
$basePath = '../../../' . PATHPOSTS;
$basePath = str_replace('/', DS, $basePath);
$checkDir = !file_exists($basePath . $_POST['path']) ? FNC::createDir($basePath, $_POST['path']) : !0;
if (!$checkDir) {echo ERROR();die;}

// Upload da imagem
$up = new Upload();
$up->baseDir = $basePath . $_POST['path'];
$up->maxSize = 4;
$upload = $up->newFile($_FILES['midia'], 'galery');
if (!$upload) {echo FNC::notify($up->log, 'warning');die;}

$src = HOME . '/' . PATHPOSTS . $_POST['path'] . '/galery/' . $up->name . $up->type;
$alt = ucfirst(strtolower(FNC::convertStr($up->name, 'text')));
$img = "<img src=\"{$src}\" alt=\"{$alt}\" title=\"{$alt}\">";

echo json_encode(['action' => 'function', 'fn' => 'insertContent', 'data' => $img]);
