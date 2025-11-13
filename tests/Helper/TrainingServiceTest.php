<?php

    namespace App\Tests\Helper;

    use App\Entity\Course;
    use App\Helper\TrainingService;
    use App\Repository\CourseRepository;
    use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

    class TrainingServiceTest extends KernelTestCase
    {

        public static function getDurationAndExpectedCost(): array
        {
            return [
                [3, 1000 * 3],
                [6, 850 * 6],
                [10, 700 * 10]
            ];
        }

        /**
         * @dataProvider getDurationAndExpectedCost
         */
        public function testGetCostReturnsCorrectAmount(int $duration, int $expectedCost): void
        {
            $course = new Course();
            $course->setDuration($duration);

            $mockRepository = $this->createMock(CourseRepository::class);
            $mockRepository
                ->expects($this->once())
                ->method('find')
                ->with(1)
                ->willReturnOnConsecutiveCalls($course);

            $trainingService = new TrainingService($mockRepository);

            $this->assertEquals($expectedCost, $trainingService->getCost(1));
        }
    }
