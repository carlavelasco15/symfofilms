<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileService2 {

    private $targetDirectory;

    public function __construct($targetDirectory) {
        $this->targetDirectory = $targetDirectory;
    }

    public function setTargetDirectory($targetDirectory) {
        $this->targetDirectory = $targetDirectory;
        return $this;
    }

    public function upload(UploadedFile $file, bool $nombreUnico = true) {

        $nuevoNombre = $nombreUnico ? uniqid().'.'.$file->guessExtension() : $file->getClientOriginalName();

        try {
            $file->move($targetDirectory, $nuevoNombre);
        } catch (FileException $e) {
            return NULL;
        }

        return $nuevoNombre;
    }


    public function replace(UploadedFile $nuevoFichero, ?string $ficheroAntiguo = NULL, bool $uniqueName = false) {

        $nuevoNombre = $nombreUnico ? uniqid().'.'.$nuevoFichero->guessExtension() : $nuevoFichero->getClientOriginalName();

        
        try {
            $nuevoFichero->move($targetDirectory, $nuevoNombre);

            if($ficheroAntiguo) {
                $filesystem = new Filesystem;
                $filesystem->remove("$this->targetDirectory/$ficheroAntiguo");
            }

        } catch (FileException $e) {
            return $ficheroAntiguo;
        }

        return $nuevoNombre;
    }

}