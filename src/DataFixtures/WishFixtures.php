<?php

    namespace App\DataFixtures;

    use App\Entity\Wish;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;

    class WishFixtures extends Fixture
    {
        public function load(ObjectManager $manager): void
        {
            $faker = \Faker\Factory::create("fr_FR");

            $wishes = ['Faire le tour du monde', 'Manger un gros taco', 'Dabber sur la tour Eiffel',
                'Cuisiner une pizza carrée', 'Visiter la Thaïlande', 'Voler un oeuf', 'Saucer un plat', 'Gratter un poil'];

            for ($i = 0; $i < 7; $i++) {
                $wish = new Wish();
                $wish->setIdUser(rand(1, 10));
                $wish->setTitle($wishes[$i]);
                $wish->setImageFilename('');
                $wish->setDescription($faker->text(200));
                $wish->setPublished(true);
                $wish->setAuthor($faker->name());
                $dateCreated = $faker->dateTimeBetween("-2 years", 'now');
                $wish->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
                $dateModified = $faker->optional(75)->dateTimeBetween($dateCreated, 'now');
                if ($dateModified) {
                    $wish->setDateModified(\DateTimeImmutable::createFromMutable($dateModified));
                }
                $manager->persist($wish);
            }

            $manager->flush();
        }
    }
