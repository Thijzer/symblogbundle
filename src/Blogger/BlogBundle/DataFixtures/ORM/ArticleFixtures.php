<?php

namespace Blogger\BlogBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Blogger\BlogBundle\Entity\Article;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       for ($i = 0; $i <= 10; $i++) {
           $article = $this->createArticle($i);
           $manager->persist($article);
           $this->addReference('article-'.$i, $article);
       }

       $manager->flush();
    }

    public function createArticle($i)
    {
        $faker = Factory::create();

        $article = new Article();
        $article->setTitle($faker->text(15).' '.$i);
        $article->setBody($faker->text);
        $article->setImage('beach.jpg');
        $article->setAuthor($faker->firstName);
        $article->setTags('symfony, php, paradise, symblog');
        $article->setCreated($faker->dateTime());
        $article->setUpdated($article->getCreated());
        $article->setCategory($faker->word);

        return $article;
    }
}