<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $time;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $providerReferenceId;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $provider;

    private $labor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getTime(): ?int
    {
        return $this->time;
    }

    public function setTime(int $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getProviderReferenceId(): ?string
    {
        return $this->providerReferenceId;
    }

    public function setProviderReferenceId(string $providerReferenceId): self
    {
        $this->providerReferenceId = $providerReferenceId;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

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
}
