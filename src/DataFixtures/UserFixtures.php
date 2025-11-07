<?php

    namespace App\DataFixtures;

    use App\Entity\User;
    use Doctrine\Bundle\FixturesBundle\Fixture;
    use Doctrine\Persistence\ObjectManager;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UserFixtures extends Fixture
    {

        public function __construct(private UserPasswordHasherInterface $usher)
        {
        }

        public function load(ObjectManager $manager): void
        {

            $faker = \Faker\Factory::create('fr_FR');

            $user1 = new User();
            $user1->setFirstName("grosminet");
            $user1->setLastName("lebg");
            $user1->setEmail("toto@admin.fr");
            $user1->setRoles(["ROLE_ADMIN"]);
            $user1->setPassword($this->usher->hashPassword($user1, "africa"));
            $manager->persist($user1);

            $user2 = new User();
            $user2->setFirstName("titi");
            $user2->setLastName("leboss");
            $user2->setEmail("titi@planner.fr");
            $user2->setRoles(["ROLE_PLANNER"]);
            $user2->setPassword($this->usher->hashPassword($user2, "africa"));
            $manager->persist($user2);

            for ($i = 0; $i < 10; $i++) {
                $user = new User();
                $user->setFirstName($faker->firstName);
                $user->setLastName($faker->lastName);
                $user->setEmail("user$i@pandata.fr");
                $user->setRoles(["ROLE_USER"]);
                $user->setPassword($this->usher->hashPassword($user, "toto@123"));
                $manager->persist($user);
            }

            $manager->flush();
        }
    }
