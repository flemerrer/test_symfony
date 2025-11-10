<?php
    namespace App\Controller;

    use App\Entity\Course;
    use App\Form\CourseType;
    use App\Repository\CourseRepository;
    use DateTimeImmutable;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            return $this->render('courses/list.html.twig', compact("courses"));
        }

        #[Route('/add', name: 'course_add', methods: ['GET', 'POST'])]
        public function add(Request $request, EntityManagerInterface $em): Response
        {
            $course = new Course();
            $form = $this->createForm(CourseType::class, $course);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $course->setDateCreated(new DateTimeImmutable());
                $em->persist($course);
                $em->flush();
                return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
            }
            return $this->render('courses/add.html.twig', ["courseForm" => $form]);
        }

        #[Route('/{id}', name: 'course_show', methods: ['GET'])]
        public function show(CourseRepository $courseRepository, $id): Response
        {
            $course = $courseRepository->find($id);
            if (!$course) {
                return $this->redirectToRoute('course_home');
            }
            return $this->render('courses/show.html.twig', compact("course"));
        }

        /*        // Version avec le ParamConverter :
                #[Route('/{id}', name: 'course_show', requirements: ['id' => '\d+'], methods: ['GET'])]
                public function show(Course $course, CourseRepository $courseRepository, $id): Response
                {
                    return $this->render('courses/show.html.twig', compact("course"));
                }*/

        #[Route('/{id}/trainers', name: 'course_trainers', requirements: ['id' => '\d+'], methods: ['GET'])]
        // Manual security check for role
        #[IsGranted("ROLE_PLANNER")]
        public function trainers (Course $course): Response {
            return $this->render('courses/trainers.html.twig', compact("course"));
        }

        #[Route('/{id}/edit', name: 'course_edit', methods: ['GET', 'POST'])]
        public function edit(Request $request, CourseRepository $courseRepository, EntityManagerInterface $em, $id): Response
        {
            $course = $courseRepository->find($id);
            $form = $this->createForm(CourseType::class, $course);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $course->setDateModified(new DateTimeImmutable());
                $em->persist($course);
                $em->flush();
                $this->addFlash('success', 'Course modified successfully!');
                return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
            }
            return $this->render('courses/edit.html.twig', ["course" => $course, "courseForm" => $form]);
        }

        #[Route('/{id}/delete/{token}', name: 'course_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
        public function delete(Course $course, EntityManagerInterface $em, string $token): Response
        {
            if ($this->isCsrfTokenValid('delete-course-' . $course->getId(), $token)) {
                $em->remove($course);
                $em->flush();
                $this->addFlash('success', 'Course deleted successfully!');
                return $this->redirectToRoute('course_home');
            }
            $this->addFlash('danger', 'Course deletion failed!');
            return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
        }

    }
