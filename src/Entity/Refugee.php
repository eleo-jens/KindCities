<?php

namespace App\Entity;

use App\Repository\RefugeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefugeeRepository::class)]
class Refugee extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
