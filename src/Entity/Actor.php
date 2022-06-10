<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActorRepository::class)
 */
class Actor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $nombre;

    /**
     * @ORM\Column(type="date")
     */
    private $nacimiento;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $nacionalidad;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $biografia;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $imagen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNacimiento(): ?\DateTimeInterface
    {
        return $this->nacimiento;
    }

    public function setNacimiento(\DateTimeInterface $nacimiento): self
    {
        $this->nacimiento = $nacimiento;

        return $this;
    }

    public function getNacionalidad(): ?string
    {
        return $this->nacionalidad;
    }

    public function setNacionalidad(?string $nacionalidad): self
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    public function getBiografia(): ?string
    {
        return $this->biografia;
    }

    public function setBiografia(?string $biografia): self
    {
        $this->biografia = $biografia;

        return $this;
    }

    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }
}
