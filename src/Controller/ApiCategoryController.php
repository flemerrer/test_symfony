<?php

    namespace App\Controller;

    use App\Entity\Category;
    use App\Repository\CategoryRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
    use Symfony\Component\Serializer\SerializerInterface;

    #[Route('/api/categories')]
    final class ApiCategoryController extends AbstractController
    {


        public function __construct(private readonly SerializerInterface $serializer, private readonly EntityManagerInterface $em)
        {
        }

        #[Route('/', name: 'api_categories_list', methods: ['GET'])]
        public function list(CategoryRepository $repository, SerializerInterface $serializer): JsonResponse
        {
            $data = $repository->findBy([], ["name" => "ASC"]);
//        $result = $serializer->serialize($data, 'json', ["groups" => 'getCategoriesFull']);
//        return new JsonResponse($result, Response::HTTP_OK, [], true);
            // Eq to :
            return $this->json($data, 200, [], ["groups" => 'getCategoriesFull']);
        }

        #[Route('/', name: 'api_categories_create', methods: ['POST'])]
        public function create(Request $request): JsonResponse
        {
            $body = $request->getContent();
            $category = $this->serializer->deserialize($body, Category::class, 'json');
            $category->setDateCreated(new \DateTimeImmutable());
            $this->em->persist($category);
            $this->em->flush();
            return $this->json(
                $category,
                Response::HTTP_CREATED,
                ["Location" => $this->generateUrl(
                    "api_categories_read",
                    ["id" => $category->getId(),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    ])]);
        }

        #[Route('/{id}', name: 'api_categories_read', requirements: ["id" => "\d+"], methods: ['GET'])]
        public function read(?Category $category): JsonResponse
        {
            if (!$category) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $result = $this->serializer->serialize($category, "json", ["groups" => "getCategories"]);
            return new JsonResponse($result, Response::HTTP_OK, [], true);
        }


        #[Route('/api/categories/{id}', name: 'api_categories_update', requirements: ["id" => "\d+"], methods: ['PUT'])]
        public function update(?Category $category, Request $request): JsonResponse
        {

            if (!$category) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $body = $request->getContent();
//            option AbstractNormalizer is important here => so it doesn't erase the existing object but update its values
            $category = $this->serializer->deserialize($body, Category::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $category]);
            $category->setDateModified(new \DateTimeImmutable());
            $this->em->persist($category);
            $this->em->flush();
            return $this->json(
                $category,
                Response::HTTP_NO_CONTENT,
                ["Location" => $this->generateUrl(
                    "api_categories_read",
                    ["id" => $category->getId(),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    ])]);
        }

        #[Route('/api/categories/{id}', name: 'api_categories_delete', requirements: ["id" => "\d+"], methods: ['DELETE'])]
        public function delete(?Category $category): JsonResponse {
            if (!$category) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            $this->em->remove($category);
            $this->em->flush();
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

    }
