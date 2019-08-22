<?php

if ($LINK->index[0] != 'home') {FNC::redirect(ADM);die;}

/**
 * Usuários Online
 */
$userOn = '0000';
if (USERSONLINE) {

    $FIXON = TBONLINE[1];
    $fields = "count({$FIXON}id) as total";
    $terms = "WHERE {$FIXON}end >= :now";
    $values = ['now' => date('Y-m-d H:i:s')];

    $conn = new Conn();
    $conn->select($fields, TBONLINE[0], $terms, $values);
    $conn->exec();
    $userOn = $conn->fetchAll()[0]['total'] ?? null;

    switch (strlen($userOn)) {

        case 0:
            $userOn = '0000' . $userOn;
            break;
        case 1:
            $userOn = '000' . $userOn;
            break;
        case 2:
            $userOn = '00' . $userOn;
            break;
        case 3:
            $userOn = '0' . $userOn;
            break;
        default:
            $userOn = $userOn;
            break;
    }

}

/**
 * Estatísticas
 */

// Data de inicio para recuperar os dados
$values = ['date' => date('Y-m-d', strtotime(date('Y-m-d')) - (60 * 60 * 24 * 60))];

// Consulta estatísticas
$FIXV = TBVIEWS[1];
$resultViews = null;

if (ANALYTICS):
    $conn->select('*', TBVIEWS[0], "WHERE {$FIXV}date >= :date", $values);
    $conn->exec();
    $resultViews = $conn->fetchAll();
endif;

// Estatísticas dos mês
$thisMont = date('Y-m');
$monthSessions = 0;
$monthPages = 0;
$lastMonthSessions = 0;
$lastMonthPages = 0;
$lastTwoMonthSessions = 0;
$lastTwoMonthPages = 0;

// Estatísticas da semana
$thisWeek = date('W');
$weekSessions = 0;
$weekPages = 0;
$lastWeekSessions = 0;
$lastWeekpages = 0;
$lastTwoWeekSessions = 0;
$lastTwoWeekpages = 0;

// Estatísticas de hoje
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime(date('Y-m-d')) - (60 * 60 * 24 * 1));
$beforeYesterday = date('Y-m-d', strtotime(date('Y-m-d')) - (60 * 60 * 24 * 2));
$todaySessions = 0;
$todayPages = 0;
$yesterdaySessions = 0;
$yesterdayPages = 0;
$beforeYesterdaySessions = 0;
$beforeYesterdayPages = 0;

// Verfica se a data esta entre o intervalo de dias
function dateInternval(string $date, int $init = 0, int $end = 0)
{
    $subtrInitDays = strtotime(date('Y-m-d')) - (60 * 60 * 24 * $init);
    $subtrEndDays = strtotime(date('Y-m-d')) - (60 * 60 * 24 * $end);
    return (strtotime($date) > $subtrInitDays && strtotime($date) <= $subtrEndDays);
}

// Verifica semana
function checkWeek(string $date, string $thisMont, string $thisWeek)
{return (substr($date, 0, 7) == $thisMont && date('W', strtotime($date)) == $thisWeek);}

