<?php

    namespace App\Controller;

    use App\Entity\Course;
    use App\Repository\CategoryRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    final class ApiCourseController extends AbstractController
    {
        #[Route('/apiTest/courses', name: 'api_courses_create', methods: ['POST'])]
        public function create(
            Request                $request,
            CategoryRepository     $categoryRepository,
            EntityManagerInterface $em,
            ValidatorInterface     $validator,
            SerializerInterface    $serializer
        ): JsonResponse
        {
            $data = $request->getContent();
            $course = $serializer->deserialize($data, Course::class, 'json');

            $dataArray = $request->toArray();
            $category = $categoryRepository->find($dataArray['category']['id']);
            if (!$category) {
                return new JsonResponse(
                    ['message' => 'Unknown category.'],
                    Response::HTTP_BAD_REQUEST,
                    []
                );
            }
            $course->setCategory($category);

            $errors = $validator->validate($course);
            if (count($errors) > 0) {
                $errorsJson = $serializer->serialize($errors, 'json');
                return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
            }

            $em->persist($course);
            $em->flush();
            // Can't use JsonResponse here because we need to set the context
            return $this->json(null, Response::HTTP_CREATED, [], ['groups' => ['getCourse']]);
        }
    }
