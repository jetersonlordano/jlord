<?php

require '../../../_app/Client.inc.php';

// Controle ações por tempo
if (!Check::TimeAction()) {die;}

// Chamar quando existir erro grave no sistema
function ERROR()
{return FNC::notify('Erro interno! Contate o suporte.', 'danger');}

// Atualiza a página se existir sessão
if (!isset($_SESSION['admin' . SESSIONUSERID])) {echo json_encode(['action' => 'reload']);die;}

// Controle de sessão e Dados do usuário logado.
$SESSION = new Session('admin', ADM . '/dashboard.php', ADM . '/');

$USERLOGGEDIN = $SESSION->getData();
$FIRSTUSERNAME = FNC::limitText($USERLOGGEDIN[TBUSERS[1] . 'name'], 1);

// Verifica a permissão para a ação
function PERMISSION(int $level)
{
    $permission = Check::UserAccess($level, 'admin');
    if (!$permission) {
        echo FNC::notify('Você não tem permissão para realizar esta ação.', 'info');
    }
    return $permission;
}

function CHECKCATEGORIE(string $section, bool $newCategorie = false)
{
    $FIX = TBCATS[1];
    $terms = "WHERE {$FIX}section = :section ORDER BY {$FIX}id ASC LIMIT 1";
    $conn = new Conn();
    $conn->select("{$FIX}id", TBCATS[0], $terms, ['section' => $section]);
    $conn->exec();
    $category = $conn->fetchAll();
    if (!$category) {
        return ($newCategorie) ? NEWCATEGORIE($section) : null;
    } else {return $category[0]["{$FIX}id"];}

}

function NEWCATEGORIE(string $section)
{
    $FIX = TBCATS[1];
    $values = [$FIX . 'section' => $section, $FIX . 'link' => uniqid()];
    $conn = new Conn();
    $conn->insert(TBCATS[0], $values);
    return $conn->exec() ? CHECKCATEGORIE($section) : null;
}

function CHECKDATA(string $table, string $prefix, int $id)
{
    $conn = new Conn();
    $conn->select('*', $table, "WHERE {$prefix}id = :id LIMIT 1", ['id' => $id]);
    $conn->exec();
    $result = $conn->fetchAll();
    return $result ? $result[0] : null;
}

function UPLOADCOVER($baseDir, $path, $file, $imgWH, $name, $current)
{
    $image = !empty($_FILES[$file]['tmp_name']) ? $_FILES[$file] : null;
    if ($image) {

        $checkIMG = $imgWH ? CHECKIMAGE($image['tmp_name'], $imgWH['width'], $imgWH['height']) : !0;

        $delIMG = $checkIMG ? DELIMAGE($baseDir . '/' . $path . '/' . $current) : 0;

        $up = new Upload();
        $up->baseDir = $baseDir;
        $up->maxSize = 2;
        $upload = $delIMG ? $up->newFile($image, $path, $name) : 0;
        return $upload ? $up->name . $up->type : 0;

    } else {return !0;}
}

function CHECKIMAGE(string $tmpName, int $defaultW, int $defaultH)
{
    list($width, $height) = getimagesize($tmpName);
    return ($width == $defaultW && $height == $defaultH);
}

function DELIMAGE(string $file)
{
    $file = str_replace([DS . DS, '//', '/'], DS, $file);
    return file_exists($file) ? FNC::delFile($file) : !0;
}

function REPLACEIMAGE($baseDir, $path, $current, $file, $title, $maxSize = 3)
{
    // Deleta imagem atual
    $baseDir = str_replace('/', DS, $baseDir);
    $delImg = DELIMAGE($baseDir . ($path ? $path . DS : null) . $current);
    if (!$delImg) {echo ERROR();die;}

    // Upload da imagem
    $up = new Upload();
    $up->baseDir = $baseDir;
    $up->maxSize = $maxSize;
    $upload = $up->newFile($file, ($path ? $path : '/'), $title);
    if (!$upload) {echo FNC::notify($up->log, 'warning');die;}
    return $up->name . $up->type;
}
