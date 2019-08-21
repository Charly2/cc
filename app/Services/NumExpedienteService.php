<?php

namespace App\Services;

/**
 * Class NumExpedienteService
 * @package App\Services
 */
class NumExpedienteService
{
    /**
     * @var array
     */
    protected static $vowels = ['a', 'e', 'i', 'o', 'u', 'y'];

    /**
     * @param $string
     * @param null $id
     * @return string
     */
    public static function generate($string, $id = null){
        $results = '';
        $id++;
        preg_match_all('/[A-Z][a-z]*/', strtoupper($string), $m);
        foreach($m[0] as $substring){
            $substring = str_replace(self::$vowels, '', strtoupper($substring));
            $results .= $substring;
        }
        $results = str_pad($id, 6, 0, STR_PAD_LEFT);

        return $results;
    }
}