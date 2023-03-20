<?php

namespace App\Entity;

use App\Repository\HostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HostRepository::class)]
class Host extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nationalNumberId = null;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Disponibilite::class, orphanRemoval: true)]
    private Collection $disponibilites;

    #[ORM\ManyToMany(targetEntity: Address::class, inversedBy: 'hosts')]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    public function __construct()
    {
        $this->disponibilites = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getNationalNumberId(): ?string
    {
        return $this->nationalNumberId;
    }

    public function setNationalNumberId(?string $nationalNumberId): self
    {
        $this->nationalNumberId = $nationalNumberId;

        return $this;
    }

    /**
     * @return Collection<int, Disponibilite>
     */
    public function getDisponibilites(): Collection
    {
        return $this->disponibilites;
    }

    public function addDisponibilite(Disponibilite $disponibilite): self
    {
        if (!$this->disponibilites->contains($disponibilite)) {
            $this->disponibilites->add($disponibilite);
            $disponibilite->setHost($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->removeElement($disponibilite)) {
            // set the owning side to null (unless already changed)
            if ($disponibilite->getHost() === $this) {
                $disponibilite->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        $this->addresses->removeElement($address);

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setHost($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getHost() === $this) {
                $reservation->setHost(null);
            }
        }

        return $this;
    }
}
