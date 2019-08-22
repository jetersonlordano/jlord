<?php

class Searcher
{
    private $result;

    public function __construct(string $srch, string $section, string $fields, string $table, string $terms, array $values)
    {
        $conn = new Conn();
        $conn->select($fields, $table, $terms, $values);
        $conn->exec();
        $this->result = $conn->fetchAll();

        // Recupera o id da busca se existir
        $srchObj = $this->getSrch($srch, $section);
        if ($srchObj) 
        {$this->updateSrch($srchObj, count($this->result));} 
        else {$this->saveSrch($srch, count($this->result), $section);}

    }

    public function getData()
    {return $this->result;}

    /**
     * Verifica se existe busca do termo e recupera o id
     * @param String $srch Termo para busca
     * @param String $section Seção onde ocorreu a busca
     * @return Int Retorna o id da busca se existir
     */
    private function getSrch(string $srch, string $section)
    {
        $FIX = TBSEARCHES[1];
        $schTerms = "WHERE {$FIX}term = :term AND {$FIX}section = :section LIMIT 1";
        $conn = new Conn();
        $conn->select('*', TBSEARCHES[0], $schTerms, ['term' => $srch, 'section' => $section]);
        $conn->exec();
        $result = $conn->fetchAll();
        return $result ? $result[0] : null;
    }

    /**
     * Cadastra a busca no banco de dados
     * @param String $srch termo da busca
     * @param Int $found Total de dados encontrados
     * @param String $section Seção onde ocorreu a busca
     * @return Boolean
     */
    private function saveSrch(string $srch, int $found, string $section)
    {
        $FIX = TBSEARCHES[1];
        $values = [
            $FIX . 'section' => $section,
            $FIX . 'term' => $srch,
            $FIX . 'found' => $found,
            $FIX . 'lastsearch' => date('Y-m-d H:i:s'),
        ];
        $conn = new Conn();
        $conn->insert(TBSEARCHES[0], $values);
        return $conn->exec();
    }

    /**
     * Atualiza busca no banco de dados
     * @param Array Busca encontrada no banco
     * @param Int $found Total de dados encontrados
     * @return Boolean
     */
    private function updateSrch(array $srch, int $found)
    {
        $FIX = TBSEARCHES[1];
        $terms = "{$FIX}count = :count, {$FIX}found = :found, {$FIX}lastsearch = :lastsearch WHERE {$FIX}id = :id LIMIT 1";
        $values = [
            'count' => $srch[$FIX . 'count'] + 1,
            'found' => $found,
            'lastsearch' => date('Y-m-d H:i:s'),
            'id' => $srch[$FIX . 'id'],
        ];
        $conn = new Conn();
        $conn->update(TBSEARCHES[0], $terms, $values);
        return $conn->exec();
    }
}
