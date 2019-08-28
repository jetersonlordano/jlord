<?php

require '../Control.inc.php';
if (!PERMISSION(9)) {die;}

$POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
if (!$POST) {ERROR();die;}

// Update
$FIX = TBNET[1];
$values = [];
$conn = new Conn();

$totalNS = count($POST);
$updateNS = 0;

foreach ($POST as $SNkey => $SNValue) {

     // Conta as atualizações
     $updateNS++;
     // Termos para atualização
     $terms = "{$FIX}perfil = :perfil WHERE {$FIX}name = :name LIMIT 1";
     $values['perfil'] = strip_tags(trim($SNValue));
     $values['name'] = strip_tags(trim($SNkey));
     $conn->update(TBNET[0], $terms, $values);
     if (!$conn->exec()) {ERROR();die;}
     // Notifica quando concluir as atualizações
     if ($updateNS == $totalNS) {echo FNC::notify("Redes sociais atualizadas com sucesso.", 'success');}
}
