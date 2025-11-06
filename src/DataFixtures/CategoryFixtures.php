<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category1 = new Category();
        $category1->setName("Développement");
        $category1->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category1);
        $this->addReference('category1', $category1);

        $category2 = new Category();
        $category2->setName("Sysadmin");
        $category2->setDateCreated(new \DateTimeImmutable());
        $manager->persist($category2);
        $this->addReference('category2', $category2);

        $manager->flush();
    }
}
