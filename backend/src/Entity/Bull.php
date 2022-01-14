<?php

namespace App\Entity;

use App\Repository\BullRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BullRepository::class)
 */
class Bull
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $week_milk;

    /**
     * @ORM\Column(type="integer")
     */
    private $week_food;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;
    /**
     * @ORM\Column(type="datetime")
     */
    private $birthday;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getWeekMilk(): ?int
    {
        return $this->week_milk;
    }

    public function setWeekMilk(int $week_milk): self
    {
        $this->week_milk = $week_milk;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getWeekFood(): ?int
    {
        return $this->week_food;
    }

    public function setWeekFood(int $week_food): self
    {
        $this->week_food = $week_food;

        return $this;
    }

    public function toArray()
    {
        return [
            'id'        => $this->getId(),
            'code'      => $this->getCode(),
            'weight'    => $this->getWeight(),
            'birthday'  => $this->getBirthday(),
            'weekMilk'  => $this->getWeekMilk(),
            'weekFood'  => $this->getWeekFood(),
        ];
    }
}
