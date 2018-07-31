<?php

namespace App\Customer;

use App\Entity\Center;
use DateTime;

use Symfony\Component\Validator\Constraints as Assert;


class CustomerRequest
{


    /**
     * @Assert\NotBlank(message="Saisissez votre prénom")
     */
    private $username;

    /**
     * @Assert\NotBlank(message="Saisissez votre nom d'utilisateur")
     */
    private $nickname;

    /**
     * @Assert\NotBlank(message="Saisissez votre adresse")
     */
    private $adress;

    /**
     * @Assert\NotBlank(message="Saisissez votre téléphone")
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="Saisissez votre date de naissance")
     */
    private $birthdate;

    private $center_id;

    private $card_id;
    /**
     * @Assert\NotBlank(message="Saisissez votre mot de passe")
     */
    private $password;

    private $roles;
    /**
     * @Assert\NotBlank(message="Saisissez votre email")
     */
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }


    /**
     * CustomerRequest constructor.
     * @param $roles
     */
    public function __construct(string $role = 'ROLE_USER')
    {
        $this->roles[] = $role;
    }


    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $name): self
    {
        $this->username = $name;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getCenterId()
    {
        return $this->center_id;
    }

    public function setCenterId(Center $center_id): self
    {
        $this->center_id = $center_id;

        return $this;
    }

    public function getCardId(): ?int
    {
        return $this->card_id;
    }

    public function setCardId(?int $card_id): self
    {
        $this->card_id = $card_id;

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

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return null;
    }


    public function eraseCredentials()
    {

    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
