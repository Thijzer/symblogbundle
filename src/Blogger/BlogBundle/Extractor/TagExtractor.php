<?php

namespace Blogger\BlogBundle\Extractor;

use Blogger\BlogBundle\Entity\Article;

class TagExtractor
{
    /**
     * @param $articles Article[]
     *
     * @return array
     */
    public function createTagList($articles)
    {
        $tags = array();
        foreach ($articles as $article)
        {
            $tags = array_merge(explode(",", $article->getTags()), $tags);
        }

        foreach ($tags as &$tag)
        {
            $tag = trim($tag);
        }

        return $tags;
    }

    /**
     * @param $articles Article[]
     *
     * @return array
     */
    public function getTagWeights($articles)
    {
        $tags = $this->createTagList($articles);

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