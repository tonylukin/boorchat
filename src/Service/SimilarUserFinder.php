<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\SearchHistory;
use App\Entity\User;
use App\Repository\SearchHistoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class SimilarUserFinder
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var SearchHistoryRepository
     */
    private $searchHistoryRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        SearchHistoryRepository $searchHistoryRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->searchHistoryRepository = $searchHistoryRepository;
    }

    public function find(User $user): ?User
    {
        $answerIndex = $this->userRepository->calculateAnswers($user);
        $searchHistories = $this->searchHistoryRepository->findBy(['user' => $user]);
        $exceptUsers = \array_map(function (SearchHistory $searchHistory) {
            return $searchHistory->getFoundUser() ? $searchHistory->getFoundUser()->getId() : null;
        }, $searchHistories);

        $similarUser = $this->userRepository->getSimilarUserByAnswerIndex($user, $answerIndex, $exceptUsers);
        if ($similarUser === null) {
            return null;
        }

        $searchHistory = new SearchHistory();
        $searchHistory->setUser($user);
        $searchHistory->setFoundUser($similarUser);
        $this->entityManager->persist($searchHistory);
        $this->entityManager->flush();

        return $similarUser;
    }
}
