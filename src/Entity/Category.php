<?php

    namespace App\Entity;

    use ApiPlatform\Metadata\ApiResource;
    use ApiPlatform\Metadata\Get;
    use ApiPlatform\Metadata\GetCollection;
    use App\Models\CreateCategoryDTO;
    use App\Repository\CategoryRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Serializer\Annotation\Groups;
    use Symfony\Component\Validator\Constraints as Assert;

//    #[ApiResource(
//        operations: [
//            new Get(normalizationContext: ['groups' => 'getCategories']),
//            new GetCollection(normalizationContext: ['groups' => 'getCategories']),
//            new Post(denormalizationContext: ['groups' => 'postCategory']),]
//    )]
//        #[ApiResource(normalizationContext: ['groups' => 'getCategories'])]
    #[ORM\Entity(repositoryClass: CategoryRepository::class)]
    class Category
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        #[Groups(['getCategories', 'getCategoriesFull'])]
        private ?int $id = null;

        #[ORM\Column(length: 180, unique: true)]
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 180)]
        #[Groups(['getCategories', 'getCategoriesFull', 'postCategory'])]
        private ?string $name = null;

        #[ORM\Column]
        private ?\DateTimeImmutable $dateCreated = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $dateModified = null;

        /**
         * @var Collection<int, Course>
         */
        #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'category')]
        #[Groups(['getCategoriesFull'])]
        private Collection $courses;

        public function __construct()
        {
            $this->courses = new ArrayCollection();
            $this->dateCreated = new \DateTimeImmutable();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getName(): ?string
        {
            return mb_strtoupper($this->name);
        }

        public function setName(string $name): static
        {
            $this->name = $name;

            return $this;
        }

        public function getDateCreated(): ?\DateTimeImmutable
        {
            return $this->dateCreated;
        }

        public function setDateCreated(\DateTimeImmutable $dateCreated): static
        {
            $this->dateCreated = $dateCreated;

            return $this;
        }

        public function getDateModified(): ?\DateTimeImmutable
        {
            return $this->dateModified;
        }

        public function setDateModified(?\DateTimeImmutable $dateModified): static
        {
            $this->dateModified = $dateModified;

            return $this;
        }

        /**
         * @return Collection<int, Course>
         */
        public function getCourses(): Collection
        {
            return $this->courses;
        }

        public function addCourse(Course $course): static
        {
            if (!$this->courses->contains($course)) {
                $this->courses->add($course);
                $course->setCategory($this);
            }

            return $this;
        }

        public function removeCourse(Course $course): static
        {
            if ($this->courses->removeElement($course)) {
                // set the owning side to null (unless already changed)
                if ($course->getCategory() === $this) {
                    $course->setCategory(null);
                }
            }

            return $this;
        }

        public static function createFromDto(CreateCategoryDTO $dto): Category
        {
            $category = new Category();
            $category->setName($dto->name);
            return $category;
        }
    }
