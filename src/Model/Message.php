<?php

namespace src\Model;

class Message
{
    private ?int $id = null;
    private int $sender_id;
    private int $receiver_id;
    private string $content;
    private ?string $timestamp = null;
    private bool $is_read = false;

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of sender_id
     */
    public function getSenderId(): int
    {
        return $this->sender_id;
    }

    /**
     * Set the value of sender_id
     */
    public function setSenderId(int $sender_id): self
    {
        $this->sender_id = $sender_id;

        return $this;
    }

    /**
     * Get the value of receiver_id
     */
    public function getReceiverId(): int
    {
        return $this->receiver_id;
    }

    /**
     * Set the value of receiver_id
     */
    public function setReceiverId(int $receiver_id): self
    {
        $this->receiver_id = $receiver_id;

        return $this;
    }

    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of timestamp
     */
    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    /**
     * Set the value of timestamp
     */
    public function setTimestamp(?string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get the value of is_read
     */
    public function isIsRead(): bool
    {
        return $this->is_read;
    }

    /**
     * Set the value of is_read
     */
    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public static function SqlAdd(Message $message): int
    {
        $requete = BDD::getInstance()->prepare("INSERT INTO messages (Sender_ID, Receiver_ID, Content) VALUES(:sender_id, :receiver_id, :content)");
        $requete->execute([
            "sender_id" => $message->getSenderId(),
            "receiver_id" => $message->getReceiverId(),
            "content" => $message->getContent()
        ]);
        return BDD::getInstance()->lastInsertId();
    }

    public static function SqlGetByUsers(int $user1_id, int $user2_id): array
    {
        $requete = BDD::getInstance()->prepare("
            SELECT * FROM messages
            WHERE (Sender_ID = :user1_id AND Receiver_ID = :user2_id)
               OR (Sender_ID = :user2_id AND Receiver_ID = :user1_id)
            ORDER BY Timestamp ASC
        ");
        $requete->execute([
            "user1_id" => $user1_id,
            "user2_id" => $user2_id
        ]);
        return $requete->fetchAll(\PDO::FETCH_ASSOC);
    }
}
