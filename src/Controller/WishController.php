<?php

    namespace App\Controller;

    use App\Form\WishType;
    use App\Repository\WishRepository;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use App\Entity\Wish;
    use Symfony\Component\Routing\Attribute\Route;

    class WishController extends AbstractController
    {
        #[Route('/list', name: 'wish_list', methods: ['GET'])]
        public function bucketList(WishRepository $wishRepository): Response
        {
            $wishes = $wishRepository->findBy(["published" => true]);

//            return new Response(json_encode(implode(', ', $array)));
            return $this->render('wishes/list.html.twig', compact("wishes"));
        }

        #[Route('/details', name: 'wish_details', methods: ['GET'])]
        public function details(): Response
        {
            return $this->render('wishes/details.html.twig', []);
        }

        #[Route('/add', name: 'wish_add', methods: ['GET', 'POST'])]
        public function add(Request $request, EntityManagerInterface $em): Response
        {
            $wish = new Wish();
            $form = $this->createForm(WishType::class, $wish);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $wish->setDateCreated(new \DateTimeImmutable());
                $em->persist($wish);
                $em->flush();
                $this->addFlash('success', 'Wish added successfully!');
                return $this->redirectToRoute('wish_list');
            }
            return $this->render('wishes/add.html.twig', ["wishForm" => $form]);
        }

    }

?>
