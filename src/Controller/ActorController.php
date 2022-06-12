<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileService;
use App\Repository\ActorRepository;
use App\Entity\Actor;
use App\Form\ActorFormType;
use App\Form\ActorDeleteFormType;
use App\Service\SearchBarService;
use App\Form\SearchBarFormType;


/**
* @Route("/actor", name="actor_")
*/
class ActorController extends AbstractController
{
    /**
     * @Route("es", name="list", methods={"GET"})
     */
    public function list(ActorRepository $actorRepository): Response
    {
        $actores = $actorRepository->findAll();
        return $this->render('actor/list.html.twig', [
            'actores' => $actores,
        ]);
    }

    
     /**
    * @Route("/search", name="search")
    */
    public function search(
        ActorRepository $peliculaRepository,
        SearchBarService $busqueda,
        Request $request): Response

    {

        $formulario = $this->createForm(SearchBarFormType::class, $busqueda, [
            'field_choices' => [
                'Nombre' => 'nombre',
                'Nacionalidad' => 'nacionalidad',
            ],
            'order_choices' => [
                'ID' => 'id',
                'Nacimiento' => 'nacimiento',
                'Nacionalidad' => 'nacionalidad',
                'Nombre' => 'nombre'
            ]
            ]);


        $formulario->get('campo')->setData($busqueda->campo);
        $formulario->get('orden')->setData($busqueda->orden);

        $formulario->handleRequest($request);

        $actores = $busqueda->search('App\Entity\Actor');

        return $this->render('actor/search.html.twig', [
            'actores' => $actores,
            'formulario' => $formulario->createView()
        ]);
    }
    
    /**
    * @Route("/create", name="create", methods={"GET", "POST"})
    */
    public function create(
        Request $request, 
        ActorRepository $actorRepository,
        FileService $uploader
        ): Response
    {
        $actor = new Actor();

        $formulario = $this->createForm(ActorFormType::class, $actor);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {

            $file = $formulario->get('imagen')->getData();
            $uploader->setTargetDirectory($this->getParameter('app.actor_directory.root'));

            if($file)
                $actor->setImagen($uploader->upload($file));


            $actorRepository->add($actor, true);
            $this->addFlash('success', 'Actor creado con éxito.');   
            return $this->redirectToRoute('actor_list');
        }

        return $this->render('actor/create.html.twig', [
                'formulario' => $formulario->createView()
            ]);
    }


    /**
    * @Route("/{id<\d+>}", name="show", methods={"GET"})
    */
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }


     /**
    * @Route("/delete/imagen/{id<\d+>}", name="imagen_delete")
    */
    public function imageDelete(
        Actor $actor,
        Request $request,
        FileService $uploader,
        ActorRepository $actorRepository): Response
    {

        $uploader->setTargetDirectory($this->getParameter('app.actor_directory.root'));

        if($imagen = $actor->getImagen()) {
            $uploader->delete($imagen);
            $actor->setImagen(NULL);
            $actorRepository->add($actor, true);
        }

        return $this->redirectToRoute('actor_edit', ['id' => $actor->getId()]);
    }


    /**
    * @Route("/edit/{id}", name="edit")
    */
    public function edit(
        Actor $actor, 
        Request $request, 
        ActorRepository $actorRepository,
        FileService $uploader
        ): Response
    {
        $fileOld = $actor->getImagen();

        $formulario = $this->createForm(ActorFormType::class, $actor);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {

            $fileNew = $formulario->get('imagen')->getData();
            $uploader->setTargetDirectory($this->getParameter('app.actor_directory.root'));

            if($fileNew)
                $actor->setImagen($uploader->replace($fileNew, $fileOld));

            $actorRepository->add($actor, true);
            $this->addFlash('success', "Actor '" .$actor->getNombre(). "' modificado con éxito.");
            return $this->redirectToRoute('actor_show', [
                'id' => $actor->getId(),
            ]);
        }

        return $this->render('actor/edit.html.twig', [
            'actor' => $actor,
            'formulario' => $formulario->createView()
        ]);
    }


    /**
    * @Route("/delete/{id}", name="delete")
    */
    public function delete (
        Actor $actor, 
        Request $request, 
        ActorRepository $actorRepository,
        FileService $uploader
        ): Response
    {

        $formulario = $this->createForm(ActorDeleteFormType::class, $actor);
        $formulario->handleRequest($request);
        $uploader->setTargetDirectory($this->getParameter('app.actor_directory.root'));

        if($formulario->isSubmitted() && $formulario->isValid()) {

            if($file = $actor->getImagen())
                $uploader->delete($file);

            $this->addFlash('success', "Ha eliminado el actor '" .$actor->getNombre()."' con éxito.");
            $actorRepository->remove($actor, true);
            return $this->redirectToRoute('actor_list');
        }

        return $this->render('actor/delete.html.twig', [
            'formulario' => $formulario->createView(),
            'actor' => $actor
        ]);
    }

}
