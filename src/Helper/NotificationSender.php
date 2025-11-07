<?php

    namespace App\Helper;

    use App\Entity\User;
    use App\Repository\UserRepository;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Email;

    class NotificationSender
    {


        public function __construct(
            private readonly MailerInterface $mailer,
            private readonly \Twig\Environment $twig,
        )
        {
        }

        public function notifyAdminsOfAccountCreation(User $user): void
        {
            $username = $user->getUsername();
            $email = $user->getEmail();
            $message = "New account for $username (email: $email)";
            file_put_contents("debug.txt", $message);

//            $message = new Email();
//            $CONTENT = $this->twig->render('file.html.twig', [])
//            $message
//                ->from("accounts@email.fr")
//                ->to("admin@email.fr")
//                ->subject("New Account")
//                ->html("<h1>New Account</h1><p>New account for $username (email: $email)</p>");
//            $this->mailer->send($message);
        }

    }

    ?>