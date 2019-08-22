<?php

if (isset($_GET['jcache'])) {echo 'jcache';die;}

require '../../../../_app/Client.inc.php';

// JSON
header('Content-Type: application/json; charset=utf-8');
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$postNumChildren = $obj['qtd'] ?? 1;

// Número da página
$pgn = Check::pgn($obj['page'], $postNumChildren);

// Ordem
$order = 'date';
$seq = 'DESC';

// Consulta de post no banco de dados
$distinct = "DISTINCT post_id, ";
$fields = "post_id, post_link, post_path, post_title, post_description, post_category, post_cover, post_lastupdate, cat_title";
$join = "INNER JOIN categories on cat_id = post_category";

$terms = " WHERE post_published >= :public AND post_date <= :now";
$terms .= " ORDER BY post_{$order} {$seq}";
$values = ['public' => 1, 'now' => date('Y-m-d H:i:s')];

if (isset($obj['id'])) {
    $terms = " WHERE post_id != :id AND post_published = :public AND post_date <= :now";
    $terms .= " ORDER BY RAND()";
    $values = ['id' => $obj['id'], 'public' => 1, 'now' => date('Y-m-d H:i:s')];
}

// Limite de dados
$limit = " LIMIT {$pgn['init']}, " . $postNumChildren;

// Select posts
$conn = new Conn();
$conn->select($distinct . $fields, TBPOSTS[0], $join . $terms . $limit, $values);
$conn->exec();
$posts = $conn->fetchAll();

$cacheDir = '../../../../' . STATICPAGEDIR;

$callback = ['action' => 'close', 'element' => 'postPlusBtn'];
$callback = json_encode($callback);
if (!$posts) {echo $callback;die;}

$totalPosts = count($posts);
for ($i = 0; $i < $totalPosts; $i++) {

    $posts[$i]['post_link'] = HOME . '/post/' . $posts[$i]['post_link'];
    $posts[$i]['post_cover'] = HOME . PATHPOSTS . $posts[$i]['post_path'] . '/' . $posts[$i]['post_cover'];

    $posts[$i]['post_category'] = HOME . '/blog/' . FNC::convertStr($posts[$i]['cat_title']);

    if ($i >= ($totalPosts - 1)) {
        $callback = ['action' => 'function', 'fn' => 'clientPostsLoading', 'data' => $posts];
        $callback = json_encode($callback);
        echo $callback;
    }
}
