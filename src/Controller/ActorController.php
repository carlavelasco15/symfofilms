<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ActorRepository;
use App\Entity\Actor;
use App\Form\ActorFormType;
use App\Form\ActorDeleteFormType;


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
    * @Route("/create", name="create", methods={"GET", "POST"})
    */
    public function create(
        Request $request, 
        ActorRepository $actorRepository
        ): Response
    {
        $actor = new Actor();

        $formulario = $this->createForm(ActorFormType::class, $actor);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {
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
    * @Route("/edit/{id}", name="edit")
    */
    public function edit(
        Actor $actor, 
        Request $request, 
        ActorRepository $actorRepository
        ): Response
    {

        $formulario = $this->createForm(ActorFormType::class, $actor);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {
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
        ActorRepository $actorRepository
        ): Response
    {

        $formulario = $this->createForm(ActorDeleteFormType::class, $actor);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {
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
