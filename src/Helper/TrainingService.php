<?php

    namespace App\Helper;

    use App\Repository\CourseRepository;

    class TrainingService
    {


        public function __construct(
            private readonly CourseRepository $courseRepository,
        )
        {
        }

        public function getCost(int $id): int {
            $course = $this->courseRepository->find($id);
            if(!$course){
                throw new \Exception("Course not found.");
            }
            $duration = $course->getDuration();

            if ($duration < 5) {
                return $duration * 1000;
            } elseif($duration < 10) {
                return $duration * 850;
            } else {
                return $duration * 700;
            }
        }
    }
