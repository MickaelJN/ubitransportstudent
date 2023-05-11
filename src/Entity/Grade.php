<?php

namespace App\Entity;

use App\Controller\GradesAverageController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\GradeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
#[ApiResource(
    operations: [
        new getCollection(
            name: "average",
            uriTemplate: "/grades/average",
            controller: GradesAverageController::class,
            paginationEnabled: false,
            openapiContext: [
                'summary' => 'Retrieves the average of all grades',
                'parameters' => [],
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
        ),
        new Post()
    ],
    normalizationContext: ["groups" => "grade:read"],
    denormalizationContext: ["groups" => "grade:write"]
)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["grade:read"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Assert\Range(
        min: 0,
        max: 20,
        notInRangeMessage: 'Score must be between {{ min }} and {{ max }}'
    )]
    #[Groups(["grade:read", "grade:write"])]
    private ?string $score = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    #[Groups(["grade:read", "grade:write"])]
    private ?string $subject = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["grade:write"])]
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): self
    {
        $this->student = $student;

        return $this;
    }
}
