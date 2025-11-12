<?php

    namespace App\Entity;

    use App\Repository\CourseRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

    #[ORM\Entity(repositoryClass: CourseRepository::class)]
    class Course
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        #[Groups(['getCategoriesFull, getCourse'])]
        private ?int $id = null;
        #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
        #[Groups(['getCategoriesFull, getCourse'])]
        private ?string $name = null;
        #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
        #[Groups(['getCourse'])]
        private ?string $content = null;
        #[Assert\NotBlank()]
        #[Assert\Range(min: 1, max: 50, notInRangeMessage: 'Duration must be between 1 and 50!')]
        #[ORM\Column(type: Types::INTEGER, nullable: false)]
        #[Groups(['getCourse'])]
        private ?int $duration = null;
        #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
        #[Groups(['getCourse'])]
        private ?bool $published = null;
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
        private ?\DateTimeImmutable $dateCreated = null;
        #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
        private ?\DateTimeImmutable $dateModified = null;

        #[ORM\ManyToOne(inversedBy: 'courses')]
        private ?Category $category = null;

        /**
         * @var Collection<int, Trainer>
         */
        #[ORM\ManyToMany(targetEntity: Trainer::class, inversedBy: 'courses')]
        private Collection $trainers;

        public function __construct()
        {
            $this->trainers = new ArrayCollection();
            $this->setDateCreated(new \DateTimeImmutable());
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setName(string $name): void
        {
            $this->name = $name;
        }

        public function getContent(): string
        {
            return $this->content;
        }

        public function setContent(string $content): void
        {
            $this->content = $content;
        }

        public function getDuration(): string
        {
            return $this->duration;
        }

        public function setDuration(string $duration): void
        {
            $this->duration = $duration;
        }

        public function isPublished(): bool
        {
            return $this->published;
        }

        public function setPublished(bool $published): void
        {
            $this->published = $published;
        }

        public function getDateCreated(): string
        {
            if ($this->dateCreated != null) {
                return $this->dateCreated->format("d/m/y");
            }
            return "";
        }

        public function setDateCreated(\DateTimeImmutable $dateCreated): void
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

        public function setDateModified(\DateTimeImmutable $dateModified): void
        {
            $this->dateModified = $dateModified;
        }

        public function getPublished(): ?bool
        {
            return $this->published;
        }

        public function __toString(): string
        {
            return "name: " . $this->getName() . "(" . $this->getId() . ")\n"
                . "duration: " . $this->getDuration() . "\n"
                . "published: " . $this->getPublished() . "\n"
                . "dateCreated: " . $this->getDateCreated() . "\n"
                . "dateModified: " . $this->getDateModified();
        }

        public function getCategory(): ?Category
        {
            return $this->category;
        }

        public function setCategory(?Category $category): static
        {
            $this->category = $category;

            return $this;
        }

        /**
         * @return Collection<int, Trainer>
         */
        public function getTrainers(): Collection
        {
            return $this->trainers;
        }

        public function addTrainer(Trainer $trainer): static
        {
            if (!$this->trainers->contains($trainer)) {
                $this->trainers->add($trainer);
            }

            return $this;
        }

        public function removeTrainer(Trainer $trainer): static
        {
            $this->trainers->removeElement($trainer);

            return $this;
        }


    }
