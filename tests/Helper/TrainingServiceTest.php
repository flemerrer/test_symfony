<?php

namespace App\Tests\Helper;

use App\Entity\Course;
use App\Helper\TrainingService;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrainingServiceTest extends KernelTestCase
{
    public function testGetCostReturnsCorrectAmount(): void
    {
        $course = new Course();
        $course->setDuration(6);
        $mockRepository = $this->createMock(CourseRepository::class);
        $mockRepository->method('find')->willReturn($course);
        $trainingService = new TrainingService($mockRepository);
        $cost = $trainingService->getCost(1);

        $this->assertEquals(850*6, $cost);
    }
}
