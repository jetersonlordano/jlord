<?php

/**
 * Sidebar
 */

$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];

echo '<aside class="posts_sidebar col col-4"><div class="posts_sidebar_search"><form id="postsSearch" name="postsSearch" action="" method="post"> <button type="submit" class="btn"><svg viewBox="0 0 17 17"><path d="M16.533 16.533a1.597 1.597 0 0 1-2.26 0l-2.816-2.818A7.392 7.392 0 0 1 7.45 14.9a7.45 7.45 0 1 1 7.45-7.45 7.4 7.4 0 0 1-1.186 4.007l2.818 2.818a1.596 1.596 0 0 1 0 2.257zM7.45 2.13a5.322 5.322 0 1 0 0 10.642 5.322 5.322 0 0 0 0-10.643z"></path></svg></button> <input type="search" class="radius" name="search" placeholder="O que você procura?"></form></div>';

// Categorias
echo '<div class="posts_sidebar_categories sidebar_box"><h4 class="sidebar_box_title">CATEGORIAS</h4> <nav class="nav_cats hover-main-list-a">';

$fields = "{$FIXC}title, {$FIXC}link, count({$FIX}id) as amount";
$terms = "LEFT JOIN " . TBCATS[0] . " on {$FIXC}id = {$FIX}category";
$terms .= " WHERE {$FIXC}section = :section AND ({$FIX}published = :published AND {$FIX}date <= :now) GROUP BY {$FIX}category ORDER BY {$FIXC}title ASC";
$values = ['section' => 'posts', 'published' => 1, 'now' => date('Y-m-d H:i:s')];
$conn = new Conn();
$conn->select($fields, TBPOSTS[0], $terms, $values);
$conn->exec();
$cats = $conn->fetchAll();

if ($cats) {foreach ($cats as $catKey) {echo "<a href=\"" . HOME . "/blog/{$catKey[$FIXC . 'link']}\">{$catKey[$FIXC . 'title']} <span>({$catKey['amount']})</span></a>";}}

echo '</nav></div>';

// Posts Mais vistos ou sugeridos
$secTitle = $LINK->index[0] == 'post' ? 'Posts sugeridos' : 'Mais vistos';
echo '<div class="posts_sidebar_hints sidebar_box"><h4 class="sidebar_box_title">' . $secTitle . '</h4>';

$fields = "{$FIX}title, {$FIX}link, {$FIX}path, {$FIX}cover";
$terms = "WHERE ({$FIX}published = :published AND {$FIX}date <= :now) ORDER BY {$FIX}views DESC LIMIT 4";
$values = ['published' => 1, 'now' => date('Y-m-d H:i:s')];

// Se for post single mostra os relacionados
if ($LINK->index[0] == 'post') {
    $terms = "WHERE {$FIX}id != :id AND ({$FIX}published = :published AND {$FIX}date <= :now) ";
    $terms .= " ORDER BY RAND() LIMIT 4";
    $values['id'] = $POST[$FIX . 'id'];
}

$conn->select($fields, TBPOSTS[0], $terms, $values);
$conn->exec();
$mostViewed = $conn->fetchAll();
if ($mostViewed) {foreach ($mostViewed as $postKey) {
    $postCover = PATHPOSTS . $postKey[$FIX . 'path'] . '/' . $postKey[$FIX . 'cover'];
    $postKey['IMAGE'] = IMAGE;
    $postKey['cover'] = Check::Image($postCover, IMAGE);
    $postKey['link'] = HOME . '/post/' . $postKey[$FIX . 'link'];
    echo FNC::view($postKey, TPL . 'post_box_sidebar.html');
}}

echo '</div>';

echo '</aside>';
