<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: Disponibilite::class, orphanRemoval: true)]
    private Collection $disponibilites;

    #[ORM\ManyToMany(targetEntity: Address::class, mappedBy: 'services')]
    private Collection $addresses;

    #[ORM\OneToMany(mappedBy: 'service', targetEntity: DetailReservation::class, orphanRemoval: true)]
    private Collection $DetailsReservation;

    public function __construct()
    {
        $this->disponibilites = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->DetailsReservation = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $disponibilite->setService($this);
        }

        return $this;
    }

    public function removeDisponibilite(Disponibilite $disponibilite): self
    {
        if ($this->disponibilites->removeElement($disponibilite)) {
            // set the owning side to null (unless already changed)
            if ($disponibilite->getService() === $this) {
                $disponibilite->setService(null);
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
            $address->addService($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->removeElement($address)) {
            $address->removeService($this);
        }

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
            $detailsReservation->setService($this);
        }

        return $this;
    }

    public function removeDetailsReservation(DetailReservation $detailsReservation): self
    {
        if ($this->DetailsReservation->removeElement($detailsReservation)) {
            // set the owning side to null (unless already changed)
            if ($detailsReservation->getService() === $this) {
                $detailsReservation->setService(null);
            }
        }

        return $this;
    }
}
