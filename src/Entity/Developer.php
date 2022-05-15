<?php

namespace App\Entity;

use App\Repository\DeveloperRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeveloperRepository::class)
 */
class Developer
{
    const WEEKLY_WORKING_HOURS = 45;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=DeveloperLevel::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $level;

    private $labor;

    private array $plans;

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

    public function getLevel(): ?developerLevel
    {
        return $this->level;
    }

    public function setLevel(developerLevel $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLabor()
    {
        return $this->labor;
    }

    /**
     * @param mixed $labor
     */
    public function setLabor($labor): void
    {
        $this->labor = $labor;
    }

    /**
     * @return array
     */
    public function getPlans(): array
    {
        return $this->plans;
    }

    /**
     * @param int   $week
     * @param array $plans
     */
    public function addPlans(int $week, array $plans): void
    {
        $this->plans[$week][] = $plans;
    }
}
