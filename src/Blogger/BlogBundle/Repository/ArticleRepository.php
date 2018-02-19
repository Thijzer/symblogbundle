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

        return $qb->execute();
    }

    public function findBySlug($slug)
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function getTags()
    {
        $blogTags = $this->createQueryBuilder('b')
            ->select('b.tags')
            ->getQuery()
            ->getResult();

        $tags = array();
        foreach ($blogTags as $blogTag)
        {
            $tags = array_merge(explode(",", $blogTag['tags']), $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }

        return $tags;
    }

    public function getTagWeights($tags)
    {
        $tagWeights = array();
        if (empty($tags))
            return $tagWeights;

        foreach ($tags as $t)
        {
            $tagWeights[$t] = (isset($tagWeights[$t])) ? $tagWeights[$t] + 1 : 1;
        }
        // Shuffle the tags
        uksort($tagWeights, function() {
            return mt_rand() > rand();
        });

        $max = max($tagWeights);

        // Max of 5 weights
        $multiplier = ($max > 5) ? 5 / $max : 1;
        foreach ($tagWeights as &$tag)
        {
            $tag = ceil($tag * $multiplier);
        }

        return $tagWeights;
    }
}