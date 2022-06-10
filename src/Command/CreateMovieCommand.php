<?php

namespace App\Command;

use App\Entity\Pelicula;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateMovieCommand extends Command
{
    protected static $defaultName = 'app:create-movie';
    protected static $defaultDescription = 'Add a short description for your command';

    private $entityManager;

    public function __construct(EntityManagerInterface $em) {
        parent::__construct();
        $this->entityManager= $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('titulo', InputArgument::REQUIRED, 'Título')
            ->addArgument('duracion', InputArgument::OPTIONAL, 'Duración', NULL)
            ->addArgument('director', InputArgument::OPTIONAL, 'Director', NULL)
            ->addArgument('genero', InputArgument::OPTIONAL, 'Género', NULL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white;bg=black>Crear película</>');

        $titulo = $input->getArgument('titulo');
        $duracion = $input->getArgument('duracion');
        $director = $input->getArgument('director');
        $genero = $input->getArgument('genero');
        
        $peli = new Pelicula();
        $peli
            ->setTitulo($titulo)
            ->setDuracion($duracion)
            ->setDirector($director)
            ->setGenero($genero);

        $this->entityManager->persist($peli);
        $this->entityManager->flush();
        
        $output->writeln("<fg=white;bg=green>Película $titulo creada!</>");
 
        return Command::SUCCESS;
    }
}
