<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ObraRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ObraRepository::class)]
class Obra
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $inicio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fin = null;

    /**
     * @var Collection<int, Empleado>
     */
    #[ORM\OneToMany(targetEntity: Empleado::class, mappedBy: 'obra')]
    private Collection $empleados;

    /**
     * @var Collection<int, Herramienta>
     */
    #[ORM\OneToMany(targetEntity: Herramienta::class, mappedBy: 'obra')]
    private Collection $herramientas;

    public function __construct()
    {
        $this->empleados = new ArrayCollection();
        $this->herramientas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getInicio(): ?\DateTime
    {
        return $this->inicio;
    }

    public function setInicio(\DateTime $inicio): static
    {
        $this->inicio = $inicio;

        return $this;
    }

    public function getFin(): ?\DateTime
    {
        return $this->fin;
    }

    public function setFin(?\DateTime $fin): static
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * @return Collection<int, Empleado>
     */
    public function getEmpleados(): Collection
    {
        return $this->empleados;
    }

    public function addEmpleado(Empleado $empleado): static
    {
        if (!$this->empleados->contains($empleado)) {
            $this->empleados->add($empleado);
            $empleado->setObra($this);
        }

        return $this;
    }

    public function removeEmpleado(Empleado $empleado): static
    {
        if ($this->empleados->removeElement($empleado)) {
            // set the owning side to null (unless already changed)
            if ($empleado->getObra() === $this) {
                $empleado->setObra(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Herramienta>
     */
    public function getHerramientas(): Collection
    {
        return $this->herramientas;
    }

    public function addHerramienta(Herramienta $herramienta): static
    {
        if (!$this->herramientas->contains($herramienta)) {
            $this->herramientas->add($herramienta);
            $herramienta->setObra($this);
        }

        return $this;
    }

    public function removeHerramienta(Herramienta $herramienta): static
    {
        if ($this->herramientas->removeElement($herramienta)) {
            // set the owning side to null (unless already changed)
            if ($herramienta->getObra() === $this) {
                $herramienta->setObra(null);
            }
        }

        return $this;
    }
}
