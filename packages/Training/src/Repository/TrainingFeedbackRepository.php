<?php

declare(strict_types=1);

namespace Pehapkari\Training\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Pehapkari\Training\Entity\Training;
use Pehapkari\Training\Entity\TrainingFeedback;

final class TrainingFeedbackRepository
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository(TrainingFeedback::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @return TrainingFeedback[]
     */
    public function fetchAll(): array
    {
        return $this->entityRepository->findAll();
    }

    /**
     * @return TrainingFeedback[]
     */
    public function getForMainPage(): array
    {
        return $this->entityRepository->createQueryBuilder('tf')
            ->where('tf.isShownOnMainPage = TRUE')
            ->getQuery()
            ->getResult();
    }

    public function save(TrainingFeedback $trainingFeedback): void
    {
        $this->entityManager->persist($trainingFeedback);
        $this->entityManager->flush();
    }

    public function getAverageRating(): float
    {
        $averageRating = (float) $this->entityRepository->createQueryBuilder('tf')
            ->select('AVG(tf.ratingContent) as average_rating')
            ->where('tf.ratingContent IS NOT NULL')
            ->getQuery()
            ->getSingleScalarResult();

        return $this->roundRating($averageRating);
    }

    public function getAverageTrainingRating(Training $training): float
    {
        $averageRating = (float) $this->entityRepository->createQueryBuilder('tf')
            ->select('AVG(tf.ratingContent) as average_rating')
            ->andWhere('tf.ratingContent IS NOT NULL')
            ->andWhere('tf.training = :training')
            ->setParameter('training', $training->getId())
            ->getQuery()
            ->getSingleScalarResult();

        return $this->roundRating($averageRating);
    }

    private function roundRating(float $averageRating): float
    {
        return round($averageRating, 2);
    }
}
