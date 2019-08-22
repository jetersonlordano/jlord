<?php

/**
 * Faz gereciamento de hashing
 * @author Jeterson Lordano <jetersonlordano@gmail.com>
 */

class JCrypt
{
    /**
     * Prefixo padrão do salt
     */
    public static $saltPrefix = '2a';

    /**
     * Custo padrão da hashing (4-31)
     */
    public static $defaultCost = 8;

    /**
     * Tamanho máximo do salt
     */
    public static $saltLength = 22;


    /**
     * Cria uma String de hash para comparação
     * @param String $str String de criação da hash
     * @param Int $cost Custo da hash
     */
    public static function createHash($str, $cost = null)
    {
        if (!$cost) {$cost = self::$defaultCost;}
        $salt = self::generateRandomSalt(); // Salt
        $hashString = self::generateHashString((int) $cost, $salt); // Hash string
        return crypt($str, $hashString);
    }

    /**
     * Verifica se a hash é compativel com a senha
     * @param String $str String a ser comparada
     * @param String $hash Hash recuperada para comparação
     * @return Boolean 
     */
    public static function checkHash($str, $hash)
    {return (crypt($str, $hash) === $hash);}

    /**
     * Cria um salt de hash randomico
     * @return String
     */
    public static function generateRandomSalt()
    {
        $seed = uniqid(mt_rand(), true);
        $salt = base64_encode($seed);
        $salt = str_replace('+', '.', $salt);
        return substr($salt, 0, self::$saltLength);
    }

    /**
     * Gera uma String hash
     * @param Int $cost Custo da hash
     * @param String $salt
     * @return String
     */
    public static function generateHashString($cost, $salt)
    {return sprintf('$%s$%02d$%s$', self::$saltPrefix, $cost, $salt);}
}
