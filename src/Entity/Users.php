<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields="mail", message="Addresse eMail déjà utilisé.")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Role;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     */
    private $idDiscord;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $villeMairie;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = 'ROLE_USER';

        return $this;
    }

    public function getIdDiscord(): ?string
    {
        return $this->idDiscord;
    }

    public function setIdDiscord(?string $idDiscord): self
    {
        $this->idDiscord = $idDiscord;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getVilleMairie(): ?string
    {
        return $this->villeMairie;
    }

    public function setVilleMairie(object $villeMairie): self
    {
		$convert = $villeMairie->getVille();
        $this->villeMairie = $convert;

        return $this;
    }

	public function setRoles(string $Roles): self
    {
        $this->villeMairie = $Roles;

        return $this;
    }

	public function getRoles(): array
    {
        //$roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

	public function getUsername()
    {
        return $this->mail;
    }

	public function getSalt()
    {
        return null;
    }

	public function eraseCredentials()
    {
		return null;
    }
}
