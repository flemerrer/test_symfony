<?php

    namespace App\Tests\Controller;

    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class MainControllerTest extends WebTestCase
    {

        public static function getAllpagesUrl(): array {
            return [
                ['/courses'],
                ['/courses/add'],
                ['/courses/1'],
                ['/courses/1/edit'],
                ['/courses/1/trainers'],
            ];
        }

        /**
         * @dataProvider getAllpagesUrl
         */
        public function testAllPagesRequestsAreSuccessful(string $url): void {
            $client = static::createClient();

            $userRepository = static::getContainer()->get(UserRepository::class);
            $user = $userRepository->findOneBy(['email' => 'titi@planner.fr']);
            $client->loginUser($user);

            $crawler = $client->request('GET', $url);

            $this->assertResponseIsSuccessful();
        }

        public function testCourseListRequestIsSuccessful(): void
        {
            $client = static::createClient();
            $crawler = $client->request('GET', '/courses');

            $this->assertResponseIsSuccessful();
            $this->assertSelectorTextContains('h1', 'Courses');
        }

        public function testLinkToHomePageIsWorking(): void
        {
            $this->markTestSkipped('Must be revisited.');
            $client = static::createClient();
            $crawler = $client->request('GET', '/courses');

            $link = $crawler->selectLink('Home')->link();
            $client->click($link);
//            FIXME: client is still at original page (as if link not clicked)
            $this->assertSelectorTextContains('h1', 'My Bucket List');
        }

        public function testValidCourseForm()
        {
            $client = static::createClient();

            $userRepository = static::getContainer()->get(UserRepository::class);
            $user = $userRepository->findOneBy(['email' => 'toto@admin.fr']);
            $client->loginUser($user);

            $crawler = $client->request('GET', '/courses/add');

            $client->submitForm('Add Course', [
                'course[name]' => 'Test Course',
                'course[content]' => 'Test Course',
                'course[published]' => '1',
                'course[duration]' => '10',
                'course[category]' => '1',
                'course[trainers]' => '2'
            ]);

            $this->assertEquals(302, $client->getResponse()->getStatusCode());
            $client->followRedirect();
            $this->assertRouteSame('course_show');
        }

    }
