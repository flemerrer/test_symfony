<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private ?string $name = null;
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $content = null;
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private ?int $duration = null;
    #[ORM\Column(type: Types::BOOLEAN, nullable: false)]
    private ?bool $published = null;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?\DateTimeImmutable $dateCreated = null;
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateModified = null;

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
        return $this->dateCreated->format("d/m/y");
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
        return "name: " . $this->getName() . "(". $this->getId() . ")\n"
            . "duration: " . $this->getDuration() . "\n"
            . "published: " . $this->getPublished() . "\n"
            . "dateCreated: " . $this->getDateCreated() . "\n"
            . "dateModified: " . $this->getDateModified();
    }


}
