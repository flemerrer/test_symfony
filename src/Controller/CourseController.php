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
        public function show(CourseRepository $courseRepository, $id): Response
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

        #[Route('/add', name: 'course_add', methods: ['GET'])]
        public function add(EntityManagerInterface $em): Response
        {
//            $course = new Course();
//            $course->setName("test course");
//            $course->setContent("no content");
//            $course->setPublished(true);
//            $course->setDuration(120);
//            $course->setDateCreated(new \DateTimeImmutable());
//            dump($course);
//            // INSERT INTO AKA Persist with ORM
//            $em->persist($course);
//            $em->flush();
//            // UPDATE : No need to call the persist again (in this case)
//            $course->setName("Symfony 6.4");
//            $course->setDuration(120);
//            dump($course);
//            // DELETE
//            $em->remove($course);
//            $em->flush();
            return $this->render("about.html.twig");
        }
    }

?>
