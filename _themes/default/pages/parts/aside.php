<?php

/**
 * Sidebar
 */

$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];


// Categorias
$fields = "{$FIXC}title, {$FIXC}link, count({$FIX}id) as amount";
$terms = "LEFT JOIN " . TBCATS[0] . " on {$FIXC}id = {$FIX}category";
$terms .= " WHERE {$FIXC}section = :section AND ({$FIX}published = :published AND {$FIX}date <= :now) GROUP BY {$FIX}category ORDER BY {$FIXC}title ASC";
$values = ['section' => 'posts', 'published' => 1, 'now' => date('Y-m-d H:i:s')];
$conn = new Conn();
$conn->select($fields, TBPOSTS[0], $terms, $values);
$conn->exec();
$cats = $conn->fetchAll();

// Posts Mais vistos ou sugeridos
$secTitle = $LINK->index[0] == 'post' ? 'Posts sugeridos' : 'Mais vistos';

$fields = "{$FIX}title, {$FIX}link, {$FIX}path, {$FIX}cover";
$terms = "WHERE ({$FIX}published = :published AND {$FIX}date <= :now) ORDER BY {$FIX}views DESC LIMIT 3";
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

//Author
$terms = 'WHERE pfx_main = :main ORDER BY pfx_accesslevel DESC LIMIT 1';
$terms = str_replace('pfx_', TBUSERS[1], $terms);
$fields = 'pfx_avatar, pfx_name, pfx_nickname, pfx_description';
$fields = str_replace('pfx_', TBUSERS[1], $fields);
$conn->select($fields, TBUSERS[0], $terms, ['main' => 1]);
$conn->exec();
$userMain = $conn->fetchAll()[0] ?? null;

//Tags
$terms = 'WHERE pfx_tags IS NOT NULL AND pfx_published = :pub ORDER BY pfx_id DESC LIMIT 30';
$terms = str_replace('pfx_', TBPOSTS[1], $terms);
$conn->select('DISTINCT ' . TBPOSTS[1] . 'tags as tags', TBPOSTS[0], $terms, ['pub' => 1]);
$conn->exec();
$postTags = $conn->fetchAll();
$tags = [];

if ($postTags) {
    foreach ($postTags as $keyTags) {
        $expTags = explode(',', trim($keyTags['tags']));
        $expTagsTotal = count($expTags);
        for ($i = 0; $i < $expTagsTotal; $i++) {
            if (!in_array($expTags[$i], $tags) && !empty($expTags[$i])) {
                array_push($tags, $expTags[$i]);
            }
        }
    }
}

$tags = implode(',', $tags);
?>

<aside id="asideBlog" class="col-12 col-md-5 col-lg-4">

<?php

if ($userMain) {
    $avatarUser = PATHAUTHORS . $userMain[TBUSERS[1] . 'avatar'];
    $userMain['avatar'] = Check::Image($avatarUser, AVATAR);
    $userMain['nickname'] = empty($userMain[TBUSERS[1] . 'nickname']) ? $userMain[TBUSERS[1] . 'name'] : $userMain[TBUSERS[1] . 'nickname'];
    echo FNC::view($userMain, TPL . 'aside_user.html');
}

?>

    <div class="aside_item">
        <div class="sidebar_search">
            <form id="postsSearch" name="postsSearch" action="" method="POST"><button type="submit" class="btn"><svg viewBox="0 0 17 17"><path d="M16.533 16.533a1.597 1.597 0 0 1-2.26 0l-2.816-2.818A7.392 7.392 0 0 1 7.45 14.9a7.45 7.45 0 1 1 7.45-7.45 7.4 7.4 0 0 1-1.186 4.007l2.818 2.818a1.596 1.596 0 0 1 0 2.257zM7.45 2.13a5.322 5.322 0 1 0 0 10.642 5.322 5.322 0 0 0 0-10.643z"></path></svg></button><input type="search" class="radius" name="search" placeholder="O que você procura?" autocomplete="off"></form>
        </div>
    </div>

    <div class="aside_item">

        <h4 class="aside_item_title">Categorias</h4>
        <nav class="aside_catogories">
            <ul class="block">
<?php

$sectionCats = $LINK->index[0] == 'blog' ? '/' . $LINK->index[0] : null;


if ($cats) {foreach ($cats as $catKey) {
    $catKey['cat_num'] = $catKey['amount'];
    $catKey['cat_link'] = HOME . $sectionCats . '/categoria/' . $catKey[$FIXC . 'link'];
    echo FNC::view($catKey, TPL . 'cat_li.html');
}}

?>

            </ul>
        </nav>
        <h4 class="aside_item_title"><?=$secTitle?></h4>

<?php

if ($mostViewed) {foreach ($mostViewed as $postKey) {
    $postCover = PATHPOSTS . $postKey[$FIX . 'path'] . '/' . $postKey[$FIX . 'cover'];
    $postKey['IMAGE'] = IMAGE;
    $postKey['cover'] = Check::Image($postCover, IMAGE);
    $postKey['link'] = HOME . '/post/' . $postKey[$FIX . 'link'];
    echo FNC::view($postKey, TPL . 'post_box_aside.html');
}}

?>

        <h6 class="aside_item_title">Tags</h6>
        <nav class="nav aside_tags">

        <?php if ($tags) { echo FNC::inLink($tags, HOME . '/tag', true);}?>

        </nav>

    </div>

</aside>
