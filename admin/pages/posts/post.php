<?php

$POSTID = $LINK->index[2] ?? null;
if (!$POSTID) {FNC::redirect(ADM . '/posts');die;}

// Prefixos
$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];
$FIXA = TBUSERS[1];

// Consulta no banco
$fields = TBPOSTS[0] . ".*, {$FIXC}title, {$FIXA}name";
$join = 'INNER JOIN ' . TBCATS[0] . " on {$FIX}category = {$FIXC}id ";
$join .= 'INNER JOIN ' . TBUSERS[0] . " on {$FIXA}id = {$FIX}author ";
$terms = $join . "WHERE {$FIX}id = :id LIMIT 1";

$conn = new Conn();
$conn->select($fields, TBPOSTS[0], $terms, ['id' => $POSTID]);
$conn->exec();
$POST = $conn->fetchAll();
if (!$POST) {FNC::redirect(ADM . '/posts');die;}
$POST = $POST[0];

// Post Cover
$POSTCover = '../' . PATHPOSTS . $POST[$FIX . 'path'] . '/' . $POST[$FIX . 'cover'];
$POSTCover = Check::Image($POSTCover, IMAGE);

// Nome da cover
$coverName = !empty($POST[$FIX . 'cover']) ? $POST[$FIX . 'cover'] : IMAGE;

echo '<div class="row"><div class="col col-8"><div class="card bg-white radius box-shadow"><div class="card-body justify-content-between align-items-center"><div class="card-title">POST</div>';

// Post publicado
echo Form::Interrupter('published', 'Públicar post', $POST[$FIX . 'published'], 'postForm');

// Fecha card-body
echo '</div>';

echo '<div class="card-body bg-light radius"><form class="form-flex radius" id="postForm" name="postForm" action="javascript:void(0);" method="post">';

// Inputs hiddens
echo '<input type="hidden" name="id" value="' . $POST[$FIX . 'id'] . '"><input type="hidden" id="path" name="path" value="' . $POST[$FIX . 'path'] . '"><input type="hidden" id="lastlink" name="lastlink" value="' . $POST[$FIX . 'link'] . '">';

// Título
echo Form::Input('text', 'title', 'Título', 'Título do post', null, $POST[$FIX . 'title'], true, '220');

// Descrição
echo Form::Textarea('description', 'Descrição', 'Descrição do post', null, $POST[$FIX . 'description'], true, 255, 'row="2"');

// Tags
echo Form::Textarea('tags', 'Tags', 'Tags separadas por vírgula', null, $POST[$FIX . 'tags'], false, 200, 'row="1"');

// Vídeo
echo Form::Input('text', 'video', 'Vídeo', 'URL do Youtube, Vímeo ou MP4', null, base64_decode($POST[$FIX . 'video']), false, 200);

// Conteúdo
echo '<div class="input-field"><label class="label">Conteúdo</label><div class="textarea" id="content"><div id="editor-textarea" class="editor-textarea"></div><textarea class="editable" name="content">' . $POST[$FIX . 'content'] . '</textarea><script src="' . HOME . '/' . WDGT . '/editor/js/medium-editor.js"></script><script async src="' . HOME . '/' . WDGT . '/scripts/editor.js"></script></div></div>';

echo '</form></div></div></div>';

/**
 * Informações adicionais
 */
echo '<div class="col col-4"><div class="card bg-white radius box-shadow"><div class="card-body justify-content-between align-items-center"><div class="card-title">PUBLICAR</div></div>';

// Cover
echo '<div class="card-body bg-light justify-content-between align-items-center"><label class="pointer block width-100" for="postCoverInput" title="Alterar imagem de capa"><img id="postCoverImg" class="img radius" src="' . $POSTCover . '" alt="' . $POST[$FIX . 'cover'] . '"></label></div>';

echo '<div class="card-body bg-light radius"><div class="form-flex radius">';

// input Cover
echo '<input hidden type="file" id="postCoverInput" name="cover[]" title="Capa do post" accept="image/jpg, image/jpeg, image/png" data-id="' . $POST[$FIX . 'id'] . '">';

/**
 * Categorias
 */

$catOpt = [];
$values = ['section' => 'posts'];
$fields = "DISTINCT {$FIXC}id, {$FIXC}title";
$terms = "WHERE {$FIXC}section = :section ORDER BY {$FIXC}title ASC";
$conn = new Conn();
$conn->select($fields, TBCATS[0], $terms, $values);
$conn->exec();
$cats = $conn->fetchAll();
foreach ($cats as $cvalue) {$catOpt[$cvalue[$FIXC . 'id']] = $cvalue[$FIXC . 'title'];}
echo Form::Select('category', 'Categoria', 'Categoria', null, $catOpt, $POST[$FIX . 'category'], true, 'form="postForm"');

/**
 * Autores
 */

$authorsOpt = [];
$values = ['access' => 9];
$fields = "DISTINCT {$FIXA}id, {$FIXA}name";
$terms = "WHERE {$FIXA}accesslevel >= :access ORDER BY {$FIXA}name ASC";
$conn = new Conn();
$conn->select($fields, TBUSERS[0], $terms, $values);
$conn->exec();
$authors = $conn->fetchAll();
foreach ($authors as $caut) {$authorsOpt[$caut[$FIXA . 'id']] = $caut[$FIXA . 'name'];}
echo Form::Select('author', 'Autor', 'Author', null, $authorsOpt, $POST[$FIX . 'author'], true, 'form="postForm"');

// Data
$POSTData = date('Y-m-d', strtotime($POST[$FIX . 'date']));
echo Form::Input('date', 'date', 'Data', 'Data da criação', null, $POSTData, false, null, 'disabled');

// Salvar
echo Form::Save('Salvar', 'postFormLoader', 'postForm', true);

echo '</div></div></div></div>';

// Fecha row
echo '</div>';

echo '<script async src="' . ADM . '/assets/scripts/post.js"></script>';
