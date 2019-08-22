<?php

if(isset($_GET['jcache'])){echo '';die;}

require '../../../../_app/Client.inc.php';

// JSON
header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$postNumChildren = POSTSNUMCHILDREN;

// Número da página
$pgn = Check::pgn($obj['page'], $postNumChildren);

$conn = new Conn();

// Ordem
$order = POSTSORDERBY;
$seq = POSTSORDERSEQ;

// Consulta de post no banco de dados
$distinct = null;
$fields = "post_id, post_link, post_path, post_title, post_description, post_category, post_cover, post_lastupdate, cat_title";
$join = "INNER JOIN categories on categories.cat_id = posts.post_category";

if(isset($obj['tag'])){

    $distinct = "DISTINCT post_id, ";
    $tag = FNC::convertStr($obj['tag'],'text');
    $terms = " WHERE post_tags LIKE :tag AND post_published = :public AND post_date <= :now";
    $terms .= " ORDER BY post_{$order} {$seq}";
    $values = ['public' => 1, 'now' => date('Y-m-d H:i:s'), 'tag' => '%'.$tag.'%'];

}else{
    $category = FNC::convertStr($obj['category'],'text');
    $terms = " WHERE cat_title = :cat AND post_published = :public AND post_date <= :now";
    $terms .= " ORDER BY post_{$order} {$seq}";
    $values = ['public' => 1, 'now' => date('Y-m-d H:i:s'), 'cat' => $category];
}

// Limite de dados
$limit = " LIMIT {$pgn['init']}, " . $postNumChildren;

// Select posts
$conn->select($distinct . $fields, TBPOSTS[0], $join . $terms . $limit, $values);
$conn->exec();
$posts = $conn->fetchAll();

if ($posts) {

    $totalPosts = count($posts);

    for ($i = 0; $i < $totalPosts; $i++) {

        $posts[$i]['post_link'] = POSTSSINGLE . '/' . $posts[$i]['post_link'];
        $postCover = HOME . PATHPOSTS . $posts[$i]['post_path'] . '/' . $posts[$i]['post_cover'];
        $posts[$i]['post_cover'] = Thumb::Nail($postCover, POSTSCOVERCACHE, null, $posts[$i]['post_lastupdate'], '../../../../cacheDir');
        $posts[$i]['post_category'] = HOME . '/categoria/' . FNC::convertStr($posts[$i]['cat_title']);

        if ($i >= ($totalPosts - 1)) {

            $callback = [
                'action' => 'function',
                'fn' => 'clientPostsLoading',
                'data' => $posts,
            ];
            $callback = json_encode($callback);
            echo $callback;
        }
    }
} else {

    $callback = [
        'action' => 'close',
        'element' => 'postPlusBtn',
    ];
    $callback = json_encode($callback);
    echo $callback;
}