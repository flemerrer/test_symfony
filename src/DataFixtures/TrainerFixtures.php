<?php

namespace App\DataFixtures;

use App\Entity\Trainer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrainerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i <= 10; $i++) {
            $trainer = new Trainer();
            $trainer->setFirstName($faker->firstName());
            $trainer->setLastName($faker->lastName());
            $dateCreated = $faker->dateTimeBetween('-3 months', 'now');
            $trainer->setDateCreated(\DateTimeImmutable::createFromMutable( $dateCreated));
            $manager->persist($trainer);
            $this->addReference('trainer'.$i, $trainer);
        }

        $manager->flush();
    }
}
