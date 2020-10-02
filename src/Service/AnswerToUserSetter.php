<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AnswerToUserSetter
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function set(int $answerId, User $user): void
    {
        /** @var Answer $answer */
        $answer = $this->entityManager->getRepository(Answer::class)->find($answerId);
        $user->addAnswer($answer);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
