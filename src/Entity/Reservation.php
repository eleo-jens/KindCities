<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeReservation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resume = null;

    #[ORM\OneToMany(mappedBy: 'reservation', targetEntity: DetailReservation::class, orphanRemoval: true)]
    private Collection $DetailsReservation;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Host $host = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Refugee $refugee = null;

    public function __construct()
    {
        $this->DetailsReservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(?\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getCodeReservation(): ?string
    {
        return $this->codeReservation;
    }

    public function setCodeReservation(?string $codeReservation): self
    {
        $this->codeReservation = $codeReservation;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * @return Collection<int, DetailReservation>
     */
    public function getDetailsReservation(): Collection
    {
        return $this->DetailsReservation;
    }

    public function addDetailsReservation(DetailReservation $detailsReservation): self
    {
        if (!$this->DetailsReservation->contains($detailsReservation)) {
            $this->DetailsReservation->add($detailsReservation);
            $detailsReservation->setReservation($this);
        }

        return $this;
    }

    public function removeDetailsReservation(DetailReservation $detailsReservation): self
    {
        if ($this->DetailsReservation->removeElement($detailsReservation)) {
            // set the owning side to null (unless already changed)
            if ($detailsReservation->getReservation() === $this) {
                $detailsReservation->setReservation(null);
            }
        }

        return $this;
    }

    public function getHost(): ?Host
    {
        return $this->host;
    }

    public function setHost(?Host $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getRefugee(): ?Refugee
    {
        return $this->refugee;
    }

    public function setRefugee(?Refugee $refugee): self
    {
        $this->refugee = $refugee;

        return $this;
    }
}
