<?php

namespace src\Controller;

use src\Model\User;
use src\Service\JwtService;

class UserController extends AbstractController
{
    public function register()
    {
        header("Content-Type: application/json; charset=utf-8");

        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "code" => 1,
                "message" => "POST method expected"
            ]);
            return;
        }

        $data = file_get_contents("php://input");
        $json = json_decode($data);

        if (empty($json) || !isset($json->mail) || !isset($json->password)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "code" => 1,
                "message" => "Missing required fields"
            ]);
            return;
        }

        $user = new User();
        $hashpass = password_hash($json->password, PASSWORD_BCRYPT, ["cost" => 12]);
        $user->setUsername($json->mail)
            ->setPassword($hashpass);
        $id = User::SqlAdd($user);

        echo json_encode([
            "code" => 0,
            "message" => "User created successfully",
            "user_id" => $id
        ]);
    }

    public function login()
    {
        header("Content-Type: application/json; charset=utf-8");

        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            header("HTTP/1.1 405 Method Not Allowed");
            return json_encode([
                "code" => 1,
                "Message" => "Post Attendu"
            ]);
        }
        // Récuperation du body en String
        $data = file_get_contents("php://input");
        //Conversion du String en JSON
        $json = json_decode($data);

        if (empty($json)) {
            header("HTTP/1.1 403 Forbidden");
            return json_encode([
                "code" => 1,
                "Message" => "Il faut des données"
            ]);
        }

        if (!isset($json->mail) || !isset($json->password)) {
            header("HTTP/1.1 403 Forbidden");
            return json_encode([
                "code" => 1,
                "Message" => "Il manque le mail ou le password"
            ]);
        }
        // Récupérer les info de l'utilisateur par son mail
        $user = User::SqlGetByMail($json->mail);
        if ($user == null) {
            header("HTTP/1.1 403 Forbidden");
            return json_encode([
                "code" => 1,
                "Message" => "User inexistant"
            ]);
        }
        // Comparer le mot de passe avec celui hashé en bdd
        if (!password_verify($json->password, $user->getPassword())) {
            header("HTTP/1.1 403 Forbidden");
            return json_encode([
                "code" => 1,
                "Message" => "Mot de passe invalid"
            ]);
        }
        // Return JWT
        $token = JwtService::createToken([
            "username" => $user->getUsername(),
        ]);

        return json_encode($token);
    }

    public function getUsers()
    {
        header("Content-Type: application/json; charset=utf-8");

        if ($_SERVER["REQUEST_METHOD"] != "GET") {
            header("HTTP/1.1 405 Method Not Allowed");
            echo json_encode([
                "code" => 1,
                "message" => "GET method expected"
            ]);
            return;
        }

        $users = User::SqlGetAll();
        echo json_encode([
            "code" => 0,
            "users" => $users
        ]);
    }
}
