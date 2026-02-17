<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ContactInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactInfo>
 */
class ContactInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactInfo::class);
    }

    public function findPrimary(): ?ContactInfo
    {
        return $this->findOneBy([], ['id' => 'ASC']);
    }
}
