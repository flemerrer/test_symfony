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

            $user = new User();
            $user->setFirstName("toto");
            $user->setLastName("lebg");
            $user->setEmail("toto@admin.fr");
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setPassword($this->usher->hashPassword($user, "africa"));
            $manager->persist($user);

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
