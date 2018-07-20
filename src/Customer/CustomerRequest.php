<?php

namespace App\Customer;

use DateTime;

use Symfony\Component\Validator\Constraints as Assert;


class CustomerRequest
{


    /**
     * @Assert\NotBlank(message="Saisissez votre prénom")
     */
    private $name;

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
     * CustomerRequest constructor.
     * @param $roles
     */
    public function __construct(string $role = 'ROLE USER')
    {
        $this->roles[] = $role;
    }


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCenterId(): ?int
    {
        return $this->center_id;
    }

    public function setCenterId(int $center_id): self
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

    public function getUsername()
    {
        return $this->getNickname();
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
