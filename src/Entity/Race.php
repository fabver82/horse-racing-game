<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $startAt;

    #[ORM\Column(type: 'integer')]
    private $length;

    #[ORM\ManyToMany(targetEntity: Horse::class, inversedBy: 'races')]
    private $horses;

    #[ORM\ManyToOne(targetEntity: Horse::class)]
    private $winner;

    #[ORM\OneToMany(mappedBy: 'race', targetEntity: Bet::class)]
    private $bets;

    public function __construct()
    {
        $this->horses = new ArrayCollection();
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return Collection<int, Horse>
     */
    public function getHorses(): Collection
    {
        return $this->horses;
    }

    public function addHorse(Horse $horse): self
    {
        if (!$this->horses->contains($horse)) {
            $this->horses[] = $horse;
        }

        return $this;
    }

    public function removeHorse(Horse $horse): self
    {
        $this->horses->removeElement($horse);

        return $this;
    }

    public function getWinner(): ?Horse
    {
        return $this->winner;
    }

    public function setWinner(?Horse $winner): self
    {
        $this->winner = $winner;

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
            $bet->setRace($this);
        }

        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        if ($this->bets->removeElement($bet)) {
            // set the owning side to null (unless already changed)
            if ($bet->getRace() === $this) {
                $bet->setRace(null);
            }
        }

        return $this;
    }
}
