<?php

$pageID = $LINK->index[2] ?? null;
if (!$pageID) {FNC::redirect(ADM . '/pgs');die;}

// Verfica se é super usuário
$SUPERUSER = $USERACTIVE[TBUSERS[1] . 'accesslevel'] == 10;
if (!PAGES && !$SUPERUSER) {PHPNOTIFY('Você não tem permissão para editar páginas');}

// Recupera os dados página
$coverName = '';
$FIX = TBPAGES[1];
$terms = "WHERE {$FIX}id = :id LIMIT 1";
$conn = new Conn();
$conn->select('*', TBPAGES[0], $terms, ['id' => $LINK->index[2]]);
$conn->exec();
$pgs = $conn->fetchAll()[0] ?? null;
if (!$pgs) {FNC::redirect(ADM . '/pgs');die;}

echo '<div class="row"><div class="col col-8"><div class="card bg-white radius box-shadow"><div class="card-body justify-content-between align-items-center"><div class="card-title">PÁGINA</div>';

echo Form::Interrupter('published', 'Públicar página', $pgs[$FIX . 'published'], 'pgForm');

echo '</div>';

echo '<div class="card-body bg-light radius"><form class="form-flex radius" id="pgForm" name="pgForm" action="javascript:void(0);" method="post">';

//Input hidden
echo '<input type="hidden" id="' . $pgs[$FIX . 'id'] . '" name="id" value="' . $pgs[$FIX . 'id'] . '">';

// Nome
echo Form::Input('text', 'name', 'Nome', 'Nome da página', null, $pgs[$FIX . 'name'], true, 45);

// Título
echo Form::Input('text', 'title', 'Título', 'Título da página', null, $pgs[$FIX . 'title'], true, 80);

// Descrição
echo Form::Textarea('description', 'Descrição', 'Título da página', null, $pgs[$FIX . 'description'], true, 160, 'rows="2"');

// Código Google Analytics
echo Form::Input('text', 'analytics', 'ID Google Analytics', 'ID Google Analytics', null, $pgs[$FIX . 'analytics'], false, 60);

// File
echo Form::Input('text', 'file', 'Arquivo', 'Arquivo .php da página', 'input-width-50', $pgs[$FIX . 'file'], false, 60);

$LISTFILES = ['' => 'Nenhum'];
$fields = "{$FIX}name, {$FIX}link";
$terms = "WHERE {$FIX}theme = :theme AND {$FIX}published = :published ORDER BY {$FIX}name ASC";
$values = ['theme' => THEME, 'published' => 1];
$conn->select($fields, TBPAGES[0], $terms, $values);
$conn->exec();
$pages = $conn->fetchAll();

if ($pages) {foreach ($pages as $key) {$LISTFILES[$key[$FIX . 'link']] = $key[$FIX . 'name'];}}

// Section
echo Form::Select('section', 'Seção', 'Seção', 'input-width-50', $LISTFILES, $pgs[$FIX . 'section'], false);

// Single
echo Form::Select('single', 'Página única', 'Single', 'input-width-50', $LISTFILES, $pgs[$FIX . 'single'], false);

// Conteúdo
echo Form::Textarea('content', 'Conteúdo HTML', 'Conteúdo HTML', null, $pgs[$FIX . 'content'], false, null, 'rows="10"');

echo '</form></div></div></div>';

// Informçãoes
echo '<div class="col col-4"><div class="card bg-white radius box-shadow"><div class="card-body"><div class="card-title">INFORMAÇÔES</div></div>';

echo '<div class="card-body justify-content-between align-items-center radius"><div class="form-flex radius">';

echo Form::checkbox('Adicionar ao menu', 'addnav', $pgs[$FIX . 'addnav'], false, null, 'form="pgForm"');

echo Form::checkbox('Cabeçalho padrão', 'header', $pgs[$FIX . 'header'], false, null, 'form="pgForm"');

echo Form::checkbox('Radapé padrão', 'footer', $pgs[$FIX . 'footer'], false, null, 'form="pgForm"');

echo Form::Save('Salvar', 'pgFormLoader', 'pgForm', true);

echo '</div></div></div></div>';

// Fecha row
echo '</div>';

echo '<script async>var postUpdateObj = {file: "async/pgs/update.php", loader: "pgFormLoader"};submitForm(postUpdateObj, "pgForm");</script>';
