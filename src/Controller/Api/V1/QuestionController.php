<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Component\XmlResponse;
use App\Controller\Api\AbstractController;
use App\Repository\QuestionRepository;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/questions", name="api_v1_question_list", methods={"GET"})
     */
    public function list(QuestionRepository $questionRepository): XmlResponse
    {
        $questions = $questionRepository->getList();

        $output = ['@attributes' => ['type' => 'questions']];
        foreach ($questions as $i => $question) {
            $row = [
                '@attributes' => ['id' => $question->getId()],
                'name' => $question->getText(),
            ];
            foreach ($question->getAnswers() as $answer) {
                $row['answers']['answer'][] = [
                    '@attributes' => ['id' => (string)$answer->getId()],
                    '@value' => $answer->getText(),
                ];
            }
            $output['question'][] = $row;
        }

        return $this->successResponse($output);
    }
}
