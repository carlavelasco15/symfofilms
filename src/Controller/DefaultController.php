<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PeliculaRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Form\ContactFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class DefaultController extends AbstractController
{
    /**
    * @Route("/", name="portada", methods={"GET"})
    */
    public function index(PeliculaRepository $peliRepo): Response
    {

        $limit = $this->getParameter('app.portada_covers');

        $peliculas = $peliRepo->covers($this->getParameter('app.portada_covers'));

        return $this->render('portada.html.twig', [
            'peliculas' => $peliculas
        ]);
    }


      /**
    * @Route("/contact", name="contacto")
    */
    public function contacto(Request $request, MailerInterface $mailer): Response
    {

        $formulario = $this->createForm(ContactFormType::class);
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()) {

            $datos = $formulario->getData();
            
            $email = new TemplatedEmail();
            $email->from(new Address($datos['email'], $datos['nombre']))
                ->to($this->getParameter('app.admin_email'))
                ->subject($datos['asunto'])
                ->htmlTemplate('email/contact.html.twig')
                ->context([
                    'de' => $datos['email'],
                    'nombre' => $datos['nombre'],
                    'asunto' => $datos['asunto'],
                    'mensaje' => $datos['mensaje']
                ]);
            
            $mailer->send($email);

            $this->addFlash('success', 'Mensaje enviado correctamente.');
            return $this->redirectToRoute('portada');
        }

        return $this->render('contacto.html.twig', [
            "formulario" => $formulario->createView()
        ]);
    }
}
