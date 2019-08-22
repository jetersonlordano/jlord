<?php

/**
 * Cria páginas estáticas do site na pasta cacheDir
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Cache
{
    private $dir;
    private $file;

    public function __construct($link)
    {
        $dir = $link->section ?? 'home';
        $file = $_GET['page'] ?? 'home';
        $file = FNC::convertStr($file);
        $this->dir = (CACHE) ? Check::Path(CACHEDIR, $dir) . DS : null;
        $this->file = $this->dir . $file . '.html';
    }

    public function clearCache($file = null)
    {return ($file) ? FNC::delFile($file) : FNC::clearDir(CACHEDIR, true);}

    public function checkCache($time = null)
    {
        if (file_exists($this->file)) {
            $time = $time ?? time();
            return (filemtime($this->file) < $time) ? $this->file : null;
        } else {return null;}
    }

    public function newPage()
    {
        if (!isset($_GET['jview'])) {
            return (!isset($_GET['jcache']) && CACHE) ? $this->createCache() : false;
        } else {return false;}
    }

    private function createCache()
    {
        if ($this->dir) {

            $page = isset($_GET['page']) ? '/index.php?page=' . $_GET['page'] : '/index.php?page=home';

            $urlCache = HOME . $page . "&jcache=1";

            $cURL = curl_init($urlCache);
            curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURL, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $content = curl_exec($cURL) ?? null;
            curl_close($cURL);

            $fileCache = ($content) ? fopen($this->file, 'wb') : null;
            $write = ($fileCache) ? fwrite($fileCache, $content) : null;
            return ($write) ? fclose($fileCache) : false;

            //return ($content) ? file_put_contents($fileName, $content) : false;

        } else {return false;}
    }
}
