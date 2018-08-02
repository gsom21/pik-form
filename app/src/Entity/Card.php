<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 *
 * @ORM\Table(name="card")
 * @ORM\Entity
 */
class Card
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="id_serial_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var guid
     *
     * @ORM\Column(name="uuid", type="guid", nullable=false)
     */
    private $uuid;

    /**
     * @var integer
     *
     * @ORM\Column(name="card", type="bigint", nullable=false)
     */
    private $card;

    /**
     * @var integer
     *
     * @ORM\Column(name="mm", type="smallint", nullable=false)
     */
    private $mm;

    /**
     * @var integer
     *
     * @ORM\Column(name="yy", type="integer", nullable=false)
     */
    private $yy;

    /**
     * @var integer
     *
     * @ORM\Column(name="cvv", type="integer", nullable=false)
     */
    private $cvv;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getCard(): ?int
    {
        return $this->card;
    }

    public function setCard(int $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getMm(): ?int
    {
        return $this->mm;
    }

    public function setMm(int $mm): self
    {
        $this->mm = $mm;

        return $this;
    }

    public function getYy(): ?int
    {
        return $this->yy;
    }

    public function setYy(int $yy): self
    {
        $this->yy = $yy;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): self
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


}

