<?php

namespace Blogger\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getAllArticles($limit = null)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a, c')
            ->leftJoin('a.comments', 'c')
            ->addOrderBy('a.created', 'DESC')
            ->getQuery()
        ;

        if ($limit !== null) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    public function findBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function getAllArticlesByCategory($category)
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a, c')
            ->leftJoin('a.comments', 'c')
            ->where('a.category IN (:category)')
            ->setParameter('category', $category)
            ->addOrderBy('a.created', 'DESC')
            ->getQuery()
        ;

        return $qb;
    }

    public function getCategories()
    {
        $article_categories = $this->createQueryBuilder('a')
            ->select('a.category')
            ->getQuery()
            ->getResult();

        return $article_categories;
    }
}