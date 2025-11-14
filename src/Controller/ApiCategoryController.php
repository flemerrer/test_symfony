<?php

    namespace App\Controller;

    use App\Entity\Category;
    use App\Form\CategoryType;
    use App\Models\CreateCategoryDTO;
    use App\Repository\CategoryRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Component\Validator\Validator\ValidatorInterface;

    #[Route('/apiTest/categories')]
    final class ApiCategoryController extends AbstractController
    {

        public function __construct(
//            private readonly SerializerInterface $serializer,
            private readonly EntityManagerInterface $em)
        {
        }

        #[Route('', name: 'api_categories_list', methods: ['GET'])]
        public function list(CategoryRepository $repository): JsonResponse
        {
            $data = $repository->findBy([], ["name" => "ASC"]);
//        $result = $serializer->serialize($data, 'json', ["groups" => 'getCategoriesFull']);
//        return new JsonResponse($result, Response::HTTP_OK, [], true);
            // Eq to :
            return $this->json($data, 200, [], ["groups" => 'getCategoriesFull']);
        }

        #[Route('', name: 'api_categories_create', methods: ['POST'])]
        public function create(
            #[MapRequestPayload(acceptFormat: 'json')]
            CreateCategoryDTO      $categoryDTO,
            Request                $request,
            SerializerInterface    $serializer,
            EntityManagerInterface $em,
            ValidatorInterface     $validator
        ): JsonResponse
        {
//            $body = $request->getContent();
//            $category = $serializer->deserialize($body, Category::class, 'json');
//            $errors = $validator->validate($category);
//            if (count($errors) > 0) {
//                $errorsJson = $serializer->serialize($errors, 'json');
//                return new JsonResponse($errorsJson, Response::HTTP_BAD_REQUEST);
//            }

//            Alternative validation method using DTO pattern :
            $category = Category::createFromDto($categoryDTO);

            $em->persist($category);
            $em->flush();

            return $this->json(null, Response::HTTP_CREATED, [
//            Important optional parameter used to create an absolute url to send back through the API : UrlGeneratorInterface::ABSOLUTE_PATH
                'Location' => $this->generateUrl('api_categories_read', ['category' => $category->getId(), UrlGeneratorInterface::ABSOLUTE_PATH])
            ]);
        }

        #[Route('/{id}', name: 'api_categories_read', requirements: ["id" => "\d+"], methods: ['GET'])]
        public function read(?Category $category): JsonResponse
        {
            if (!$category) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            return $this->json($category, Response::HTTP_OK, [], ["groups" => "getCategories"]);
        }


        #[Route('/api/categories/{id}', name: 'api_categories_update', requirements: ["id" => "\d+"], methods: ['PUT'])]
        public function update(?Category $category, Request $request): JsonResponse
        {

            if (!$category) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $body = $request->getContent();
////            option AbstractNormalizer is important here => so it doesn't erase the existing object but update its values
//            $category = $this->serializer->deserialize($body, Category::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $category]);
//            $category->setDateModified(new \DateTimeImmutable());
//            $this->em->persist($category);
//            $this->em->flush();

            $bodyJson = json_decode($body, true);
//            $bodyJson = $serializer->deserialize($body, Category::class, 'json'); // Alternative

            $form = $this->createForm(CategoryType::class, $category, ['csrf_protection' => false]);
            $form->submit($bodyJson, true);

            if ($form->isValid()) {
                $this->em->flush();
                return $this->json(
                    $category,
                    Response::HTTP_NO_CONTENT,
                    ["Location" => $this->generateUrl(
                        "api_categories_read",
                        ["id" => $category->getId(),
                            UrlGeneratorInterface::ABSOLUTE_PATH
                        ])],
                    ['groups' => 'getCategoriesFull']
                );
            }
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }

        #[Route('/api/categories/{id}', name: 'api_categories_delete', requirements: ["id" => "\d+"], methods: ['DELETE'])]
        public function delete(?Category $category): JsonResponse
        {
            if (!$category) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            $this->em->remove($category);
            $this->em->flush();
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

    }
