<?php

/**
 * Gerencia URL's amigáveis
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Link
{

    /**
     * @var Array Indeces da url
     */
    public $index;

    /**
     * @var String Sessão diretório em que o arquivo se encontra
     */
    public $section;

    /**
     * @var String Arquivo que o sistema deverá incluir
     */
    public $file;

    /**
     * @var String Caminho base de onde os arquivo deverão ser lidos
     */
    public $path;

    public function __construct(string $dir = REQ, string $path = 'pages', bool $checkLink = false)
    {
        // Diretório base de páginas
        $this->path = (string) $dir . $path . DS;

        // Explode a url e tranforma em indices
        $this->index = strip_tags(trim(filter_input(INPUT_GET, 'page', FILTER_DEFAULT)));
        $this->index = $this->index ? $this->index : 'home';
        $this->index = explode('/', $this->index);

        if($checkLink){$this->checkLink($this->path, $this->index);}

    }

    /**
     * Verfica se a página existe de acordo com os parametros passados pela URL
     */
    private function checkLink($path, $index)
    {

        // Passo um
        $this->file = $this->checkFile($path, $index[0]);
        $this->section = (!$this->file && is_dir($path . $index[0])) ? $index[0] : null;
        if (!$this->file && !$this->section) {$this->file = $this->checkFile($path, 'home');}

        // Passo dois
        if (!$this->file && $this->section) {
            $this->file = isset($index[1]) ? $this->checkFile($path, $index[1], $this->section) : $this->checkFile($path, 'home', $this->section);
            $this->file = $this->file ?? $this->checkFile($path, 'home', $this->section);
        }

        if (!$this->file) {FNC::redirect(HOME . '/404');die;}
    }

    /**
     * Verifica e monta o caminho do arquivo para o require()
     */
    private function checkFile($path, $file, $section = null)
    {
        $file = (string) trim($file) . '.php';
        $section = (string) !$section ? null : trim($section) . DS;
        return file_exists($path . $section . $file) ? $path . $section . $file : null;
    }
}
