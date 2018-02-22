<?php

namespace Blogger\BlogBundle\DataFixtures\ORM;

use Blogger\BlogBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Blogger\BlogBundle\Entity\Article;
use Faker\Factory;

class CategoryFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 10; $i++) {
            $category = $this->createCategory();
            $manager->persist($category);
        }

        $manager->flush();
    }

    public function createCategory()
    {
        $faker = Factory::create();

        $category = new Category();
        $category->setCategoryName($faker->word());

        return $category;
    }
}