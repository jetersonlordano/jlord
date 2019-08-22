<?php

require '../Control.inc.php';

// Nível de permissão
$permission = 9;

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {echo ERROR();die;}

// Campos obrigatórios
$requireds = [];

switch ($POST['form']) {

    case 'resources':
        $permission = 10;
        $requireds = ['POSTS', 'PAGES', 'USERSONLINE', 'ANALYTICS', 'SEARCH', 'SEO'];
        break;

    case 'business':
        $requireds = ['PHONE', 'EMAIL', 'CEP', 'ADDRESS'];
        break;

    case 'apis':
        $requireds = [];
        break;

    default:
        $requireds = ['TITLE', 'DESCRIPTION', 'THEME', 'TIMEZONE'];
        break;
}

// Controle de permissão individual para blocos
if (!PERMISSION($permission)) {die;}

// Remove form do POST para não dar erro no banco
unset($POST['form']);
foreach ($requireds as $key) {
    if (!isset($POST[$key])) {echo ERROR();die;}
    if (empty($POST[$key])) {echo FNC::notify("O campo '{$key}' é obrigatório.", 'warning');die;}
}

/**
 * Verifica se teme existe
 */
if (isset($POST['THEME'])):
    $pathTheme = str_replace('/', DS, '../../../' . PATHTHEMES . DS . $POST['THEME']);
    if (!file_exists($pathTheme)) {echo FNC::notify("Tema ({$POST['THEME']}) não encontrador!", 'warning');die;}
endif;

// Update
$FIX = TBCONFIG[1];
$values = [];
$conn = new Conn();

$total = count($POST);
$update = 0;

foreach ($POST as $key => $vlr) {

    // Conta as atualizações
    $update++;

    // Termos para atualização
    $terms = "{$FIX}value = :value WHERE {$FIX}name = :name LIMIT 1";
    $values['value'] = strip_tags(trim($vlr));
    $values['name'] = $key;

    $conn->update(TBCONFIG[0], $terms, $values);
    if (!$conn->exec()) {echo ERROR();die;}

    // Notifica quando concluir as atualizações
    if ($update == $total) {echo FNC::notify("Atualizado com sucesso.", 'success');}
}
