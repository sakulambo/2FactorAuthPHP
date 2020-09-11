<?php


namespace App\Utils;


use App\Entity\MobileAuthCode;

class MobileAuthCodeAuthenticator
{

    /**
     * @param MobileAuthCode $authenticationCode
     * @return false|int|string
     * @throws \Exception
     */
    public static function checkAuthenticationCode(MobileAuthCode $authenticationCode){
        $createdAt = $authenticationCode->getCreatedAt();
        $now = new \DateTime('now');

        $dateDiffInterval = $createdAt->diff($now);
        if ($dateDiffInterval->i < 5){
            if(!$authenticationCode->getExpired()){
                return true;
            }else{
                throw new \Exception('Authorization code has expired.',500);
            }
        }else{
            throw new \Exception('Authorization code has expired.',500);
        }

    }
}