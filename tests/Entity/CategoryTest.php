<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testSomething(): void
    {
        $category = new Category();
        $category->setName('dépendance circulaire');
        $this->assertEquals('DÉPENDANCE CIRCULAIRE', $category->getName());
    }
}
