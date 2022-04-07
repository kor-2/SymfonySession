<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgrammeRepository::class)
 */
class Programme
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ModuleFormation::class, inversedBy="programmes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $moduleFormation;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="programmes")
     */
    private $session;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_jour_module;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModuleFormation(): ?ModuleFormation
    {
        return $this->moduleFormation;
    }

    public function setModuleFormation(?ModuleFormation $moduleFormation): self
    {
        $this->moduleFormation = $moduleFormation;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function getNbJourModule(): ?int
    {
        return $this->nb_jour_module;
    }

    public function setNbJourModule(int $nb_jour_module): self
    {
        $this->nb_jour_module = $nb_jour_module;

        return $this;
    }
}
