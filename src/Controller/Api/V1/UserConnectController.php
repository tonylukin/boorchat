<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Controller\Api\AbstractController;
use App\Repository\UserRepository;
use App\Service\SimilarUserFinder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserConnectController extends AbstractController
{
    /**
     * @Route("/similar-user-search/{userId}", name="api_v1_similar_user_search")
     */
    public function similarUserSearch(int $userId, UserRepository $userRepository, SimilarUserFinder $similarUserFinder): JsonResponse
    {
        $user = $userRepository->find($userId);
        if ($user === null) {
            return $this->errorResponse('User not found', Response::HTTP_NOT_FOUND);
        }
        $similarUser = $similarUserFinder->find($user);
        if ($similarUser === null) {
            return $this->errorResponse('Similar user not found', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse(['message' => "User: {$similarUser->getUsername()} #{$similarUser->getId()}"]);
    }
}
