<?php

namespace Blogger\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getAllArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->addOrderBy('a.created', 'DESC')
            ->getQuery()
        ;

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb->execute();
    }
}