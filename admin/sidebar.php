<?php

// Verfica se é super usuário
$SUPERUSER = $USERACTIVE[TBUSERS[1] . 'accesslevel'] == 10;

$currentSection = $LINK->section != null ? $LINK->section : 'home';

echo '<aside id="sidebar" class="bg-white box-shadow" data-visible="false"><div class="main_logo">JLord</div><nav id="navigation" class="nav" data-target="' . $currentSection . '"><a class="nav_item" href="' . ADM . '" title="Dashboard" data-section="home" data-active="false"><i class="fa fa-home"></i>Dashboard</a>';

//  Categorias
echo '<a class="nav_item" href="' . ADM . '/cats" title="Categorias" data-section="cats"><i class="fa fa-filter"></i>Categorias</a>';

// Posts
echo ($SUPERUSER || POSTS) ? '<a class="nav_item" href="' . ADM . '/posts" title="Posts" data-section="posts"><i class="fa fa-pencil"></i>Posts</a>' : null;

// Menu
echo '<a class="nav_item" href="' . ADM . '/menu" title="Menu de navegação" data-section="manu"><i class="fa fa-list"></i>Navegação</a>';

// Páginas
// echo ($SUPERUSER || PAGES) ? '<a class="nav_item" href="' . ADM . '/pgs" title="Páginas" data-section="pgs"><i class="fa fa-file"></i>Páginas</a>' : null;

// Analytics
//echo ($SUPERUSER || ANALYTICS) ? '<a class="nav_item" href="' . ADM . '/analytics" title="Relatórios do sistema" data-section="analytics"><i class="fa fa-bar-chart-o"></i>Relatórios</a>' : null;

// Pesquisas
//echo ($SUPERUSER || SEARCH) ? '<a class="nav_item" href="' . ADM . '/searches" title="Relatórios de persquisas" data-section="searches"><i class="fa fa-search"></i>Pesquisas</a>' : null;

// Usuários
echo '<a class="nav_item" href="' . ADM . '/users" title="Usuários" data-section="users"><i class="fa fa-users"></i>Usuários</a>';

// Sistema
echo '<a class="nav_item" href="' . ADM . '/system" title="Sistema" data-section="system"><i class="fa fa-cogs" ></i>Sistema</a>';

// Ver site e Fim
echo '</nav><a class="view_site btn btn-red inline-block center radius" href="' . HOME . '" title="Ver site" target="_blank"><i class="fa fa-laptop"></i>VER SITE</a></aside>';
