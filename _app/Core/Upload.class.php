<?php

/**
 * Class Upload
 * Faz upload de arquivos e cria o seus diretórios
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Upload
{
    public $baseDir = 'uploads';
    public $maxSize = 5;
    public $log;
    public $name;
    public $type;
    public $path;

    public function newFile(array $file, string $path = null, string $name = null)
    {
        // Previne diretório Base
        $this->baseDir = (string) trim(str_replace([DS, '/'], ' ', $this->baseDir));
        $this->baseDir = str_replace(' ', DS, $this->baseDir);

        // Previne o tamanho máximo do arquivo
        $this->maxSize = (int) $this->maxSize;

        // Previne diretório do arquivo
        $path = !$path ? DS : DS . str_replace('/', DS, $path) . DS;
        $path = $path != DS ? str_replace([DS . DS], DS, $path) : DS;

        // Recupera extensão do arquivo
        $type = $this->getExtension($file['name']);
        $this->type = $type;

        // Recupera o nome do arquivo;
        $name = !$name ? $this->getFileName($file['name'], $type, $path) : FNC::convertStr($name);
        $this->name = $name;

        // Valida tamanho do arquivo
        $checkSize = $this->checkSize($file['size']);
        if (!$checkSize) {$this->log = 'Arquivo maior que o limite permitido!'; return false; die;}

        // Valida tipo do arquivo
        $checkType = $this->checkType($file['type']);
        if(!$checkType){$this->log = 'Este tipo de arquivo não é permitido!'; return false; die;}

        if ($checkSize && $checkType) {

            // Cria ou recupera o diretório do arquivo
            $dir = FNC::createDir($this->baseDir, $path);
            $this->path = $dir;

            // Valida diretório
            if(!$dir){$this->log = 'Diretório inválido!'; return false; die;}

            // Move o arquivo para o diretório
            return $this->moveFile($file['tmp_name'], $dir . DS . $name . $type);
        }

    }

    /**
     * Cria ou recupera o nome do arquivo e será movido
     * @param String $fileName Nome do arquivo
     * @param String $type Extensão do arquivo
     * @param String $path Diretório para qual o arquivo será movido
     */
    private function getFileName($fileName, $type, $path)
    {
        $fileName = str_replace($type, '', $fileName);
        $fileName = FNC::convertStr($fileName);
        $file = $this->baseDir . $path . $fileName . $type;
        $newName = $fileName . '-' . time() . rand(0, 1000);
        return file_exists($file) ? $newName : $fileName;
    }

    /**
     * Verifica se o tipo do arquivo é permitido
     * @param String $type MIMEType do arquivo
     * @return Boolean
     */
    private function checkType(string $type)
    {
        $MIMETypes = ['image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif', 'application/pdf', 'video/ogg'];
        return in_array($type, $MIMETypes);
    }
    /**
     * Recupera a extensão do arquivo
     * @param String $fileName Nome do arquivo
     * @return String Extensão do arquivo
     */
    private function getExtension(string $fileName)
    {
        $exp = explode('.', $fileName);
        return strtolower('.' . end($exp));
    }

    /**
     * Verifica se o tamanho do arquivo é permitido
     * @param Int $size Tamanho do arquivo em bits
     * @return Boolean
     */
    private function checkSize(int $size)
    {return ($this->maxSize * (1024 * 1024)) >= $size;}

    /**
     * Move o arquivo da memória para o diretório especificado
     * @param String $tmpName Nome temporário do arquivo
     * @param String $file Nome do arquivo com caminho completo para onde vai ser movido
     * @return Boolean
     */
    private function moveFile(string $tmpName, string $file)
    {return move_uploaded_file($tmpName, $file);}

}
