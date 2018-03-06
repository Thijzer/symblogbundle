<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getCategories()
    {
        return $this->createQueryBuilder('a')
            ->getQuery()
        ;
    }
}