foreach ($resultViews as $keyViews) {

    // Views Hoje
    if ($keyViews[$FIXV . 'date'] == $today) {
        $todaySessions = $keyViews[$FIXV . 'sessions'];
        $todayPages = $keyViews[$FIXV . 'pages'];
    }

    // Views ontem
    if ($keyViews[$FIXV . 'date'] == $yesterday) {
        $yesterdaySessions = $keyViews[$FIXV . 'sessions'];
        $yesterdayPages = $keyViews[$FIXV . 'pages'];
    }
    // Views anteontem
    if ($keyViews[$FIXV . 'date'] == $beforeYesterday) {
        $beforeYesterdaySessions = $keyViews[$FIXV . 'sessions'];
        $beforeYesterdayPages = $keyViews[$FIXV . 'pages'];
    }

    // Soma esta semana
    if (checkWeek($keyViews[$FIXV . 'date'], $thisMont, $thisWeek)) {
        $weekSessions += $keyViews[$FIXV . 'sessions'];
        $weekPages += $keyViews[$FIXV . 'pages'];
    }

    // Soma últimos 7 dias
    if (dateInternval($keyViews[$FIXV . 'date'], 7)) {
        $lastWeekSessions += $keyViews[$FIXV . 'sessions'];
        $lastWeekpages += $keyViews[$FIXV . 'pages'];
    }

    // Soma últimos 14 à 7 dias
    if (dateInternval($keyViews[$FIXV . 'date'], 14, 7)) {
        $lastTwoWeekSessions += $keyViews[$FIXV . 'sessions'];
        $lastTwoWeekpages += $keyViews[$FIXV . 'pages'];
    }

    // Soma este mês
    if (substr($keyViews[$FIXV . 'date'], 0, 7) == $thisMont) {
        $monthSessions += $keyViews[$FIXV . 'sessions'];
        $monthPages += $keyViews[$FIXV . 'pages'];
    }

    // Soma últimos 30 dias
    if (dateInternval($keyViews[$FIXV . 'date'], 30)) {
        $lastMonthSessions += $keyViews[$FIXV . 'sessions'];
        $lastMonthPages += $keyViews[$FIXV . 'pages'];
    }

    // Soma últimos 60 à 30 dias
    if (dateInternval($keyViews[$FIXV . 'date'], 60, 30)) {
        $lastTwoMonthSessions += $keyViews[$FIXV . 'sessions'];
        $lastTwoMonthPages += $keyViews[$FIXV . 'pages'];
    }

}

// Campara os dados e calcula a poncentagem da diferença
function comparePct($target, $last)
{return ($target && $last) ? round((($target / $last) * 100) - 100, 2) : 0;}

// Camparações em %
$weekCompare = comparePct($lastWeekSessions, $lastTwoWeekSessions);
$monthCompare = comparePct($lastMonthSessions, $lastTwoMonthSessions);
$yesterdayCompare = comparePct($yesterdaySessions, $beforeYesterdaySessions);

// Welcome
echo '<div class="row"><div class="col"><span class="welcome_dashboard">Bem vindo ao ' . CMSNAME . '</span></div></div>';

/**
 * Estatíticas
 */

echo '<section class="row align-items-stretch">';

// Usuários Online
echo '<div class="col col-3 statistic_box"><div class="card box-shadow bg-white radius"><div class="card-body justify-content-between"><div class="statistic_box_info"> <span class="statistic_box_title">USUÁRIOS ONLINE</span><div class="statistic_box_values"><div class="item"><span id="usersOnlineNow">' . $userOn . '</span></div></div></div> <span class="statistic_box_icon bg-red"> <i class="fa fa-users"></i></span> <a class="btn btn-green radius" href="javascript:void(0);" title="Acompanhar usuários online">ACOMPANHAR USUÁRIOS</a></div></div></div>';

$statistics = [];

// Hoje
$statistics['now'] = [
    'name' => 'HOJE',
    'color' => 'blue',
    'sessions' => $todaySessions,
    'pages' => $todayPages,
    'arrowcolor' => $yesterdayCompare <= 0 ? 'red' : 'green',
    'arrow' => $yesterdayCompare <= 0 ? 'down' : 'up',
    'compare' => $yesterdayCompare,
    'info' => 'Desde ontem',
];

// Esta semana
$statistics['week'] = [
    'name' => 'ESTA SEMANA',
    'color' => 'orange',
    'sessions' => $weekSessions,
    'pages' => $weekPages,
    'arrowcolor' => $weekCompare <= 0 ? 'red' : 'green',
    'arrow' => $weekCompare <= 0 ? 'down' : 'up',
    'compare' => $weekCompare,
    'info' => 'Últimos 7 dias',
];

