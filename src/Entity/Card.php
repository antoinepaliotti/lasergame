<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CardRepository")
 */
class Card
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customer_nickname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $card_number;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCustomerNickname()
    {
        return $this->customer_nickname;
    }

    /**
     * @param mixed $customer_nickname
     */
    public function setCustomerNickname($customer_nickname): void
    {
        $this->customer_nickname = $customer_nickname;
    }



    public function getCardNumber(): ?string
    {
        return $this->card_number;
    }

    public function setCardNumber(string $card_number): self
    {
        $this->card_number = $card_number;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(?int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}

