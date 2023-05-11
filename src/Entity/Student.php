<?php

namespace App\Entity;

use App\Controller\GradesAverageByStudentController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[Post(
    denormalizationContext: ["groups" => "student:write"]
)]
#[Delete()]
#[Patch(
    denormalizationContext: ["groups" => "student:write"]
)]
#[Get()]
#[Get(
    uriTemplate: "/students/{id}/average",
    controller: GradesAverageByStudentController::class,
    openapiContext: [
        'summary' => 'Retrieves the average of a student\'s grades',
        'responses' => [
            '200' => [
                'description' => "Success return average",
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'float',
                            'example' => 10.50
                        ]
                    ]
                ]
            ]
        ]
    ]
),]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["student:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["student:read", "student:write"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["student:read", "student:write"])]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["student:read", "student:write"])]
    private ?\DateTimeImmutable $dateOfBirth = null;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Grade::class, orphanRemoval: true)]
    private Collection $grades;

    public function __construct()
    {
        $this->grades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeImmutable $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * @return Collection<int, Grade>
     */
    public function getGrades(): Collection
    {
        return $this->grades;
    }

    public function addGrade(Grade $grade): self
    {
        if (!$this->grades->contains($grade)) {
            $this->grades->add($grade);
            $grade->setStudent($this);
        }

        return $this;
    }

    public function removeGrade(Grade $grade): self
    {
        if ($this->grades->removeElement($grade)) {
            // set the owning side to null (unless already changed)
            if ($grade->getStudent() === $this) {
                $grade->setStudent(null);
            }
        }

        return $this;
    }
}
