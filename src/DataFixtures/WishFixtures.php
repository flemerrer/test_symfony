<?php

    namespace App\DataFixtures;

    use App\Entity\User;
    use App\Entity\Wish;
    use App\Entity\WishCategory;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Common\DataFixtures\DependentFixtureInterface;
    use Doctrine\Persistence\ObjectManager;

    class WishFixtures extends Fixture implements DependentFixtureInterface
    {
        public function load(ObjectManager $manager): void
        {
            $faker = \Faker\Factory::create("fr_FR");

            $category = ['Travel & Adventure', 'Sport', 'Entertainment', 'Human Relations', 'Others'];

            $j=1;
            foreach ($category as $cat) {
                $wishCat = new WishCategory();
                $wishCat->setName($cat);
                $manager->persist($wishCat);
                $this->addReference('wish_category_'.$j, $wishCat);
                $j++;
            }

            $manager->flush();

            $wishes = ['Faire le tour du monde', 'Manger un gros taco', 'Dabber sur la tour Eiffel',
                'Cuisiner une pizza carrée', 'Visiter la Thaïlande', 'Voler un oeuf', 'Saucer un plat', 'Gratter un poil'];

            for ($i = 0; $i < 7; $i++) {
                $wish = new Wish();
                $wish->setIdUser(rand(1, 10));
                $wish->setTitle($wishes[$i]);
                $wish->setImageFilename('');
                $wish->setDescription($faker->text(200));
                $wish->setPublished(true);
                $wishCat = $this->getReference("wish_category_".$faker->numberBetween(1, 5), WishCategory::class);
                $wish->setWishCategory($wishCat);
                $wish->setAuthor($this->getReference("user$i", User::class));
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

        public function getDependencies(): array
        {
            return [UserFixtures::class];
        }
    }
