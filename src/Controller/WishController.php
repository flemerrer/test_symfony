<?php

    namespace App\Controller;

    use App\Entity\Comment;
    use App\Form\CommentType;
    use App\Form\WishType;
    use App\Helper\WishService;
    use App\Repository\WishRepository;
    use DateTimeImmutable;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\DependencyInjection\Attribute\Autowire;
    use Symfony\Component\HttpFoundation\File\Exception\UploadException;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use App\Entity\Wish;
    use Symfony\Component\Routing\Attribute\Route;

    #[Route('/wishes')]
    class WishController extends AbstractController
    {

        public function __construct(
            private readonly WishService            $service,
            private readonly EntityManagerInterface $em,
        )
        {
        }

        #[Route('/', name: 'wish_list', methods: ['GET'])]
        public function bucketList(WishRepository $wishRepository): Response
        {
            $wishes = $wishRepository->getAllWishes();
//            return new Response(json_encode(implode(', ', $array)));
            return $this->render('wishes/list.html.twig', compact("wishes"));
        }

        #[Route('/add', name: 'wish_add', methods: ['GET', 'POST'])]
        public function add(Request $request): Response
        {
            $wish = new Wish();
            $form = $this->createForm(WishType::class, $wish);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $wish->setAuthor($this->getUser());
                $file = $form->get('illustration')->getData();
                if ($file) {
                    try {
                        $newFileName = $this->service->upload($file);
                        $wish->setImageFilename($newFileName);
                    } catch (UploadException $e) {
                        $this->addFlash('error', $e->getMessage());
                    }
                }
                $censoredTitle = $this->service->purify($wish->getTitle());
                $wish->setTitle($censoredTitle);
                $censoredDescription = $this->service->purify($wish->getDescription());
                $wish->setDescription($censoredDescription);
                $this->em->persist($wish);
                $this->em->flush();
                $this->addFlash('success', 'Wish added successfully!');
                return $this->redirectToRoute('wish_details', ['id' => $wish->getId()]);
            }
            return $this->render('wishes/add.html.twig', ["wishForm" => $form]);
        }

        #[Route('/{id}', name: 'wish_details', methods: ['GET', 'POST'])]
        public function details(Request $request, WishRepository $wishRepository, string $id): Response
        {
            $wish = $wishRepository->find($id);
            if (!$wish) {
                return $this->redirectToRoute('wish_list');
            }

            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            $user = $this->getUser();
            if ($form->isSubmitted() && $form->isValid()) {
                $this->service->addComment($wish, $comment, $user);
                $this->addFlash('success', 'Comment added successfully!');
            }

            return $this->render('wishes/details.html.twig', ["wish" => $wish, "commentForm" => $form]);
        }

        #[Route('/{id}/edit', name: 'wish_edit', methods: ['GET', 'POST'])]
        public function edit(Wish $wish, Request $request, #[Autowire('%kernel.project_dir%/public/uploads/illustrations')] string $uploadedImagesDir): Response
        {
            $form = $this->createForm(WishType::class, $wish);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if ($form->has('deleteCb') && $form->get('deleteCb')->getData()) {
                    unlink($uploadedImagesDir . '/' . $wish->getImageFilename());
                    $wish->setImageFilename('');
                }

                $file = $form->get('illustration')->getData();
                if ($file) {
                    try {
                        $newFileName = $this->service->upload($file);
                        $wish->setImageFilename($newFileName);
                    } catch (UploadException $e) {
                        $this->addFlash('error', $e->getMessage());
                    }
                }

                $wish->setDateModified(new DateTimeImmutable());
                $this->em->persist($wish);
                $this->em->flush();
                $this->addFlash('success', 'Wish modified successfully!');
                return $this->redirectToRoute('wish_details', ['id' => $wish->getId()]);
            }
            return $this->render('wishes/edit.html.twig', ["wish" => $wish, "wishForm" => $form]);
        }


        #[Route('/{id}/delete/{token}', name: 'wish_delete', requirements: ['id' => '\d+'], methods: ['GET'])]
        public function delete(Wish $wish, string $token): Response
        {

            if ($this->isCsrfTokenValid('delete-wish-' . $wish->getId(), $token)) {
                $this->em->remove($wish);
                $this->em->flush();
                $this->addFlash('success', 'Wish deleted successfully!');
                return $this->redirectToRoute('wish_list');
            }
            $this->addFlash('danger', 'Wish deletion failed!');
            return $this->redirectToRoute('wish_details', ['id' => $wish->getId()]);
        }
    }
