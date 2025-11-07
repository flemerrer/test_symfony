<?php namespace App\Helper;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\String\Slugger\SluggerInterface;

class WishService
{

    public function saveImage($slugger, $uploadedImagesDir, $wish, $file): void
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        $file->move($uploadedImagesDir, $newFilename);
        $wish->setImageFilename($newFilename);
        $wish->setDateCreated(new \DateTimeImmutable());
    }

    public function addComment(Wish $wish, Comment $comment, User $user){
        $comment->setWish($wish);
        $comment->setAuthor($user);
        $comment->setDateCreated(new \DateTimeImmutable());
    }
}

?>