<?php

// Seções com categórios
$SECTIONS = [];
if (POSTS) {$SECTIONS['posts'] = ['Posts', TBPOSTS];}
if (BUSINESS) {$SECTIONS['business'] = ['Empresas', TBBUSINESS];}

$FIX = TBCATS[1];
$terms = "WHERE {$FIX}id = :id LIMIT 1";
$conn = new Conn();
$conn->select("*", TBCATS[0], $terms, ['id' => trim($LINK->index[2])]);
$conn->exec();
$CAT = $conn->fetchAll()[0] ?? null;
if (!$CAT) {FNC::redirect(ADM . '/cats');die;}

// Nome da seção
$CAT['section'] = $SECTIONS[$CAT[$FIX . 'section']][0];

// Form Categoria
echo '<div class="row"><div class="col"><div class="card bg-white radius box-shadow"><div class="card-body"><div class="card-title">CATEGORIA</div></div><div class="card-body bg-light radius"><form class="form-flex radius" id="catForm" name="catForm" action="javascript:void(0);" method="post">';

// Campos ocultos
echo '<input type="hidden" id="cat' . $CAT[$FIX . 'id'] . '" name="id" value="' . $CAT[$FIX . 'id'] . '">';
echo '<input type="hidden" name="section" value="' . $CAT[$FIX . 'section'] . '">';

// Title
echo Form::Input('text', 'name', 'Título', 'Título da categoria', null, $CAT[$FIX . 'title'], true, 45, 'autocomplete="off"');

// Description
echo Form::Textarea('description', 'Descrição', 'Descrição da categoria', null, $CAT[$FIX . 'description'], true, 255, 'rows="2"');

// Icon
echo Form::Input('text', 'icon', 'Icone', 'Icone da categoria', null, $CAT[$FIX . 'icon'], false, 45);

// Section
echo Form::Input('text', 'section', 'Seção', 'Seção', null, $CAT['section'], true, 45, 'disabled readonly');

// Input($type, $name, $label, $title, $width = null, $value = null, $required = true, $maxlength = null, $add = null, $icon = null)

// Textarea($name, $label, $title, $width = null, $value = null, $required = true, $maxlength = null, $add = null)

// Save
echo Form::Save('Salvar', 'loaderFormCat', 'catForm', true);

echo '</form></div></div></div></div>';
echo "<script async>submitForm({file: 'async/cats/update.php', loader: 'loaderFormCat'}, 'catForm');</script>";
