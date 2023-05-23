<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Faker\Factory;
use App\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create('fr_FR');
        $slugify = new Slugify();
        for ($i=0; $i < 10; $i++) { 
           $newCategory = new Category();
           $newCategory
           ->setName($faker->name)
           ->setSlug($slugify->slugify($newCategory->getName()))
           ->setDescription($faker->text())
           ->setPicture('https://picsum.photos/200/300?random');

           $manager->persist($newCategory);

           for ($e=0; $e < 10; $e++) { 
               $date = new \DateTimeImmutable();
               $newProduct = new Product();
               $newProduct
               ->setName($faker->name)
               ->setDescription($faker->text())
               ->setPrice(random_int(10,500))
               ->setPicture('https://picsum.photos/200/300?random')
               ->setSlug($slugify->slugify($newProduct->getName()))
               ->setCreatedAt($date)
               ->setCategory($newCategory);

               $manager->persist($newProduct);
           }

           $manager->flush();
        }


    }
}
