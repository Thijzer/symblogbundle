<?php

namespace Blogger\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getAllArticles()
    {
        $qb = $this->createQueryBuilder('a')
            ->orderBy('a.created', 'DESC')
            ->getQuery()
        ;

        return $qb->execute();
    }

    public function getLatestArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a')
            ->addOrderBy('a.created', 'DESC')
            ->getQuery()
        ;

        if (false === is_null($limit))
            $qb->setMaxResults($limit);

        return $qb->execute();
    }
}