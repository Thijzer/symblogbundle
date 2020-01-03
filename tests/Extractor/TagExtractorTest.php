<?php

namespace Tests\App\Extractor;

use App\Entity\Article;
use App\Extractor\TagExtractor;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TagExtractorTest extends WebTestCase
{
    /**
     * @var $extractor TagExtractor
     */
    private $extractor;

    public function setUp()
    {
        $this->extractor = new TagExtractor();
    }

    public function testCreateTagList()
    {
        $tagList = $this->extractor->createTagList($this->createArticles('abcd, cdba'));

        $this->assertTrue(count($tagList) > 1);
        $this->assertContains('abcd', $tagList);
    }

    public function testGetTagWeights()
    {
        $tags = 'php, code, code, symblog, article';
        $tagsWeight = $this->extractor->getTagWeights($this->createArticles($tags));

        $this->assertTrue(count($tagsWeight) > 1);

        $tags = 'php';
        $tagsWeight = $this->extractor->getTagWeights($this->createArticles($tags, 5));

        $this->assertEquals(5, $tagsWeight['php']);

        $articleCollection = $this->createArticleCollection([
            $this->createArticle('php'),
            $this->createArticle('php'),
            $this->createArticle('php'),
            $this->createArticle('html'),
            $this->createArticle('html'),
            $this->createArticle('js'),
            $this->createArticle('js, php'),
            $this->createArticle('php, html'),
            $this->createArticle('php, html, js'),
        ]);
        $tagsWeight = $this->extractor->getTagWeights($articleCollection);

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(4, $tagsWeight['html']);

        $tagsWeight = $this->extractor->getTagWeights([]);
        $this->assertEmpty($tagsWeight);
    }

    /**
     * @param string $tags
     *
     * @return Article
     */
    private function createArticle($tags)
    {
        $article = $this->prophesize(Article::class);
        $article->getTags()->willReturn($tags);

        return $article->reveal();
    }

    private function createArticles($tags, $maxArticles = 2)
    {
        $articles = [];
        for ($i = 1; $i <= $maxArticles; $i++) {
            $articles[] = $this->createArticle($tags);
        }

        return $this->createArticleCollection($articles);
    }

    /**
     * @param array $articles
     * @return ArrayCollection
     */
    private function createArticleCollection(array $articles = [])
    {
        $arrayCollection = new ArrayCollection();

        foreach ($articles as $article) {
            $arrayCollection->add(clone $article);
        }

        return $arrayCollection;
    }
}
