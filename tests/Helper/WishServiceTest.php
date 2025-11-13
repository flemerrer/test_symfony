<?php

namespace App\Tests\Helper;

use App\Helper\WishService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WishServiceTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->wishService = static::getContainer()->get(WishService::class);
    }

    public function testTruncateTitleWhenTooLong(): void {
        $expected = "My wish is...";
        $actual = $this->wishService->truncate(10, "My wish is too long.");
        $this->assertEquals($expected, $actual);
    }

}
