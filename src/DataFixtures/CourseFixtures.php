<?php

    namespace App\DataFixtures;

    use App\Entity\Category;
    use App\Entity\Course;
    use App\Entity\Trainer;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Persistence\ObjectManager;

    class CourseFixtures extends Fixture implements DependentFixtureInterface
    {
        public function load(ObjectManager $manager): void
        {
            $faker = \Faker\Factory::create("fr_FR");

            $course1 = new Course();
            $course1->setName("PHP 8.3");
            $course1->setContent("Curabitur est gravida et libero vitae dictum.");
            $course1->setPublished(true);
            $course1->setDuration(120);
            $course1->setCategory($this->getReference("category1", Category::class));
            $manager->persist($course1);

            $course2 = new Course();
            $course2->setName("Symfony 6.4");
            $course2->setContent("Curabitur est gravida et libero vitae dictum.");
            $course2->setPublished(true);
            $course2->setDuration(180);
            $course2->setCategory($this->getReference("category2", Category::class));
            $manager->persist($course2);

            for ($i = 1; $i < 15; $i++) {
                $course = new Course();
                $course->setName($faker->colorName());
                $course->setContent($faker->text(200));
                $course->setPublished($faker->boolean(chanceOfGettingTrue: 85));
                $course->setDuration($faker->numberBetween(30, 180));
                $dateCreated = $faker->dateTimeBetween("-2 years", 'now');
                $course->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));
                $dateModified = $faker->optional(75)->dateTimeBetween($dateCreated, 'now');
                $course ->setCategory($this->getReference("category".$faker->numberBetween(1, 2), Category::class));
                $numberOfTrainersInThisCourse = $faker->numberBetween(1, 5);
                for ($j = 1; $j <= $numberOfTrainersInThisCourse; $j++) {
                    $course->addTrainer($this->getReference("trainer".$faker->numberBetween(1, 10), Trainer::class));
                }
                if ($dateModified) {
                    $course->setDateModified(\DateTimeImmutable::createFromMutable($dateModified));
                }
                $manager->persist($course);
            }

            $manager->flush();
        }

        public function getDependencies(): array
        {
            return [CategoryFixtures::class, TrainerFixtures::class];
        }


    }
