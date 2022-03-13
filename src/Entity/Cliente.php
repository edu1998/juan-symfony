<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nombre;

    #[ORM\Column(type: 'string', length: 250, nullable: true)]
    private $apellido;

    #[ORM\Column(type: 'string', length: 12, nullable: true)]
    private $telefono;

    #[ORM\Column(type: 'string', length: 250, nullable: true)]
    private $direccion;

    #[ORM\OneToMany(mappedBy: 'cliente', targetEntity: Incidencia::class)]
    private $incidencias;

    public function __construct()
    {
        $this->incidencias = new ArrayCollection();
    }

    public function __toString() {
        return $this->nombre;
    }


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

    public function getApellido()
    {
        return $this->apellido;
    }

    public function setApellido($apellido): void
    {
        $this->apellido = $apellido;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getIncidencias(): Collection
    {
        return $this->incidencias;
    }

    public function addIncidencia(Incidencia $incidencia): self
    {
        if (!$this->incidencias->contains($incidencia)) {
            $this->incidencias[] = $incidencia;
            $incidencia->setCliente($this);
        }

        return $this;
    }

    public function removeIncidencia(Incidencia $incidencia): self
    {
        if ($this->incidencias->removeElement($incidencia)) {
            // set the owning side to null (unless already changed)
            if ($incidencia->getCliente() === $this) {
                $incidencia->setCliente(null);
            }
        }

        return $this;
    }

}
