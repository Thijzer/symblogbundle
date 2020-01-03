<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Comment;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CommentFixtures extends Fixture implements DependentFixtureInterface
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

    public function getDependencies()
    {
        return [ArticleFixtures::class];
    }
}
