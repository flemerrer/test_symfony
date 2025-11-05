<?php

    namespace App\Entity;

    use App\Repository\WishRepository;
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
        #[ORM\Column(type: Types::INTEGER, nullable: false)]
        private ?int $idUser;
        #[Assert\NotBlank(message: 'Please enter a title for your wish.')]
        #[Assert\Length(min: 2, max: 50, minMessage: 'Min 2 characters!', maxMessage: 'Max 250 characters!')]
        #[ORM\Column(type: Types::STRING, length: 250, nullable: false)]
        private ?string $title;
        #[Assert\NotBlank(message: 'Please type your name or username')]
        #[Assert\Length(min: 10, max: 2000, minMessage: 'Min 10 characters!', maxMessage: 'Max 2000 characters!')]
        #[ORM\Column(type: Types::STRING)]
        private ?string $description;
        #[Assert\NotBlank(message: 'Please type your name or username')]
        #[Assert\Length(min: 2, max: 50, minMessage: 'Min 2 characters!', maxMessage: 'Max 50 characters!')]
        #[ORM\Column(type: Types::STRING, length: 50, nullable: false)]
        private ?string $author;
        #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
        private ?bool $published = null;
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        private ?\DateTimeImmutable $dateCreated = null;
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
        private ?\DateTimeImmutable $dateModified = null;

        #[ORM\Column(type: Types::STRING, nullable: true)]
        private string $imageFilename = '';

        public function getImageFilename(): string
        {
            return $this->imageFilename;
        }

        public function setImageFilename(string $imageFilename): void
        {
            $this->imageFilename = $imageFilename;
        }

        public function __construct()
        {
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

        public function getIdUser(): int
        {
            return $this->idUser;
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

        public function getAuthor(): ?string
        {
            return $this->author;
        }

        public function setAuthor(?string $author): void
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
            return $this->dateModified->format("d/m/Y");
        }

        public function setDateModified(?\DateTimeImmutable $dateModified): void
        {
            $this->dateModified = $dateModified;
        }

    }
