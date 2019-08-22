<?php

/**
 * Inicio do documento HTML
 */

// Verfica se imagem de capa existe
function checkCoverSeo(string $image = null)
{
    $IMG = [];
    $IMG['cover'] = $image ?? str_replace(HOME . '/', '', COVER);
    $IMG['coveralt'] = TITLE;
    $IMG['coverw'] = 1280;
    $IMG['coverh'] = 720;

    $file = str_replace('/', DS, $image);

    if (file_exists($file) && !is_dir($file)) {
        $IMG['cover'] = $image;
        $exp = explode('/', $image);
        $alt = str_replace(['.png', '.jpg', '.jpeg', '.gif'], '', end($exp));
        $alt = FNC::convertStr($alt, 'text');
        $IMG['coveralt'] = $alt;
        list($w, $h) = getimagesize($image);
        $tags['coverw'] = $w;
        $tags['coverh'] = $h;
    }
    return $IMG;
}

/**
 * MetaTags SEO
 */

$FIX = TBPAGES[1];
$SEO = $JPAGEINFO ?? $PAGES->getData();
if (!$SEO) {$SEO = [];}

$JSONLD = null;
$SEOCOVER = checkCoverSeo($SEO[$FIX . 'cover'] ?? null);

$SEO[$FIX . 'title'] = $SEO[$FIX . 'title'] ?? TITLE;
$SEO[$FIX . 'description'] = $SEO[$FIX . 'description'] ?? DESCRIPTION;
$SEO[$FIX . 'cover'] = $SEOCOVER['cover'];
$SEO[$FIX . 'coveralt'] = $SEOCOVER['coveralt'];
$SEO[$FIX . 'coverw'] = $SEOCOVER['coverw'];
$SEO[$FIX . 'coverh'] = $SEOCOVER['coverh'];
$SEO[$FIX . 'type'] = $SEOCOVER['type'] ?? 'WebSite';

// URL atual
$initLink = TRANSFERPROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$SEO[$FIX . 'link'] = $SEO[$FIX . 'link'] ?? str_replace(HOME, '', $initLink);

echo '<!DOCTYPE html><html lang="pt-BR"><head>';

// Google Analytics
$SEO[$FIX . 'analytics'] = $SEO[$FIX . 'analytics'] ?? GOOGLEANALYTICSID;
$SEO[$FIX . 'analytics'] = !empty($SEO[$FIX . 'analytics']) ? $SEO[$FIX . 'analytics'] : GOOGLEANALYTICSID;

if (!empty($SEO[$FIX . 'analytics'])) {
    echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$SEO[$FIX . 'analytics']}\"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);} gtag('js',new Date());gtag('config','{$SEO[$FIX . 'analytics']}');</script>";
}

// Informações da página
echo '<meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><meta http-equiv="x-ua-compatible" content="ie=edge"/><meta name="robots" content="index, follow"/>';

echo '<title>' . $SEO[$FIX . 'title'] . '</title><meta name="description" content="' . $SEO[$FIX . 'description'] . '"/><link rel="base" href="' . HOME . '"/><link rel="home" href="' . HOME . '"/><base id="urlHome" href="' . HOME . '"/><meta property="twitter:creator" content="' . DEVTWITTERCREATOR . '"/>';

// Metatags de SEO
if (SEO) {

    echo '<link rel="canonical" href="' . HOME . '/' . $SEO[$FIX . 'link'] . '"/>';
    echo '<link rel="shortlink" href="' . HOME . '/' . $SEO[$FIX . 'link'] . '"/>';
    echo '<link rel="alternate" hreflang="x-default" href="' . HOME . '"/>';
    echo '<link rel="alternate" type="application/rss+xml" href="' . HOME . '/rss.xml"/>';
    echo '<link rel="sitemap" type="application/xml" href="' . HOME . '/sitemap.xml"/>';

    echo '<meta itemprop="name" content="' . $SEO[$FIX . 'title'] . '"/>';
    echo '<meta itemprop="description" content="' . $SEO[$FIX . 'description'] . '"/>';
    echo '<meta itemprop="image" content="' . HOME . '/' . $SEO[$FIX . 'cover'] . '"/>';
    echo '<meta itemprop="url" content="' . HOME . '/' . $SEO[$FIX . 'link'] . '"/>';

    // Facebook
    echo '<meta property="og:site_name" content="' . TITLE . '"/>';
    echo '<meta property="og:locale" content="pt_BR"/>';
    echo '<meta property="og:type" content="' . $SEO[$FIX . 'type'] . '"/>';
    echo '<meta property="og:title" content="' . $SEO[$FIX . 'title'] . '"/>';
    echo '<meta property="og:description" content="' . $SEO[$FIX . 'description'] . '"/>';
    echo '<meta property="og:image:url" content="' . HOME . '/' . $SEO[$FIX . 'cover'] . '"/>';
    echo '<meta property="og:image:alt" content="' . $SEO[$FIX . 'title'] . '"/>';
    echo '<meta property="og:image:width" content="' . $SEO[$FIX . 'coverw'] . '"/>';
    echo '<meta property="og:image:height" content="' . $SEO[$FIX . 'coverh'] . '"/>';
    echo '<meta property="og:url" content="' . HOME . '/' . $SEO[$FIX . 'link'] . '"/>';
    echo '<meta property="ia:markup_url" content="' . HOME . '/' . $SEO[$FIX . 'link'] . '"/>';
    echo '<meta property="fb:pages" content="' . FACEBOOKPAGEID . '"/>';

    // Twitter
    echo '<meta property="twitter:card" content="summary_large_image"/>';
    echo '<meta property="twitter:title" content="' . $SEO[$FIX . 'title'] . '"/>';
    echo '<meta property="twitter:description" content="' . $SEO[$FIX . 'description'] . '"/>';
    echo '<meta property="twitter:image" content="' . HOME . '/' . $SEO[$FIX . 'cover'] . '"/>';
    echo '<meta property="twitter:image:width" content="' . $SEO[$FIX . 'coverw'] . '"/>';
    echo '<meta property="twitter:image:height" content="' . $SEO[$FIX . 'coverh'] . '"/>';
    echo '<meta property="twitter:url" content="' . HOME . '/' . $SEO[$FIX . 'link'] . '"/>';
    echo '<meta property="twitter:domain" content="' . HOME . '"/>';

    // ld-json
    //echo '<script type="application/ld+json">' . $JSONLD . '</script>';

}
