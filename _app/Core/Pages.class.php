<?php

class Pages
{
    public $files = [];
    private $data;

    /**
     * Verifica qual página está sendo requisitada e chama as sua funções
     * @param Object $link Objeto da class Link.class.php
     */
    public function __construct($LINK)
    {

        $FIX = TBPAGES[1];
        $file = $LINK->index[0];
        $path = $LINK->path;

        // Se existir $_POST['search'] redireciona para página de pesquisa.
        $this->checkSearch($path);

        // Lê a página do bando de dados
        $pageData = $this->loadPages($file);

        // Preloader da página
        $loader = $this->addFiles('loader', $pageData[$FIX . 'file'], $path, 'loaders');
        $loader = $loader ?? $this->addFiles('loader', $file, $path, 'loaders');
        if (!$loader && $pageData) {$this->addFiles('loader', 'loader', DEFAULTS, 'includes');}

        // Init
        $this->addFiles('init', 'init', DEFAULTS, 'includes');

        // Header default
        $header = $this->addFiles('header', 'header', $path, 'includes');
        if (!$header) {PHPNOTIFY("Crie o arquivo 'header.php' no diretório {$path}includes");die;}
        if ($pageData) {$this->addFiles('header', $pageData[$FIX . 'header'], $path, 'includes');}

        // Conteúdo da página
        $page = $this->addFiles('page', $pageData[$FIX . 'file'], $path);
        $page = $page ?? $this->addFiles('page', $file, $path);
        $page = $page ?? $this->addFiles('page', 'default', DEFAULTS, 'includes');

        // Footer default
        $footer = $this->addFiles('footer', 'footer', $path, 'includes');
        if (!$footer) {PHPNOTIFY("Crie o arquivo 'footer.php' no diretório {$path}includes");die;}
        if ($pageData) {$this->addFiles('footer', $pageData[$FIX . 'footer'], $path, 'includes');}

        // End
        $this->addFiles('end', 'end', DEFAULTS, 'includes');

        // Dados da página
        $this->data = $pageData;
    }

    private function addFiles(string $name, string $file = null, string $dir, string $path = null)
    {
        if (!empty($file)) {
            $this->files[$name] = $file ? $this->checkFile($dir, $path, $file) : null;
            return $this->files[$name];
        }
    }

    /**
     * Verifica se os arquivo PHP da página existe
     * @param String $dir
     * @param String $path
     * @param String $file nome do arquivo php
     */
    private function checkFile(string $dir = null, string $path = null, string $file)
    {
        $file = $dir . DS . $path . DS . $file . '.php';
        $file = str_replace([DS . DS . DS, DS . DS], DS, $file);
        return file_exists($file) ? $file : null;
    }

    public function getData()
    {return $this->data;}

    /**
     * Verifica se está pesquisando e redireciona para página de pesquisa
     * @param String $path Diretório do arquivo de pesquisa
     */
    private function checkSearch(string $path = null)
    {
        $search = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($search['search']) && !empty($search['search'])) {

            $searchPage = (isset($search['page']) && !empty($search['page']));
            $searchPage = $searchPage ? FNC::convertStr($search['page']) : 'pesquisa';
            $search = urlencode(strip_tags(trim($search['search'])));

            if (!$this->checkFile($path, 'loaders', $searchPage)) {PHPNOTIFY("Crie o arquivo {$path}loaders" . DS . $searchPage . '.php');die;}

            if (!$this->checkFile($path, null, $searchPage)) {PHPNOTIFY("Crie o arquivo {$path}{$searchPage}.php");die;}

            header('Location: ' . HOME . '/' . $searchPage . '/' . $search);
            die;
        }
    }

    /**
     * Carrega páginas do banco
     */
    private function loadPages($page)
    {
        $FIX = TBPAGES[1];
        $terms = "WHERE {$FIX}link = :link AND ({$FIX}published >= :published AND {$FIX}theme = :theme) LIMIT 1";
        $published = isset($_GET['jview']) ? 0 : 1;
        $values = ['link' => $page, 'published' => $published, 'theme' => THEME];
        $conn = new Conn();
        $conn->select('*', TBPAGES[0], $terms, $values);
        $conn->exec();
        $result = $conn->fetchAll();
        return $result ? $result[0] : null;
    }

}
