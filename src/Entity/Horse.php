<?php

namespace App\Entity;

use App\Repository\HorseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HorseRepository::class)]
class Horse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'float')]
    private $speed;

    #[ORM\Column(type: 'float')]
    private $endurance;

    #[ORM\Column(type: 'float')]
    private $form;

    #[ORM\Column(type: 'float')]
    private $fitness;

    #[ORM\ManyToMany(targetEntity: Race::class, mappedBy: 'horses')]
    private $races;

    #[ORM\OneToMany(mappedBy: 'horse', targetEntity: Bet::class)]
    private $bets;

    private $odd;

    public function __construct()
    {
        $this->odd = null;
        $this->races = new ArrayCollection();
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSpeed(): ?float
    {
        return $this->speed;
    }

    public function setSpeed(float $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getEndurance(): ?float
    {
        return $this->endurance;
    }

    public function setEndurance(float $endurance): self
    {
        $this->endurance = $endurance;

        return $this;
    }

    public function getForm(): ?float
    {
        return $this->form;
    }
    public function getFormStr(): ?String
    {

        switch (true) {
            case ($this->form < 3):
                return 'Poor';
            case ($this->form < 5):
                return 'Average';
            case ($this->form < 8):
                return 'Good';
            case ($this->form <= 10):
                return 'Excellent';
        }
    }

    public function setForm(float $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function getFitness(): ?float
    {
        return $this->fitness;
    }

    public function setFitness(float $fitness): self
    {
        $this->fitness = $fitness;

        return $this;
    }

    public function getTotalPoint()
    {
        return ($this->getSpeed() * 1.5) + ($this->getEndurance() * 1.5) + ($this->getForm() * 2) + $this->getFitness();
    }

    public function getOdd()
    {
        return $this->odd;
    }
    public function setOdd($odd): self
    {
        $this->odd = $odd;
        return  $this;
    }

    /**
     * @return Collection<int, Race>
     */
    public function getRaces(): Collection
    {
        return $this->races;
    }

    public function addRace(Race $race): self
    {
        if (!$this->races->contains($race)) {
            $this->races[] = $race;
            $race->addHorse($this);
        }

        return $this;
    }

    public function removeRace(Race $race): self
    {
        if ($this->races->removeElement($race)) {
            $race->removeHorse($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Bet>
     */
    public function getBets(): Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets[] = $bet;
            $bet->setHorse($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getHorse() === $this) {
                $bet->setHorse(null);
            }
        }

        return $this;
    }
}