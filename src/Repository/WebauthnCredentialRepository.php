<?php

namespace App\Repository;

use App\Entity\WebauthnCredential;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WebauthnCredentialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebauthnCredential::class);
    }

    public function findOneByCredentialId(string $credentialId): ?WebauthnCredential
    {
        return $this->createQueryBuilder('w')
            ->andWhere('JSON_EXTRACT(w.credentialData, "$.id") = :id')
            ->setParameter('id', $credentialId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}