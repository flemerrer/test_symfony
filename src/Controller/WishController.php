<?php

    namespace App\Controller;

    use App\Form\WishType;
    use App\Repository\WishRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\DependencyInjection\Attribute\Autowire;
    use Symfony\Component\HttpFoundation\File\Exception\FileException;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use App\Entity\Wish;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\String\Slugger\SluggerInterface;

    #[Route('/wishes')]
    class WishController extends AbstractController
    {
        #[Route('/', name: 'wish_list', methods: ['GET'])]
        public function bucketList(WishRepository $wishRepository): Response
        {
            $wishes = $wishRepository->findBy(["published" => true]);
//            return new Response(json_encode(implode(', ', $array)));
            return $this->render('wishes/list.html.twig', compact("wishes"));
        }

        #[Route('/add', name: 'wish_add', methods: ['GET', 'POST'])]
        public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger,
                            #[Autowire('%kernel.project_dir%/public/uploads/illustrations')] string $uploadedImagesDir): Response
        {
            $wish = new Wish();
            $form = $this->createForm(WishType::class, $wish);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $file = $form->get('illustration')->getData();
                if ($file) {
                    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
                    try {
                        $file->move($uploadedImagesDir, $newFilename);
                        $wish->setImageFilename($newFilename);
                    } catch (FileException $e) {
                        $this->addFlash('error', $e->getMessage());
                    }
                }

                $wish->setDateCreated(new \DateTimeImmutable());
                $em->persist($wish);
                $em->flush();
                $this->addFlash('success', 'Wish added successfully!');
                return $this->redirectToRoute('wish_details', ['id' => $wish->getId()]);
            }
            return $this->render('wishes/add.html.twig', ["wishForm" => $form]);
        }

        #[Route('/{id}', name: 'wish_details', methods: ['GET'])]
        public function details(WishRepository $wishRepository, string $id): Response
        {
            $wish = $wishRepository->find($id);
            if (!$wish) {
                return $this->redirectToRoute('wish_list');
            }
            return $this->render('wishes/details.html.twig', ["wish" => $wish]);
        }

        #[Route('/{id}/edit', name: 'wish_edit', methods: ['GET', 'POST'])]
        public function edit(Wish $wish, Request $request, EntityManagerInterface $em, #[Autowire('%kernel.project_dir%/public/uploads/illustrations')] string $uploadedImagesDir): Response
        {
            $form = $this->createForm(WishType::class, $wish);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                if ($form->has('deleteCb')) {
                    unlink($uploadedImagesDir.'/'.$wish->getImageFilename());
                    $wish->setImageFilename('');
                }

                $wish->setDateModified(new \DateTimeImmutable());
                $em->persist($wish);
                $em->flush();
                $this->addFlash('success', 'Wish modified successfully!');
                return $this->redirectToRoute('wish_details', ['id' => $wish->getId()]);
            }
            return $this->render('wishes/edit.html.twig', ["wish" => $wish, "wishForm" => $form]);
        }
    }

?>
