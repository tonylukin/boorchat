<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="float")
     */
    private $vector;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @var Question
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers", cascade={"all"})
     */
    private $question;

    /**
     * @var User[]|ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="answers")
     */
    private $users;

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getVector(): ?float
    {
        return $this->vector;
    }

    public function setVector(float $vector): self
    {
        $this->vector = $vector;

        return $this;
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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): Answer
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return User[]|ArrayCollection
     */
    public function getUsers(): ?ArrayCollection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAnswer($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeAnswer($this);
        }

        return $this;
    }
}
