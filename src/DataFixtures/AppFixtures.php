<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setTitle($faker->sentence())->setImage('https://picsum.photos/id/'.$i.'/300/150')->setPrice($faker->randomFloat(2));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
