<?php
    namespace App\Controller;

    use App\Entity\Course;
    use App\Repository\CourseRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('/courses')]
    class CourseController extends AbstractController
    {
        #[Route('/', name: 'course_home', methods: ['GET'])]
        public function home(CourseRepository $courseRepository): Response
        {
//            $courses = $courseRepository->findAll();
//            $courses = $courseRepository->findBy(["published" => true], ["name" => "ASC"],  50, 0);
            $minDuration = 2;
            $courses = $courseRepository->findLastCourses($minDuration);
            return $this->render('course/list.html.twig', compact("courses"));
        }

        #[Route('/{id}', name: 'course_show', methods: ['GET'])]
        public function show(CourseRepository $courseRepository, int $id): Response
        {
            $course = $courseRepository->find($id);
            if(!$course){
                return $this->redirectToRoute('course_home');
            }
            return $this->render('course/show.html.twig', compact("course"));
        }

/*        // Version avec le ParamConverter :
        #[Route('/{id}', name: 'course_show', requirements: ['id' => '\d+'], methods: ['GET'])]
        public function show(Course $course, CourseRepository $courseRepository, $id): Response
        {
            return $this->render('course/show.html.twig', compact("course"));
        }*/

    }

?>
