<?php

namespace Tests\BloggerBlogBundle\Entity;

use Blogger\BlogBundle\Entity\Article;

class ArticleTest extends \PHPUnit_Framework_TestCase
{
    public function testSlugify()
    {
        $article = new Article();

        $this->assertEquals('hello-world', $article->slugify('Hello World'));
        $this->assertEquals('a-day-with-symfony', $article->slugify('A Day With Symfony'));
        $this->assertEquals('hello-world', $article->slugify('Hello world'));
        $this->assertEquals('symblog', $article->slugify('symblog '));
    }

    public function testSetSlug()
    {
        $article = new Article();

        $article->setSlug('Symfony Blog');
        $this->assertEquals('symfony-blog', $article->getSlug());
    }

    public function testSetTitle()
    {
        $article = new Article();

        $article->setTitle('Hello World');
        $this->assertEquals('hello-world', $article->getSlug());
    }
}