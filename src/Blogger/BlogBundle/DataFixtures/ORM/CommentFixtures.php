<?php

namespace Blogger\BlogBundle\DataFixtures\ORM;

use Blogger\BlogBundle\Entity\Article;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Blogger\BlogBundle\Entity\Comment;
use Faker\Factory;

class CommentFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 10; $i++) {
            $comment = $this->createComment($this->getArticle($i));

            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function createComment(Article $article)
    {
        $faker = Factory::create();

        $comment = new Comment($article, $faker->realText($maxNbChars = 50, $indexSize = 2));
        $comment->setUser($faker->firstName());

        return $comment;
    }

    protected function getArticle($i)
    {
        return $this->getReference('article-'.$i);
    }

    function getDependencies()
    {
        return [ArticleFixtures::class];
    }
}