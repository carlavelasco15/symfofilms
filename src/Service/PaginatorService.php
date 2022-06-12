<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;


class PaginatorService {

    private $limit, $entityType = '', $em;
    private $paginaActual = 1, $total = 0;


    public function __construct(int $limit, EntityManagerInterface $em) {
        $this->em = $em;
        $this->limit = $limit;
    }

    public function setEntityType(string $entityType) {
        $this->entityType = $entityType;
    }

    public function setLimit(string $limit) {
        $this->limit = $limit;
    }

    public function getPaginaActual(): int {
        return $this->paginaActual;
    }

    public function getTotal():int {
        return $this->total;
    }

    public function getTotalPages():int {
        return ceil($this->total/$this->limit);
    }


    public function paginate($dql, int $page) {

        $paginator = new Paginator($dql);

        $paginator->getQuery()
            ->setFirstResult($this->limit * ($page-1))
            ->setMaxResults($this->limit);

        $this->paginaActual = $page;
        $this->total = $paginator->count();


        return $paginator;
    }


    public function findAllEntities(int $paginaActual = 1):Paginator {
        $consulta = $this->em->createQuery(
            "SELECT p
            FROM $this->entityType p
            ORDER BY p.id DESC"
        );

        return $this->paginate($consulta, $paginaActual);
    }

}


