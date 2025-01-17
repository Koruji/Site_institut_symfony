<?php

namespace App\Service;
use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;

class GenerateCode
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function code($length = 5) : string {
        do {
            $code = "";

            for($i = 0; $i < $length; $i++) {
                $code .= chr(rand(65, 90));
            }

            $existingCode = $this->entityManager->getRepository(Matiere::class)
                ->findOneBy(['code_matiere' => $code]);

        } while ($existingCode !== null);

        return $code;
    }

}