<?php
namespace App\Utils;

class MobileAuthCodeGenerator
{

    /**
     * @return string
     */
    public static function generateUniqueCode(){
        $charDictionary = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        shuffle($charDictionary);
        $code = '';
        foreach (array_rand($charDictionary, 4) as $k) $code .= $charDictionary[$k];

        return $code;
    }

}