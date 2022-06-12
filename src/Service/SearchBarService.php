<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Doctrine\ORM\EntityManagerInterface;


class SearchBarService {

    public $campo='id', $valor='%', $orden='id', $sentido='ASC', $limite=5;
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function search(string $entityType): array {
        return $this->em->createQuery(
            "SELECT p
            FROM $entityType p
            WHERE p.$this->campo LIKE :valor
            ORDER BY p.$this->orden $this->sentido
            "
        )
        ->setParameter('valor', '%'.$this->valor.'%')
        ->setMaxResults($this->limite)
        ->getResult();
    }
   
}


