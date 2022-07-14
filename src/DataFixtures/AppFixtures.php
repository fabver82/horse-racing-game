<?php

namespace App\DataFixtures;

use App\Entity\Horse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /** @var Generator */
    protected $faker;
    public function load(ObjectManager $manager): void
    {

        $this->faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $horse = new Horse();
            $horse->setImage($this->faker->imageUrl(35, 35));
            $horse->setName('test' . $i);
            $horse->setSpeed($this->faker->randomFloat(2, 0, 10));
            $horse->setEndurance($this->faker->randomFloat(2, 0, 10));
            $horse->setForm($this->faker->randomFloat(2, 0, 10));
            $horse->setFitness($this->faker->randomFloat(2, 0, 10));
            $manager->persist($horse);
        }
        $manager->flush();
    }
}