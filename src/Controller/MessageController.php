<?php

namespace src\Controller;

use src\Model\Message;

class MessageController
{
    public function send()
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

        if (empty($json) || !isset($json->sender_id) || !isset($json->receiver_id) || !isset($json->content)) {
            header("HTTP/1.1 400 Bad Request");
            echo json_encode([
                "code" => 1,
                "message" => "Missing required fields"
            ]);
            return;
        }

        $message = new Message();
        $message->setSenderId($json->sender_id)
            ->setReceiverId($json->receiver_id)
            ->setContent($json->content);
        $id = Message::SqlAdd($message);

        echo json_encode([
            "code" => 0,
            "message" => "Message sent successfully",
            "message_id" => $id
        ]);
    }


    public function getMessages($user1_id = null, $user2_id = null)
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

    if ($user1_id === null || $user2_id === null) {
        header("HTTP/1.1 400 Bad Request");
        echo json_encode([
            "code" => 1,
            "message" => "Missing user1_id or user2_id"
        ]);
        return;
    }

    // Fetch messages between these two users
    $messages = Message::SqlGetByUsers($user1_id, $user2_id);

    echo json_encode([
        "code" => 0,
        "messages" => $messages
    ]);
}

}
