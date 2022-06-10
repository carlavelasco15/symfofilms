<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PeliculaRepository;

class DefaultController extends AbstractController
{
    /**
    * @Route("/", name="portada", methods={"GET"})
    */
    public function index(PeliculaRepository $peliRepo): Response
    {

        $limit = $this->getParameter('app.portada_covers');

        $peliculas = $peliRepo->covers();

        return $this->render('portada.html.twig', [
            'peliculas' => $peliculas
        ]);
    }
}
