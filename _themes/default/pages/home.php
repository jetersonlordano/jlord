<?php

/**
 * Posts
 */

$FIX = TBPOSTS[1];
$FIXC = TBCATS[1];
$FIXPG = TBPAGES[1];

// $JPAGEINFO = $PAGES->getData();
$SECTION = '/categoria/';
$SINGLE = '/post/';

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

?>

<div id="mainWrapper" class="wrapper">
    <div class="container">
        <div class="row align-items-start">
            <section id="mainBlog" class="col-12 col-md-7 col-lg-8">

                <div id="blogContainer">


                    <?php

if ($POSTS) {foreach ($POSTS as $pkey) {
    $pCover = PATHPOSTS . $pkey[$FIX . 'path'] . '/' . $pkey[$FIX . 'cover'];
    $pkey['IMAGE'] = IMAGE;
    $pkey['post_cover'] = Thumb::Nail($pCover, 600, null, $pkey[$FIX . 'lastupdate'], 'blog');
    $pkey['link'] = HOME . $SINGLE . $pkey[$FIX . 'link'];
    $pkey['linkcat'] = HOME . $SECTION . $pkey[$FIXC . 'link'];
    $pkey[$FIXC . 'title'] = strtoupper($pkey[$FIXC . 'title']);
    $pkey['date'] = strftime('%d de %B de %Y', strtotime($pkey[$FIX . 'lastupdate']));
    echo FNC::view($pkey, TPL . 'post_box.html');

}} else {echo 'Nenhum post encontrado!';}

?>

                </div>
                <div class="posts_pagination flex justify-content-between">
<?php

// Total de posts
$conn->select("count(post_id) as posts_total", TBPOSTS[0], $terms, $values);
$conn->exec();
$totalPosts = $conn->fetchAll()[0]['posts_total'];

$urlPagination = '/';

if ($LINK->index[0] == 'categoria' || $LINK->index[0] == 'pesquisa') {
    $urlPagination = '/' . $LINK->index[0] . '/' .  $LINK->index[1] . '/';
}

$countPosts = $POSTS ? count($POSTS) : 0;
$pgnt = FNC::pagination($countPosts, $totalPosts, $pgn, $numChildren, HOME . $urlPagination);

if ($pgnt['linknext'] != 'javascript:void(0);') {
    echo '<a class="btn btn-main btn-md" href="' . $pgnt['linknext'] . '" title="Posts mais antigos"><i class="fa fa-arrow-left"></i> Mais antigos</a>';
}

if ($pgnt['linkprev'] != 'javascript:void(0);') {
    echo '<a class="btn btn-main btn-md" href="' . $pgnt['linkprev'] . '" title="Posts mais recentes">Mais recentes <i class="fa fa-arrow-right"></i></a>';
}

?>



                </div>

            </section>

            <!-- sidebar -->
            <?php include_once __DIR__ . DS . 'parts' . DS . 'aside.php';?>

        </div>
    </div>
</div>