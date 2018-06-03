<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $score = 0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Party", inversedBy="players")
     * @ORM\JoinColumn(nullable=true)
     */
    private $party;
    
    public function __construct(string $name = 'none'){
        $this->setName($name);
    }
    
    public function getId()
    {
        return $this->id;
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
    
    public function getParty(): ?Party
    {
        return $this->party;
    }

    public function setParty(Party $party): self
    {
        $this->party = $party;

        return $this;
    }
}