// Este mês
$statistics['month'] = [
    'name' => 'ESTE MÊS',
    'color' => 'green',
    'sessions' => $monthSessions,
    'pages' => $monthPages,
    'arrowcolor' => $monthCompare <= 0 ? 'red' : 'green',
    'arrow' => $monthCompare <= 0 ? 'down' : 'up',
    'compare' => $monthCompare,
    'info' => 'Últimos 30 dias',
];

foreach ($statistics as $stVlr) {echo FNC::view($stVlr, 'tpl' . DS . 'statistic_box.html');}

// Fecha estatísticas
echo '</section>';

/**
 * Posts
 */
if (POSTS):
    echo '<div class="row"><div class="col"><div class="card box-shadow bg-white radius"><div class="card-body justify-content-between align-items-center"><div class="card-title">POSTS MAIS VISTOS</div><a class="btn btn-main box-shadow-alt radius card-top-btn" href="' . ADM . '/posts" title="Todos os posts">POSTS</a></div><div class="card-body"><div class="table"><table><tbody><tr><th>TÌTULO</th><th>ÚLTIMA VISITA</th><th>VIEWS</th><th>AÇÔES</th></tr>';

    // Tpl tabela posts
    $tplTbPost = '<tr><td>#post_title#</td><td>#post_lastview#</td><td>#post_views#</td><td class="table-actions"><a class="btn btn-red radius" href="#post_link#" target="_blank">Ver post</a></td></tr>';

    $FIXP = TBPOSTS[1];
    $fields = "{$FIXP}title, {$FIXP}lastview, {$FIXP}link, {$FIXP}views";
    $terms = "ORDER BY {$FIXP}views DESC LIMIT 6";
    $conn = new Conn();
    $conn->select($fields, TBPOSTS[0], $terms);
    $conn->exec();
    $posts = $conn->fetchAll();
    if ($posts) {foreach ($posts as $pVlr) {
        $pVlr['post_link'] = HOME . '/post/' . $pVlr['post_link'];
        echo FNC::view($pVlr, null, $tplTbPost);
    }}

    // Fecha posts
    echo '</tbody></table></div></div></div></div></div>';
endif;

/**
 * Pesquisas
 */

if (SEARCH):

    echo '<div class="row"><div class="col"><div class="card box-shadow bg-white radius"><div class="card-body justify-content-between align-items-center"><div class="card-title">PESQUISAS EM ALTA</div><a class="btn btn-main box-shadow-alt radius card-top-btn" href="' . ADM . '/searches" title="Pesquisas">PESQUISAS</a></div><div class="card-body"><div class="table"><table><tbody><tr><th>PESQUISA</th><th>SEÇÃO</th><th>ITENS</th><th>ÚLTIMA PESQUISA</th><th>TOTAL</th></tr>';

    $tplTbSearches = '<tr><td>#sch_term#</td><td>#sch_section#</td><td>#sch_found#</td><td>#sch_lastsearch#</td><td>#sch_count#</td></tr>';

    $conn = new Conn();
    $conn->select('*', TBSEARCHES[0], 'ORDER BY ' . TBSEARCHES[1] . 'count DESC LIMIT 6');
    $conn->exec();
    $SRCHS = $conn->fetchAll();
    if ($SRCHS) {foreach ($SRCHS as $srchVlr) {echo FNC::view($srchVlr, null, $tplTbSearches);}}

    // Fecha Pesquisas
    echo '</tbody></table></div></div></div></div></div>';

endif;

/**
 * Scripts
 */

if (USERSONLINE) {echo "<script async>var newObjUsersOnline={file:'async/usersOnline.php'};setInterval(function(){ajax(newObjUsersOnline);}, 15000);var usersOnlineNow=jget('#usersOnlineNow');function showUsersOnline(users){usersOnlineNow.textContent=users;}setTimeout(function(){ ajax(newObjUsersOnline); }, 2000);function uploadListUsersOn(obj){console.log(obj);}</script>";}
