<?php

    namespace App\Controller;

    use App\Form\EventSearchType;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Attribute\Route;
    use Symfony\Component\Serializer\SerializerInterface;
    use Symfony\Contracts\HttpClient\HttpClientInterface;

    #[Route('/apiTest')]
    final class ApiTestController extends AbstractController
    {
        #[Route('/regions', name: 'api_regionsList')]
        public function regionsList(SerializerInterface $serializer): Response
        {
            // Also works with web uri
            $content = file_get_contents('https://geo.api.gouv.fr/regions');
//        $regionsArray = $serializer->decode($content, 'json');
            // Don't forget the array concatenation and its weird syntax here
//        $regions = $serializer->denormalize($regionsArray, Region::class . '[]');
            // Eq to :
            $regions = $serializer->deserialize($content, Region::class . '[]', 'json');
            return $this->render('api/regions.html.twig', ["regions" => $regions]);
        }

        #[Route('/events', name: 'api_eventsList', methods: ['GET', 'POST'])]
        public function eventsList(Request $request, SerializerInterface $serializer, HttpClientInterface $httpClient): Response
        {
            $BASE_URL = 'https://public.opendatasoft.com/api/explore/v2.1/catalog/datasets/evenements-publics-openagenda/records?limit=25';
            $form = $this->createForm(EventSearchType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $city = ucfirst($form->get('city')->getData());
                $date = $form->get('dateEvent')->getData()->format('Y-m-d');
                $response = $httpClient->request("GET",
                    $BASE_URL . "&refine=location_city%3A" . $city . "&refine=firstdate_begin%3A" . $date);
            } else {
                $response = $httpClient->request("GET", $BASE_URL);
            }

            $resultsArray = $serializer->decode($response->getContent(), 'json');
            $results = $serializer->denormalize($resultsArray["results"], Event::class . '[]');

            return $this->render('api/events.html.twig', ["events" => $results, "eventSearch" => $form]);
        }
    }

    class Region
    {
        private ?string $nom = "";
        private ?string $code = "";

        public function getNom(): string
        {
            return $this->nom;
        }

        public function setNom(string $nom): void
        {
            $this->nom = $nom;
        }

        public function getCode(): string
        {
            return $this->code;
        }

        public function setCode(string $code): void
        {
            $this->code = $code;
        }

    }

    class Event
    {
        private ?string $title_fr = "";
        private ?string $thumbnail = "";
        private ?string $description_fr = "";
        private ?string $daterange_fr = "";
        private ?string $canonical_url = "";
        private ?string $location_name = "";
        private ?string $location_address = "";

        public function getTitleFr(): ?string
        {
            return $this->title_fr;
        }

        public function setTitleFr(?string $title_fr): void
        {
            $this->title_fr = $title_fr;
        }

        public function getThumbnail(): ?string
        {
            return $this->thumbnail;
        }

        public function setThumbnail(?string $thumbnail): void
        {
            $this->thumbnail = $thumbnail;
        }

        public function getDescriptionFr(): ?string
        {
            return $this->description_fr;
        }

        public function setDescriptionFr(?string $description_fr): void
        {
            $this->description_fr = $description_fr;
        }

        public function getDaterangeFr(): ?string
        {
            return $this->daterange_fr;
        }

        public function setDaterangeFr(?string $daterange_fr): void
        {
            $this->daterange_fr = $daterange_fr;
        }

        public function getCanonicalUrl(): ?string
        {
            return $this->canonical_url;
        }

        public function setCanonicalUrl(?string $canonical_url): void
        {
            $this->canonical_url = $canonical_url;
        }

        public function getLocationName(): ?string
        {
            return $this->location_name;
        }

        public function setLocationName(?string $location_name): void
        {
            $this->location_name = $location_name;
        }

        public function getLocationAddress(): ?string
        {
            return $this->location_address;
        }

        public function setLocationAddress(?string $location_address): void
        {
            $this->location_address = $location_address;
        }


    }

