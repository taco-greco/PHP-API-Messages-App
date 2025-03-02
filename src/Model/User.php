<?php

namespace src\Model;

class User
{
    private ?int $Id = null;
    private string $Username;
    private string $Email;
    private string $Password;
    private ?string $Created_At = null;
    private ?string $Last_Online = null;

    /**
     * Get the value of Id
     */
    public function getId(): ?int
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     */
    public function setId(?int $Id): self
    {
        $this->Id = $Id;
        return $this;
    }

    /**
     * Get the value of Username
     */
    public function getUsername(): string
    {
        return $this->Username;
    }

    /**
     * Set the value of Username
     */
    public function setUsername(string $Username): self
    {
        $this->Username = $Username;
        return $this;
    }

    /**
     * Get the value of Email
     */
    public function getEmail(): string
    {
        return $this->Email;
    }

    /**
     * Set the value of Email
     */
    public function setEmail(string $Email): self
    {
        $this->Email = $Email;
        return $this;
    }

    /**
     * Get the value of Password
     */
    public function getPassword(): string
    {
        return $this->Password;
    }

    /**
     * Set the value of Password
     */
    public function setPassword(string $Password): self
    {
        $this->Password = $Password;
        return $this;
    }

    /**
     * Get the value of Created_At
     */
    public function getCreatedAt(): ?string
    {
        return $this->Created_At;
    }

    /**
     * Set the value of Created_At
     */
    public function setCreatedAt(?string $Created_At): self
    {
        $this->Created_At = $Created_At;
        return $this;
    }

    /**
     * Get the value of Last_Online
     */
    public function getLastOnline(): ?string
    {
        return $this->Last_Online;
    }

    /**
     * Set the value of Last_Online
     */
    public function setLastOnline(?string $Last_Online): self
    {
        $this->Last_Online = $Last_Online;
        return $this;
    }

    public static function SqlGetAll(): array
    {
        $requete = BDD::getInstance()->prepare("SELECT ID, Username, Email FROM users");
        $requete->execute();
        return $requete->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function SqlAdd(User $user): int
    {
        $requete = BDD::getInstance()->prepare("INSERT INTO users (Username, Email, Password) VALUES(:Username, :Email, :Password)");
        $requete->execute([
            "Username" => $user->getUsername(),
            "Email" => $user->getEmail(),
            "Password" => $user->getPassword()
        ]);
        return BDD::getInstance()->lastInsertId();
    }

    public static function SqlGetByMail(string $email): ?User
    {
        $requete = BDD::getInstance()->prepare("SELECT * FROM users WHERE Email=:email");
        $requete->execute([
            "email" => $email
        ]);
        $datas = $requete->fetch(\PDO::FETCH_ASSOC);
        if ($datas != false) {
            $user = new User();
            $user->setId($datas["ID"])
                ->setUsername($datas["Username"])
                ->setEmail($datas["Email"])
                ->setPassword($datas["Password"])
                ->setCreatedAt($datas["Created_At"])
                ->setLastOnline($datas["Last_Online"]);
            return $user;
        }
        return null;
    }
}