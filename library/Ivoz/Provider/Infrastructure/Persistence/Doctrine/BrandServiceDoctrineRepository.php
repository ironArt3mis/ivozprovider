<?php

namespace Ivoz\Provider\Infrastructure\Persistence\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ivoz\Provider\Domain\Model\BrandService\BrandServiceRepository;
use Ivoz\Provider\Domain\Model\BrandService\BrandService;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * BrandServiceDoctrineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BrandServiceDoctrineRepository extends ServiceEntityRepository implements BrandServiceRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BrandService::class);
    }
}