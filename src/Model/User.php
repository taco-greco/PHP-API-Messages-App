<?php

namespace src\Model;

class User
{
    private ?int $Id = null;
    private String $Email;
    private String $Password;
    private array $Roles;


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
     * Get the value of Email
     */
    public function getEmail(): String
    {
        return $this->Email;
    }

    /**
     * Set the value of Email
     */
    public function setEmail(String $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    /**
     * Get the value of Password
     */
    public function getPassword(): String
    {
        return $this->Password;
    }

    /**
     * Set the value of Password
     */
    public function setPassword(String $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * Get the value of Roles
     */
    public function getRoles(): array
    {
        return $this->Roles;
    }

    /**
     * Set the value of Roles
     */
    public function setRoles(array $Roles): self
    {
        $this->Roles = $Roles;

        return $this;
    }

    public static function SqlAdd(User $user): int
    {
        $requete = BDD::getInstance()->prepare("INSERT INTO users (Email, Password,
NomPrenom, Roles) VALUES(:Email, :Password, :NomPrenom, :Roles)");
        $requete->execute([
            "Email" => $user->getEMail(),
            "Password" => $user->getPassword(),
            "NomPrenom" => "Olivier Carglass", //Prévoir un champ dans le formulaire pour çaà l'avenir
            "Roles" => json_encode($user->getRoles())
        ]);
        return BDD::getInstance()->lastInsertId();
    }

    public static function SqlGetByMail(string $mail): ?User
    {
        $requete = BDD::getInstance()->prepare("SELECT * FROM users WHERE Email=:mail");
        $requete->execute([
            "mail" => $mail
        ]);
        $datas = $requete->fetch(\PDO::FETCH_ASSOC);
        if ($datas != false) {
            $user = new User();
            $user->setId($datas["Id"])
                ->setEMail($datas["Email"])
                ->setPassword($datas["Password"])
                ->setRoles(json_decode($datas["Roles"]));
            return $user;
        }
        return null;
    }
}
