<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class MainController extends AbstractController
    {
        #[Route('/', name: 'main_hello', methods: ['GET'])]
        public function Hello(): Response
        {
            $firstName = "Bob";
            $lastName = "Dylan";
            return $this->render("main/hello.html.twig", compact("firstName", "lastName"));
        }

        #[Route('/rainbow', name: 'main_rainbow', methods: ['GET'])]
        public function Test(): Response
        {
            $person = (object) array("firstName" => 'François', 'lastName' => 'Le Dragon', 'game' => 'Arc Raiders');

            return $this->render("main/rainbow.html.twig", [
                "person" => $person
            ]);
        }
    }

?>
