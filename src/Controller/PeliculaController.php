<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PeliculaRepository;
use App\Entity\Pelicula;
use App\Form\PeliculaFormType;
use App\Form\PeliculaDeleteFormType;
use App\Service\FileService;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\SearchBarService;
use App\Form\SearchBarFormType;




/**
* @Route("/pelicula", name="pelicula_")
*/
class PeliculaController extends AbstractController
{

    /**
    * @Route("s", name="list", methods={"GET"})
    */
    public function list(PeliculaRepository $peliculaRepository): Response
    {
        $pelis = $peliculaRepository->findAll();

        return $this->render('pelicula/list.html.twig', [
            'peliculas' => $pelis,
        ]);
    }


     /**
    * @Route("/search", name="search")
    */
    public function search(
                        PeliculaRepository $peliculaRepository, 
                        SearchBarService $busqueda,
                        Request $request): Response
    {
        $formulario = $this->createForm(SearchBarFormType::class, $busqueda, [
            'field_choices' => [
                'Título' => 'titulo',
                'Género' => 'genero',
                'Sinopsis' => 'sinopsis'
            ],
            'order_choices' => [
                'ID' => 'id',
                'Título' => 'titulo',
                'Director' => 'director',
                'Género' => 'genero',
            ]
            ]);

        $formulario->get('campo')->setData($busqueda->campo);
        $formulario->get('orden')->setData($busqueda->orden);

        $formulario->handleRequest($request);

        $pelis = $busqueda->search('App\Entity\Pelicula');

        return $this->render('pelicula/search.html.twig', [
            'peliculas' => $pelis,
            'formulario' => $formulario->createView()
        ]);
    }


    /**
    * @Route("/create", name="create", methods={"GET", "POST"})
    */
    public function create(
        Request $request, 
        PeliculaRepository $peliculaRepository,
        FileService $uploader
        ): Response
    {
        $peli = new Pelicula();

        
        $formulario = $this->createForm(PeliculaFormType::class, $peli);
        $formulario->handleRequest($request);
        
        if($formulario->isSubmitted() && $formulario->isValid()) {
            
            $file = $formulario->get('imagen')->getData();

            if($file)
                $peli->setImagen($uploader->upload($file));


            $peliculaRepository->add($peli, true);
            $this->addFlash('success', 'Película creada con éxito.');   
            return $this->redirectToRoute('pelicula_list');
        }

        return $this->render('pelicula/create.html.twig', [
                'formulario' => $formulario->createView()
            ]);
    }



    /**
    * @Route("/{id<\d+>}", name="show", methods={"GET"})
    */
    public function show(Pelicula $pelicula): Response
    {
        return $this->render('pelicula/show.html.twig', [
            'pelicula' => $pelicula,
        ]);
    }

    /**
    * @Route("/edit/{id}", name="edit")
    */
    public function edit(
        Pelicula $pelicula, 
        Request $request, 
        PeliculaRepository $peliculaRepository,
        FileService $uploaded
        ): Response
    {
        $imagenAntigua = $pelicula->getImagen();

        $formulario = $this->createForm(PeliculaFormType::class, $pelicula);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {

            $imagenNueva = $formulario->get('imagen')->getData();

            if($imagenNueva)
                $pelicula->setImagen($uploaded->replace($imagenNueva, $imagenAntigua));

            $peliculaRepository->add($pelicula, true);
            $this->addFlash('success', "Película '" .$pelicula->getTitulo(). "' modificada con éxito.");
            return $this->redirectToRoute('pelicula_show', [
                'id' => $pelicula->getId(),
            ]);
        }

        return $this->render('pelicula/edit.html.twig', [
            'pelicula' => $pelicula,
            'formulario' => $formulario->createView()
        ]);
    }


     /**
    * @Route("/delete/imagen/{id<\d+>}", name="imagen_delete")
    */
    public function deleteImage(Pelicula $pelicula,
                                Request $request,  
                                PeliculaRepository $peliculaRepository,
                                FileService $uploader):Response {

        if($pelicula->getImagen()) {
            $uploader->delete($pelicula->getImagen());
            $pelicula->setImagen(NULL);
            $peliculaRepository->add($pelicula, true);
        }
        
        return $this->redirectToRoute('pelicula_edit', ['id' => $pelicula->getId()]);
    }


    /**
    * @Route("/delete/{id}", name="delete")
    */
    public function delete(Pelicula $pelicula,
                        Request $request,
                        PeliculaRepository $peliculaRepository,
                        FileService $uploaded): Response
    {
        $formulario = $this->createForm(PeliculaDeleteFormType::class, $pelicula);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {

            if($pelicula->getImagen())
                $uploaded->delete($pelicula->getImagen());
            

            $this->addFlash('success', "Ha eliminado la película '" .$pelicula->getTitulo()."' con éxito.");
            $peliculaRepository->remove($pelicula, true);
            return $this->redirectToRoute('pelicula_list');
        }

        return $this->render('pelicula/delete.html.twig', [
            'formulario' => $formulario->createView(),
            'pelicula' => $pelicula
        ]);
    }

}
