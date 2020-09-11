<?php


namespace App\Utils;


class MobileAuthAuthenticator
{

    /**
     * @param $mobilePhone
     * @return false|int|string
     * @throws \Exception
     */
    public static function checkMobilePhoneFormat($mobilePhone){
        if (strlen(trim($mobilePhone)) == 9){
            $spainMobilePhonePattern = "/^[679]{1}[0-9]{8}$/";
            return preg_match($spainMobilePhonePattern, trim($mobilePhone));
        }

        throw new \Exception('Invalid mobile phone extension (Must contain 9 numbers)',500);

    }
}