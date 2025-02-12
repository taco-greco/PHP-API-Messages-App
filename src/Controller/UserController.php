<?php

namespace src\Controller;

use src\Model\User;
use src\Service\JwtService;

class UserController extends AbstractController
{
    public function create()
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
}
