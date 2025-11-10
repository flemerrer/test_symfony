<?php namespace App\Helper;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Wish;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class WishService
{
    public function __construct(private SluggerInterface $slugger, private string $targetDir)
    {
    }

    private array $censoredWords = ['asshole', 'beat off', 'blowjob', 'chink', 'circle jerk', 'clit', 'cock', 'cock sucker', 'coon', 'coochie', 'cunt', 'dick', 'dyke', 'fag', 'faggot', 'fuck', 'gangbang', 'golden shower', 'hand job', 'jack off', 'jerk off', 'jizz', 'kike', 'lesbo', 'mofo', 'motherfucker', 'nigga', 'nigger', 'poontang', 'pussy', 'rim job', 'skeet', 'snatch', 'tits', 'wigger', 'wop'];

    public function purify($string): string
    {
        foreach ($this->censoredWords as $word) {
            $string = str_ireplace($word, '*', $string);
        }
        return $string;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        try {
            $file->move($this->targetDir, $newFilename);
        } catch (FileException $e) {
            throw new UploadException($e->getMessage());
        }
        return $newFilename;
    }

    public function addComment(Wish $wish, Comment $comment, User $user){
        $comment->setWish($wish);
        $comment->setAuthor($user);
        $comment->setDateCreated(new \DateTimeImmutable());
    }
}

?>