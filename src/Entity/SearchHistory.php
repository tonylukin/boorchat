<?php

namespace App\Entity;

use App\Repository\SearchHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SearchHistoryRepository::class)
 */
class SearchHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"all"})
     */
    private $user;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"all"})
     */
    private $foundUser;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): SearchHistory
    {
        $this->user = $user;
        return $this;
    }

    public function getFoundUser(): ?User
    {
        return $this->foundUser;
    }

    public function setFoundUser(User $foundUser): SearchHistory
    {
        $this->foundUser = $foundUser;
        return $this;
    }
}
