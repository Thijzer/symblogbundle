<?php

namespace Blogger\BlogBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Blogger\BlogBundle\Entity\Blog;
use Faker\Factory;

class BlogFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
       for ($i = 0; $i <= 10; $i++) {
           $manager->persist($this->createBlog($i));
       }

       $manager->flush();
    }

    public function createBlog($i)
    {
        $faker = Factory::create();

        $blog = new Blog();
        $blog->setTitle($faker->text(15).' '.$i);
        $blog->setBlog($faker->text);
        $blog->setImage('beach.jpg');
        $blog->setAuthor($faker->firstName);
        $blog->setTags('symfony, php, paradise, symblog');
        $blog->setCreated($faker->dateTime());
        $blog->setUpdated($blog->getCreated());

        return $blog;
    }
}