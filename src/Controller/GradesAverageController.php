<?php

namespace App\Controller;
use App\Repository\GradeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GradesAverageController extends AbstractController
{    
    public function __construct(private GradeRepository $gradeRepository){}

    public function __invoke(): ?float
    {
        //call query to calculate average
        $avg = $this->gradeRepository->getGradesAverage();
        if($avg)
        {
            return number_format($avg, 2);
        }
        return $avg;
    }
}