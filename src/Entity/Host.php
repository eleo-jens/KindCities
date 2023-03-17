<?php

namespace App\Entity;

use App\Repository\HostRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HostRepository::class)]
class Host extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nationalNumberId = null;

    public function getNationalNumberId(): ?string
    {
        return $this->nationalNumberId;
    }

    public function setNationalNumberId(?string $nationalNumberId): self
    {
        $this->nationalNumberId = $nationalNumberId;

        return $this;
    }
}
