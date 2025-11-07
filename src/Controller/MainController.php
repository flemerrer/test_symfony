<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;

    class MainController extends AbstractController
    {
        #[Route('/', name: 'main_home', methods: ['GET'])]
        public function home(Request $request): Response
        {
            return $this->redirectToRoute("wish_list");
        }

        #[Route('/hello', name: 'main_hello', methods: ['GET'])]
        public function hello(Request $request): Response
        {
            dump($request);
            return $this->render("main/hello.html.twig");
        }

        #[Route('/rainbow', name: 'main_rainbow', methods: ['GET'])]
        public function makeItRain(): Response
        {
            $person = (object)array("firstName" => 'François', 'lastName' => 'Le Dragon', 'game' => 'Arc Raiders');

            return $this->render("main/rainbow.html.twig", [
                "person" => $person
            ]);
        }

        #[Route('/test', name: 'main_test', methods: ['GET'])]
        public function test(Request $request): Response
        {
            dd($request);
        }

        #[Route('/about', name: 'main_about', methods: ['GET'])]
        public function about(): Response
        {
            return $this->render('about.html.twig', []);
        }

    }

?>
