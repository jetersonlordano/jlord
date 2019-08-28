<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

// Diretório _app
$pathapp = str_replace('/', DS, '../../../_app');
if (!file_exists($pathapp)) {ERROR();die;}

// Consulta configurações no banco
$FIX = TBCONFIG[1];
$terms = "WHERE {$FIX}file = :file";
$values = ['file' => 'client'];
$conn = new Conn();
$conn->select('*', TBCONFIG[0], $terms, $values);
$conn->exec();
$configs = $conn->fetchAll();
if (!$configs) {ERROR();die;}

// Consulta redes sociais no banco
$FIXN = TBNET[1];
$terms = "WHERE {$FIXN}perfil != :perfil";
$values = ['perfil' => ''];
$conn->select('*', TBNET[0], $terms, $values);
$conn->exec();
$networks = $conn->fetchAll();

// Consulta páginas para gerar menu de nevegação automático
$FIXP = TBNAV[1];
$terms = "ORDER BY {$FIXP}order ASC";
$conn->select('*', TBNAV[0], $terms);
$conn->exec();
$nav = $conn->fetchAll();

$settings = "<?php\n\r/**\n * Configurações do site \n * @author Jeterson Lordano ";
$settings .= "<jetersonlordano@gmail.com> \n */\n\r";
$settings .= "// Configurações do servidor \nrequire 'Config.inc.php';\n";

// Comentário
$comment = '';

/**
 * Seta as configurações da tabela config
 */
foreach ($configs as $key => $vlr) {

    $fnc = trim($vlr[$FIX . 'fnc']);
    $type = trim($vlr[$FIX . 'type']);
    $name = $fnc != 'define' ? null : "'" . trim($vlr[$FIX . 'name']) . "', ";
    $value = null;

    switch ($type) {

        case 'string':
            $value = "'" . trim($vlr[$FIX . 'value']) . "');";
            break;

        default:
            $value = trim($vlr[$FIX . 'value']) . ");";
            break;
    }

    if ($vlr[$FIX . 'comment'] != $comment) {
        $comment = $vlr[$FIX . 'comment'];
        $settings .= "\n" . '// ' . $vlr[$FIX . 'comment'] . "\n";
    }
    $settings .= $fnc . "(" . $name . $value . "\n";

}

/**
 * Gera array de redes sociais
 */
$settings .= "\n// Redes sociais\ndefine('SOCIALNETWORKS', [\n";
if ($networks) {foreach ($networks as $key => $vlr) {
    $name = trim($vlr[$FIXN . 'name']);
    $base = trim($vlr[$FIXN . 'base']);
    $perfil = trim($vlr[$FIXN . 'perfil']);
    $icon = trim($vlr[$FIXN . 'icon']);
    $settings .= "    '{$name}' => ['{$base}', '{$perfil}', '{$icon}'],\n";
}}
$settings .= "]);\n";

/**
 * Gera array do menu de navegação
 */

$settings .= "\n// Menu principal\ndefine('NAVIGATION', [\n";

if ($nav) {
    foreach ($nav as $key => $vlr) {
        $settings .= "    '{$vlr[$FIXP . 'name']}' => ['{$vlr[$FIXP . 'menu']}', '{$vlr[$FIXP . 'title']}', '{$vlr[$FIXP . 'url']}', {$vlr[$FIXP . 'blank']}],\n";
    }
}
$settings .= ']);';

// Cria o arquivo Client.inc.php
file_put_contents($pathapp . DS . 'Client.inc.php', $settings);

$pathCache = str_replace('/', DS, '../../../') . CACHEDIR;
echo FNC::cleanDir($pathCache, true) ? FNC::notify('Configurações aplicadas com sucesso', 'success') : ERROR();
