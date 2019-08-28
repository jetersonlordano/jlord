<?php


$FIX = TBNAV[1];
$terms = "WHERE {$FIX}id = :id LIMIT 1";
$conn = new Conn();
$conn->select("*", TBNAV[0], $terms, ['id' => trim($LINK->index[2])]);
$conn->exec();
$NAV = $conn->fetchAll()[0] ?? null;
if (!$NAV) {FNC::redirect(ADM . '/menu');die;}



// Form 
echo '<div class="row"><div class="col"><div class="card bg-white radius box-shadow"><div class="card-body"><div class="card-title">ITEM</div></div><div class="card-body bg-light radius"><form class="form-flex radius" id="menuForm" name="menuForm" action="javascript:void(0);" method="post">';

// Campos ocultos
echo '<input type="hidden" id="nav' . $NAV[$FIX . 'id'] . '" name="id" value="' . $NAV[$FIX . 'id'] . '">';

// Name
echo Form::Input('text', 'name', 'Nome', 'Nome do item', null, $NAV[$FIX . 'name'], true, 45, 'autocomplete="off"');

// Menu
echo Form::Input('text', 'menu', 'Menu', 'Menu de aprensentação', null, $NAV[$FIX . 'menu'], true, 45, 'autocomplete="off"');

// Title
echo Form::Input('text', 'title', 'Título', 'Título SEO', null, $NAV[$FIX . 'title'], true, 80, 'autocomplete="off"');

// URL
echo Form::Input('text', 'url', 'URL', 'URL', null, $NAV[$FIX . 'url'], false, 150, 'autocomplete="off"');

// Blank
$ONOFF = ['1' => 'Ativado', '0' => 'Desativado'];
echo Form::Select('blank', 'Nova Aba', 'Abrir em nova aba', null, $ONOFF, $NAV[$FIX . 'url'] ?? '0', true);

// Order
echo Form::Input('number', 'order', 'Ordem', 'Ordem', null, $NAV[$FIX . 'order'] ?? 0, false);

// Save
echo Form::Save('Salvar', 'loaderFromMenu', 'menuForm', true);

echo '</form></div></div></div></div>';
echo "<script async>submitForm({file: 'async/menu/update.php', loader: 'loaderFromMenu'}, 'menuForm');</script>";
