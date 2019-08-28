<?php

/**
 * Métodos estáticos genéricos
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class FNC
{
    /**
     * Converte dados de um array em palavras chaves com ### e faz a substituição dos valores
     * @param Array $values dados para substituição de chaves
     * @param String $file arquivo para substituição
     * @return String Retorna os dados substituidos
     */
    public static function view(array $values, string $file = null, string $strTpl = null)
    {
             
        // Template
        $file = file_exists($file) ? file_get_contents($file) : null;
        $tpl = $file ?? $strTpl;
      
        // Transforma as chaves do array em chaves especiais para sibstituição
        $links = '#' . implode('#&#', array_keys($values)) . '#';

        // Converte os chaves especiais em array
        $keys = explode('&', $links);

     

        // Substitui as chaves especias pelos dados do array
        return $tpl ? str_replace($keys, array_values($values), $tpl) : null;
    }

    /**
     * Cria um objeto JSON de notificação
     * @param String $msg Messagem da notificação
     * @param String $type Tipo de notificação - info, dange, warning ou success
     * @return Objeto JSON
     */
    public static function notify(string $msg, string $type = 'info')
    {return json_encode(['action' => 'notify', 'type' => "{$type}", 'message' => "{$msg}"]);}

    /**
     * Redireciona para o link correto se estiver faltando partes
     */
    public static function vldLink(string $linkSingle, string $correctLink, $section = null)
    {
        if ($linkSingle != $correctLink) {self::redirect(HOME . (!$section ? null : '/' . $section) . '/' . $correctLink);die;}
        return !0;
    }

    /**
     * Atualiza Views da página no banco de dados
     * Controla view única
     */
    public static function updateView($ID, $views, $table)
    {
        if (!isset($_GET['jview'])) {
            $FIX = $table[1];
            $terms = "{$FIX}views = :views, {$FIX}lastview = :lastview WHERE {$FIX}id = :id";
            $values = ['views' => ($views + 1), 'lastview' => date('Y-m-d H:i:s'), 'id' => $ID];
            $up = new Conn();
            $up->update($table[0], $terms, $values);

            // Adiciona página na sessão  para controle de única view por sessão
            $pageId = $table[0] . $ID;
            $pagesAccessed = md5('pagesaccessed' . TITLE);
            if (!isset($_SESSION[$pagesAccessed])) {$_SESSION[$pagesAccessed] = [];}
            if (!in_array($pageId, $_SESSION[$pagesAccessed])) {
                array_push($_SESSION[$pagesAccessed], $pageId);
                $up->exec();
            }
        }
    }

    /**
     * Converte uma string com palavras separadas por virgula em links
     * @param String $str
     * @param String $url URL base do link
     * @param Boolean $uppercase Se verdadeiro converte em maiúsculas
     * @return String
     */
    public static function inLink(string $str, string $url, bool $uppercase = false)
    {
        if (empty($str)) {return null;}

        $expTags = explode(',', trim($str));
        $links = '';

        foreach ($expTags as $vlr) {
            $tag = self::convertStr($vlr);
            $tagName = $uppercase ? strtoupper(trim($vlr)) : trim($vlr);
            $links .= "<a href=\"{$url}/{$tag}\">{$tagName}</a>";
        }
        return $links;
    }

    /**
     * Sistema de páginação
     * @param Int $qtd Quantidade de posts encontrados por página
     * @param Int $total Total de posts encontrado no termo
     * @param Array $pgn Objeto da função Check::pgn
     * @param Int $numChildren Número de filhos por página
     * @param String $link Inicio do link
     * @return Array ['info', 'linkprev', 'linknext', 'titleprev', 'titlenext']
     */
    public static function pagination(int $qtd, int $total, array $pgn, int $numChildren, string $link)
    {
        // Total de páginas
        $totalPg = ceil($total / $numChildren);

        $pagination = [
            'info' => 'Não encontrado',
            'linkprev' => 'javascript:void(0);',
            'linknext' => 'javascript:void(0);',
            'titleprev' => 'Inativo',
            'titlenext' => 'Inativo',
        ];

        if ($pgn['page'] > 1) {
            $pagination['linkprev'] = $link . ($pgn['page'] - 1);
            $pagination['titleprev'] = "Ir para página " . ($pgn['page'] - 1);
        }

        if ($pgn['page'] < $totalPg) {
            $pagination['linknext'] = $link . ($pgn['page'] + 1);
            $pagination['titlenext'] = "Ir para página " . ($pgn['page'] + 1);
        }

        if ($total > 0) {
            $min = $pgn['init'] + 1;
            $max = $pgn['init'] + $qtd;
            $pagination['info'] = "{$min} - {$max} de {$total}";
        }

        return $pagination;
    }

    /**
     * Substitui caracteres especiais por caracteres simples ou espaços
     * @param String $str
     * @return String
     */
    public static function removeAccents(string $str)
    {
        $chars = [];
        $chars['a'] = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿ"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        $chars['b'] = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyyby                                 ';
        $result = strtr(utf8_decode($str), utf8_decode($chars['a']), $chars['b']);
        $result = strip_tags(trim($result));
        return strtolower(utf8_encode($result));
    }

    /**
     * Converte textos para o formato de link amigáveil e busca
     * @param String $str
     * @param String $type Tipo de string - link, search ou text
     * @return String
     */
    public static function convertStr(string $str, string $type = 'link')
    {
        $str = strip_tags(trim(FNC::removeAccents($str)));
        switch (strtolower($type)) {
            case 'search':
                return strtolower(str_replace([' ', '  ', '-', '--'], '+', $str));
                break;
            case 'text':
                return strtolower(str_replace(['-', '+', '--', '-+'], ' ', $str));
                break;
            default:
                $str = strtolower(str_replace([' ', '  ', '--', '+'], '-', $str));
                return strtolower(str_replace([' ', '  ', '--', '+'], '-', $str));
        }
    }

    /**
     * Remonta um array de imagens geralmente vindo de um formulário html
     * @param Array $array Array de imagens todo bagunçado
     * @return Array Array de imagens organizado
     */
    public static function convertArray(array $array)
    {
        $imagens = [];
        $total = ($array['error'][0] == 0) ? count($array['tmp_name']) : 0;
        for ($i = 0; $i < $total; $i++) {
            $file = array();
            $file['name'] = strtolower($array['name'][$i]);
            $file['type'] = $array['type'][$i];
            $file['tmp_name'] = $array['tmp_name'][$i];
            $file['error'] = $array['error'][$i];
            $file['size'] = $array['size'][$i];
            $imagens[$i] = $file;
        }
        return $imagens;
    }

    /**
     * Limpa erros no código html e converte os caracteres especiais
     * @param String $str Código HTML sujo
     * @param Boolean $specialchars Converter os caracters html para especiais
     * @return String Texto convertido
     */
    public static function convertTags($str, $specialchars = false)
    {
        $replaceWhite = ['<p><br></p>', '<div><br></div>', '<p></br></p>', '<div></br></div>', '<h4><br></h4>', '<h3><br></h3>', '<h4></br></h4>', '<h3></br></h3>', '<p></p>', '<div></div>', '<h4></h4>', '<h3></h3>', '<pre></pre>', '<pre><br></pre>', '<pre></br></pre>', '<p><span style="font-size: 1em;"></span></p>', '<p><span style="font-size: 1.1em;"></span></p>', ' style="font-size: 1em;"', ' style="font-size: 1.1em;"', '><span', '/span><'];

        $clearStr = str_replace([' class>', ' class="">'], '>', $str);
        $clearStr = str_replace(['<div><ol>', '<p><ol>'], '<ol>', $clearStr);
        $clearStr = str_replace(['</ol></div>', '</ol></p>'], '</ol>', $clearStr);

        $clearStr = str_replace($replaceWhite, '', $clearStr);
        $clearStr = str_replace('<br></', '</', $clearStr);
        $clearStr = str_replace([], '</', $clearStr);

        // Converte <div> para <p>
        $clearStr = str_replace('<div', '<p', $clearStr);
        $clearStr = str_replace('</div>', '</p>', $clearStr);

        $clearStr = $specialchars ? htmlspecialchars($clearStr) : $clearStr;
        return $clearStr;

    }

    /**
     * Coloca um limite na quantidade de palavras que o conteiner pode ter
     * @param String $str Texto a ser modificado
     * @param Int $limit Numero de palavras permitido
     * @return String
     */
    public static function limitText(string $str, int $limit)
    {
        $text = '';
        $expStr = explode(' ', trim($str));
        $totalWords = count($expStr);

        for ($i = 0; $i < $limit && $i < $totalWords; $i++) {$text .= $i >= ($limit - 1) ? $expStr[$i] : $expStr[$i] . ' ';}
        return $text;
    }

    /**
     * Alterna a sequencia de dados em ASC ou DESC
     * @param String $set
     * @param String $order
     * @param String $seq
     * @param String $link
     */
    public static function altSeq(string $set, string $order, string $seq, string $link)
    {
        $result = $link . "&order={$set}&seq=ASC";
        return ($set == $order) ? $link . "&order={$order}&seq=" . (($seq == 'DESC') ? 'ASC' : 'DESC') : $result;
    }

    /**
     * Faz calculo de idade usando dade de hoje
     * @param String $data Data para calculo com hoje
     * @param String $format Formato da resposta do calculo
     * @return String Retorna idade calculada
     */
    public static function calcDate($data, $format)
    {
        $date = new DateTime((string) $data);
        $interval = $date->diff(new DateTime(date('Y-m-d H:i:s')));
        return $interval->format($format);
    }

    /**
     * Cria um array de arquivos de um diretório
     * @param String $dir Diretório a ser varrido
     * @param String $types Tipos de arquivos permitidos
     * @return Array Retorna um array de arquivos ou pastas
     */
    public static function listFiles(string $dir, $types = '*')
    {return file_exists($dir) ? glob("{$dir}\{{$types}}", GLOB_BRACE) : [];}

    /**
     * Redireciona usando javascript
     * @param String $url Link para redirecionamento
     */
    public static function redirect($url)
    {echo "<script> window.location.href = '{$url}'; </script>";die;}

    /**
     * Deleta um arquivo de um diretório
     * @param String $file Caminho completo do arquivo a ser deletado
     * @return Boolean Returna verdadeiro ou falso de acordo com o processo
     */
    public static function delFile(string $file)
    {return (file_exists($file) && !is_dir($file)) ? unlink($file) : !0;}

    /**
     * Exclui os arquivos não utilizados no conteúdo para manter a pasta limpa
     * @param String $str Conteúdo da postagem
     * @param String $dir Diretório onde os arquivo estão
     */
    public static function clearPath($str, $dir)
    {
        $files = FNC::listFiles($dir);
        foreach ($files as $key) {
            $expKey = explode('/', str_replace(DS, '/', $key));
            if (strpos($str, end($expKey)) == false) {FNC::delFile($dir . '/' . end($expKey));}
        }
    }

    /**
     * Cria um ou mais diretório de acordo com o caminho passodo no paramentro
     * @param String $baseDir Diretório base onde deve ser criado
     * @param String $dirName Nome do diretório a ser criado
     * @return Boolean Retorna verdadeiro ou falso de acordo com o processo
     */

    public static function createDir(string $baseDir, string $dirName = null)
    {
        if (is_dir($baseDir)) {
            $dirName = ($dirName == DS || $dirName == '/') ? null : $dirName;
            $dirName = ($dirName != null ? $baseDir . DS . $dirName : $baseDir . DS . date("dmy") . '-' . time() . rand(0, 1000));
            $dirName = str_replace([DS . DS, '//'], DS, $dirName);
            if (!file_exists($dirName) && !is_dir($dirName)) {mkdir($dirName, 0777, true);}
            return $dirName;
        }
    }

    /**
     * Limpar e excluir um diretório
     * @param String $dir Caminho completo do diretório alvo
     * @param Boolean $delDir Se setado como true também excluir o diretório alvo
     * @return Boolean
     */
    public static function cleanDir(string $dir, bool $delDir = false)
    {
        if ((file_exists($dir) && is_dir($dir))) {
            $objects = array_diff(scandir($dir), array('.', '..'));
            foreach ($objects as $vlr) {$obj = $dir . DIRECTORY_SEPARATOR . $vlr; (filetype($obj) == 'dir') ? FNC::cleanDir($obj, !0) : unlink($obj);}
            reset($objects);
            if ($delDir) {rmdir($dir);}
        }
        return !0;
    }
}
