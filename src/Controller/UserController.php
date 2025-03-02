<?php

namespace src\Controller;

use src\Model\User;
use src\Service\JwtService;

class UserController
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

        if (empty($json) || !isset($json->username) || !isset($json->email) || !isset($json->password)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "code" => 1,
                "message" => "Missing required fields"
            ]);
            return;
        }

        $user = new User();
        $user->setUsername($json->username)
             ->setEmail($json->email)
             ->setPassword(password_hash($json->password, PASSWORD_BCRYPT));
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
            echo json_encode([
                "code" => 1,
                "message" => "POST method expected"
            ]);
            return;
        }

        $data = file_get_contents("php://input");
        $json = json_decode($data);

        if (empty($json) || !isset($json->email) || !isset($json->password)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "code" => 1,
                "message" => "Missing email or password"
            ]);
            return;
        }

        $user = User::SqlGetByMail($json->email);
        if ($user && password_verify($json->password, $user->getPassword())) {
            echo json_encode([
                "code" => 0,
                "message" => "Login successful",
                "user_id" => $user->getId()
            ]);
        } else {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode([
                "code" => 1,
                "message" => "Invalid email or password"
            ]);
        }
    }

    public function getUsers($connectedUserId)
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

        $users = User::SqlGetAllExcluding($connectedUserId);
        echo json_encode([
            "code" => 0,
            "users" => $users
        ]);
    }
}
