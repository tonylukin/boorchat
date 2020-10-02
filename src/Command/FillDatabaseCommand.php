<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillDatabaseCommand extends Command
{
    private const USER_COUNT = 10;
    private const QUESTION_COUNT = 10;

    protected static $defaultName = 'app:fill-db';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $initialQuestions = $this->generateQuestions();

        foreach (\range(1, self::USER_COUNT) as $i) {
            $user = new User();
            $user->setUsername("TestUser{$i}");
            $user->setExternalId($i);

            $questions = $initialQuestions;
            \shuffle($questions);
            foreach (\range(1, self::QUESTION_COUNT) as $k) {
                $answer = new Answer();
                $answer->setText("Answer #{$k} for {$user->getUsername()}");
                $answer->setVector($this->generateVector());
                $answer->setQuestion(\array_shift($questions));
                $user->addAnswer($answer);
                $this->em->persist($answer);
            }
            $this->em->persist($user);
        }
        $this->em->flush();

        return 0;
    }

    /**
     * @return Question[]
     */
    private function generateQuestions(): array
    {
        $data = [];
        foreach (\range(1, self::QUESTION_COUNT) as $i) {
            $question = new Question();
            $question->setText("Question #{$i}");
            $question->setWeight(\random_int(1, 10));
            $this->em->persist($question);
            $data[] = $question;
        }

        return $data;
    }

    private function generateVector(): float
    {
        $precision = 100;
        return (\random_int(0, $precision * 2) - $precision) / $precision;
    }
}
