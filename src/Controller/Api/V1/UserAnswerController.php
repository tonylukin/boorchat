<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Component\XmlResponse;
use App\Controller\Api\AbstractController;
use App\Repository\UserRepository;
use App\Service\AnswerToUserSetter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserAnswerController extends AbstractController
{
    /**
     * @Route("/user-answer", name="api_v1_create_user_answer", methods={"POST"})
     */
    public function create(Request $request, UserRepository $userRepository, AnswerToUserSetter $answerToUserSetter): XmlResponse
    {
        $username = $request->request->get('user')['@attributes']['username'];
        $user = $userRepository->findOneBy(['username' => $username]);

        if ($user === null) {
            return $this->errorResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->request->get('questions')['question'];
        foreach ($data as $row) {
            $answerToUserSetter->set((int)$row['@attributes']['answer'], $user);
        }

        return $this->successResponse([
            '@attributes' => ['type' => 'answers'],
            'error' => [
                '@attributes' => ['type' => '0'],
                'description' => 'No error',
            ]
        ]);
    }
}
