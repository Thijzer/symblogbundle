<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use Faker\Factory;

class CategoryFixtures extends Fixture
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

        return new Category($faker->word());
    }
}
