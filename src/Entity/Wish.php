<?php

    namespace App\Entity;

    use App\Repository\WishRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Validator\Constraints as Assert;

    #[UniqueEntity('title', message: 'This wish already exists!')]
    #[ORM\Entity(repositoryClass: WishRepository::class)]
    class Wish
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: Types::INTEGER, nullable: false)]
        private ?int $id;

        #[Assert\NotBlank(message: 'Please enter a title for your wish.')]
        #[Assert\Length(min: 2, max: 50, minMessage: 'Min 2 characters!', maxMessage: 'Max 250 characters!')]
        #[ORM\Column(type: Types::STRING, length: 250, nullable: false)]
        private ?string $title;
        #[Assert\NotBlank(message: 'Please enter a description (min 10 chars).')]
        #[Assert\Length(min: 10, max: 2000, minMessage: 'Min 10 characters!', maxMessage: 'Max 2000 characters!')]
        #[ORM\Column(type: Types::STRING)]
        private ?string $description;
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'wishes')]
        private ?User $author;
        #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
        private ?bool $published = null;
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        private ?\DateTimeImmutable $dateCreated = null;
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
        private ?\DateTimeImmutable $dateModified = null;
        #[ORM\Column(type: Types::STRING, nullable: true)]
        private ?string $imageFilename = null;

        #[ORM\ManyToOne(inversedBy: 'wishes')]
        private ?WishCategory $wishCategory = null;

        /**
         * @var Collection<int, Comment>
         */
        #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'wish')]
        private Collection $comments;

        public function __construct()
        {
            $this->comments = new ArrayCollection();
            $this->setDateCreated(new \DateTimeImmutable());
        }

        public function getImageFilename(): string
        {
            if ($this->imageFilename) {
                return $this->imageFilename;
            }
            return "";
        }

        public function setImageFilename(string $imageFilename): void
        {
            $this->imageFilename = $imageFilename;
        }

        public function setId(int $id): void
        {
            $this->id = $id;
        }

        public function setIdUser(int $idUser): void
        {
            $this->idUser = $idUser;
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getTitle(): ?string
        {
            return $this->title;
        }

        public function setTitle(?string $title): void
        {
            $this->title = $title;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function setDescription(?string $description): void
        {
            $this->description = $description;
        }

        public function getAuthor(): ?User
        {
            return $this->author;
        }

        public function setAuthor(?User $author): void
        {
            $this->author = $author;
        }

        public function getPublished(): ?bool
        {
            return $this->published;
        }

        public function setPublished(?bool $published): void
        {
            $this->published = $published;
        }

        public function getDateCreated(): string
        {
            return $this->dateCreated->format("d/m/Y");
        }

        public function setDateCreated(?\DateTimeImmutable $dateCreated): void
        {
            $this->dateCreated = $dateCreated;
        }

        public function getDateModified(): string
        {
            if ($this->dateModified != null) {
                return $this->dateModified->format("d/m/y");
            }
            return "";
        }

        public function setDateModified(?\DateTimeImmutable $dateModified): void
        {
            $this->dateModified = $dateModified;
        }

        public function getWishCategory(): ?WishCategory
        {
            return $this->wishCategory;
        }

        public function setWishCategory(?WishCategory $wishCategory): void
        {
            $this->wishCategory = $wishCategory;
        }

        /**
         * @return Collection<int, Comment>
         */
        public function getComments(): Collection
        {
            return $this->comments;
        }

        public function addComment(Comment $comment): static
        {
            if (!$this->comments->contains($comment)) {
                $this->comments->add($comment);
                $comment->setWish($this);
            }

            return $this;
        }

        public function removeComment(Comment $comment): static
        {
            if ($this->comments->removeElement($comment)) {
                // set the owning side to null (unless already changed)
                if ($comment->getWish() === $this) {
                    $comment->setWish(null);
                }
            }

            return $this;
        }

    }
