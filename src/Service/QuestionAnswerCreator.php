<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class QuestionAnswerCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * todo is it needed?
     */
    public function create(array $data, User $user): void
    {
        foreach ($data as $row) {
            $question = new Question();
            $question->setText($row[0]);
            $question->setWeight($row[1]);

            $answer = new Answer();
            $answer->setQuestion($question);
            $answer->setText($row[2]);
            $answer->setVector($row[3]);

            $user->addAnswer($answer);
            $this->entityManager->persist($answer);
        }
        $this->entityManager->flush();
    }
}
