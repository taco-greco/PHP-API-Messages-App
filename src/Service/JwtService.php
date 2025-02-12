<?php

namespace src\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use src\Model\Hobby;

class JwtService
{
    public static String $secretKey = "cesi";

    public static function createToken(array $datas) : String{
        //$datas = Données personnelles qui seront dans le payload du JWT
        $issuedAt = new \DateTimeImmutable();
        $expire = $issuedAt->modify("+6 minutes")->getTimestamp();
        $serverName = "hobbymatch.localhost";

        $data = [
            "iat" => $issuedAt->getTimestamp(),
            "iss" => $serverName,
            "nbf" => $issuedAt->getTimestamp(),
            "exp" => $expire,
            "data" => CryptService::encrypt(json_encode($datas))
        ];

        $jwt = JWT::encode(
            $data,
            self::$secretKey,
            "HS256"
        );
        return $jwt;
    }


    public static function checkToken() :array
    {
        if(!preg_match("/Bearer\s(\S+)/",$_SERVER["HTTP_AUTHORIZATION"],$matches)){
            return [
                "status" => "error",
                "message" => "Bearer token is not here"
            ];
        }

        $jwt = $matches[1];
        if(!$jwt){
            return [
                "status" => "error",
                "message" => "Aucun jeton n'a pu être extrait de l'entête Bearer"
            ];
        }

        try{
            $token = JWT::decode($jwt,new Key(self::$secretKey,'HS256'));
        }catch (\Exception $exception){
            return [
                "status" => "error",
                "message" => "les données du jeton ne sont pas compatibles : {$exception->getMessage()}"
            ];
        }

        $now = new \DateTimeImmutable();
        $serverName = "192.168.1.15:8000";
        if($token->iss !== $serverName || $token->exp < $now->getTimestamp() || $token->nbf > $now->getTimestamp()){
            return [
                "status" => "error",
                "message" => "Nom du serveur ou date expiration invalide"
            ];
        }

        return [
            "status" => "success",
            "message" => "JWT Valide",
            "data" => json_decode(CryptService::decrypt($token->data))
        ];
    }
}
