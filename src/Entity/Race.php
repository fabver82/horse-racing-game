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

    public function __construct($horseRepo)
    {
        $this->horses = new ArrayCollection();
        $this->bets = new ArrayCollection();

        $allHorses = $horseRepo->findAll();
        for ($i = 0; $i < 10; $i++) {
            $randId = random_int(0, count($allHorses) - 1);
            $randHorse = array_splice($allHorses, $randId, 1)[0];
            $this->addHorse($randHorse);
        }
        $this->setOdds();
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
    private function getTotalPoint()
    {
        $totalPoint = 0;
        foreach ($this->horses as $horse) {
            $totalPoint += $horse->getTotalPoint();
        }
        return $totalPoint;
    }
    private function setOdds()
    {
        $totalPoint = $this->getTotalPoint();
        $average = $totalPoint / 10;

        foreach ($this->horses as $horse) {
            $totalHorse = $horse->getTotalPoint();
            $diff = $totalHorse - $average;
            $percent = 10 + $diff;
            if ($percent < 0) {
                $percent = - ($percent / (10 - $percent));
            }
            $odd = round(1 / ($percent / 100), 0, PHP_ROUND_HALF_ODD);
            // array_push($odds, $odd);
            $horse->setOdd($odd);
        }
    }
}