<?php

    namespace App\DataFixtures;

    use App\Entity\Course;
    use App\Entity\Wish;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;

    class AppFixtures extends Fixture
    {
        public function load(ObjectManager $em): void
        {
            $faker = \Faker\Factory::create("fr_FR");

            $course = new Course();
            $course->setName("PHP 8.3");
            $course->setContent("Curabitur est gravida et libero vitae dictum.");
            $course->setPublished(true);
            $course->setDuration(120);
            $course->setDateCreated(new \DateTimeImmutable());
            $em->persist($course);

            $course2 = new Course();
            $course2->setName("Symfony 6.4");
            $course2->setContent("Curabitur est gravida et libero vitae dictum.");
            $course2->setPublished(true);
            $course2->setDuration(180);
            $course2->setDateCreated(new \DateTimeImmutable());
            $em->persist($course2);

            for ($i = 1; $i < 15; $i++) {
                $new = new Course();
                $new->setName($faker->sentence);
                $new->setContent($faker->text(200));
                $new->setPublished($faker->boolean(chanceOfGettingTrue: 85));
                $new->setDuration($faker->numberBetween(30, 180));
                $dateCreated = $faker->dateTimeBetween("-2 years", 'now');
                $new->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
                $dateModified = $faker->optional(75)->dateTimeBetween($dateCreated, 'now');
                if ($dateModified) {
                    $new->setDateModified(\DateTimeImmutable::createFromMutable($dateModified));
                }
                $em->persist($new);
            }

            $wishes = ['Faire le tour du monde', 'Manger un gros taco', 'Dabber sur la tour Eiffel',
                'Cuisiner une pizza carrée', 'Visiter la Thaïlande', 'Voler un oeuf', 'Saucer un plat', 'Gratter un poil'];

            for ($i = 0; $i < 7; $i++) {
                $wish = new Wish();
                $wish->setIdUser(rand(1, 10));
                $wish->setTitle($wishes[$i]);
                $wish->setDescription($faker->text(200));
                $wish->setPublished(true);
                $wish->setAuthor($faker->name());
                $dateCreated = $faker->dateTimeBetween("-2 years", 'now');
                $wish->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
                $dateModified = $faker->optional(75)->dateTimeBetween($dateCreated, 'now');
                if ($dateModified) {
                    $wish->setDateModified(\DateTimeImmutable::createFromMutable($dateModified));
                }
                $em->persist($wish);
            }

            $em->flush();
        }
    }
