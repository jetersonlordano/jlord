<?php

/**
 * Cria e comprime imagem
 * Gera miniatua para cacheDIr
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class Thumb
{

    /**
     * Cria uma nova miniatura de uma imagem
     * @param String $src URL da imagem online
     * @param String $width largura da nova imagem
     * @param String $quality Qualidade da compressão em porcentagem
     * @param String $time tempo de modificação
     * @param String $path pasta dentro do baseDir para onde a imagem vai - padrão 'home'
     * @param String $default imagem padrão
     */
    public static function Nail($src, $width, $quality = 75, $time = null, $path = null, $default = null, $back = 0)
    {
        $quality = (int) !$quality ? 75 : $quality;
        $path = (string) !$path ? 'home' : $path;
        $time = (string) !$time ? time() : date('U', strtotime($time));
        $default = (string) !$default ? IMAGE : $default;
        $backDir = '';

        // Verifica se a imagem existe e o MIMEType é permitido
        $target = self::getImage($src);

        for ($b = 0; $b < $back; $b++) {
            $backDir .= '..' . DS;
        }

        // Verificar ou cria os diretório
        $targetDir = self::checkDir($backDir . CACHEDIR, $path);

        if ($target && $targetDir) {
            $thumbNail = self::getThumb($src, $target, $targetDir, $width, $quality, $time);
            $thumbNail = str_replace(['../'], '', $thumbNail);
            return $thumbNail ? HOME . '/' . $thumbNail : null;
        } else {return $default;}
    }

    /**
     * Usa biblioteca GD para criar uma nova imagem
     * @param String $src URL da imagem original
     * @param Objeto $target Objeto target
     * @param String $targetDir Diretório para onde a imagem vai
     * @param String $width Nova largura da imagem
     * @param String $quality Qualidade da compressão
     * @param String $time Último update
     * @return String Caminho da imagem criada
     */
    private static function getThumb($src, $target, $targetDir, $width, $quality, $time)
    {
        // Largura e altura da imagem original
        list($imgW, $imgH) = $target;

        // Calcula a largura e altura do novo arquivo
        $width = (int) !$width ? $imgW : $width;
        $height = (int) ($imgH * $width) / $imgW;

        $imageInfo = self::getInfo($src, $width, $height);
        $targetMove = $targetDir . DS . $imageInfo['name'] . $imageInfo['type'];

        if (self::allowCreate($targetMove, $time)) {

            $imageCreate = self::imageFrom($src, $target['mime']);

            // Cria a nova imagem true color
            $imageTrueColor = imagecreatetruecolor($width, $height);

            // Define o modo de mesclagem
            imagealphablending($imageTrueColor, false);

            // Define transparencia da imagem
            imagesavealpha($imageTrueColor, true);

            // Faz uma cópia da imagem com uma nova resolução
            imagecopyresampled($imageTrueColor, $imageCreate, 0, 0, 0, 0, $width, $height, $imgW, $imgH);

            // Cria nova imagem
            if (self::createImage($targetMove, $target['mime'], $imageTrueColor, $quality)) {
                return str_replace(DS, '/', $targetMove);
            }

            // Limpa as imagens criadas da memória
            imagedestroy($imageCreate);
            imagedestroy($imageTrueColor);

        } else {return str_replace(DS, '/', $targetMove);}

    }

    /**
     * Recupera nome e extensão da imagem
     * @param String $src URL da imagem
     * @param Int $width Largura da imagem
     * @param Int $height Altura da imagem
     * @return Array
     */
    private static function getInfo($src, $width, $height)
    {
        $imageInfo = [];
        $width = round($width);
        $height = round($height);
        $name = explode('/', $src);
        $name = strtolower(end($name));
        $type = explode('.', $name);
        $imageInfo['type'] = '.' . end($type);
        $imageInfo['name'] = str_replace(['.jpg', '.png', '.gif', '.jpeg', '.pjpeg', '.x-png'], '', $name) . "-{$width}x{$height}";
        return $imageInfo;
    }

    /**
     * Verifica permissão para criar a imagem
     * Verifica se o arquivo já existe ou se tempo de modificação e menor que o ultimo update
     * @param String $file Caminho da imagem cacheDir
     * @param String $time Data ou Time para modificação
     * @return Boolean
     */
    private static function allowCreate($file, $time = null)
    {return (!file_exists($file) || $time > filectime($file));}

    /**
     * Cria um objeto imagecreatefrom da GD
     * @param String $src caminho do imagem original
     * @param String $type MIMEType da imagem original
     * @return Objeto GD imagecreatefrom
     */
    private static function imageFrom($src, $type)
    {
        switch ($type) {
            case 'image/png':
            case 'image/x-png':
                return imagecreatefrompng($src);
                break;
            case 'image/gif':
                return imagecreatefromgif($src);
                break;
            default:
                return imagecreatefromjpeg($src);
        }
    }

    /**
     * Cria uma nova imagem com GD e seta qualidade de compressão
     * @param String $source caminho da imagem
     * @param String $type MIMEType da imagem original
     * @param Objeto $imageTrueColor Objeto imagecreatetruecolor
     * @param Int $quality Qualidade da compressão
     * @return Boolean
     */
    private static function createImage($source, $type, $imageTrueColor, $quality)
    {
        switch ($type) {
            case 'image/png':
            case 'image/x-png':
                return imagepng($imageTrueColor, $source, 9);
                break;
            case 'image/gif':
                return imagegif($imageTrueColor, $source);
                break;
            default:
                return imagejpeg($imageTrueColor, $source, $quality);
                break;
        }
    }

    /**
     * Verifica se a imagem original exite
     * Verifica o MIMEType da imagem original
     * @param String $image URL da imagem original
     * @return Array getimagesize da imagem
     */
    private static function getImage($image)
    {
        $MIMETypes = ['image/jpe', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/x-png', 'image/gif'];

        // Verifica se o arquivo existe
        $image = str_replace('/', DS, $image);
        $image = file_exists($image) && !is_dir($image) ? getimagesize($image) : null;

        // Verifica se o MIMEType está na lista de permitidos
        return in_array($image['mime'], $MIMETypes) ? $image : null;
    }

    /**
     * Verificar ou cria os diretórios
     * @param String $baseDir Diretório base - padrão 'cacheDir'
     * @param String $dir Diretório onde a imagem será enviada
     * @return String Caminho do diretório
     */
    private static function checkDir($baseDir, $dir)
    {
        if (!file_exists($baseDir)) {
            if (mkdir($baseDir)) {return FNC::createDir($baseDir, $dir . DS . 'images');}
        } else {return FNC::createDir($baseDir, $dir . DS . 'images');}
    }
}
