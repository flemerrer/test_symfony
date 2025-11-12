<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(max:2000)]
    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(
     message:"Pick a number between 1 and 5.",
     value:5)]
    #[ORM\Column]
    private ?int $rating = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wish $wish = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $dateCreated = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateModified = null;

    /**
     * @param int|null $id
     */
    public function __construct()
    {
        $this->setDateCreated(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function displayRating(): ?string
    {
        return str_repeat("★", $this->rating).str_repeat("☆", 5 - $this->rating);
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getWish(): ?Wish
    {
        return $this->wish;
    }

    public function setWish(?Wish $wish): static
    {
        $this->wish = $wish;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User|UserInterface $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDateCreated(): ?string
    {
        if ($this->dateCreated != null) {
            return $this->dateCreated->format("d/m/y h:m");
        }
        return "";
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?string
    {
        if ($this->dateModified != null) {
            return $this->dateModified->format("d/m/y h:m");
        }
        return "";
    }

    public function setDateModified(?\DateTimeImmutable $dateModified): static
    {
        $this->dateModified = $dateModified;

        return $this;
    }
}
