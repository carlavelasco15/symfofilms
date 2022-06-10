<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileService {

    public $targetDirectory;

    public function __construct(String $targetDirectory) {
        $this->targetDirectory = $targetDirectory;
    }


    /* Cambiar el nombre del fichero (si está indicado) y moverlo a la carpeta indicada */

    public function upload(UploadedFile $file, bool $nombreUnico = true) : ?string {

        $fichero = $nombreUnico ? uniqid(). '.' .$file->guessExtension() : $file->getClientOriginalName();

        try {
           $file->move($this->targetDirectory, $fichero);
        } catch(FileException $e) {
            return NULL;
        }

        return $fichero;
    }



    public function replace(UploadedFile $file, ?string $anterior = NULL, bool $nombreUnico = true) : ?string {

        $fichero = $nombreUnico ? uniqid() .'.'. $file->guessExtension() : $file->getClientOriginalName();

        try {
            $file->move($this->targetDirectory, $fichero);

            if($anterior) {
                $fileSystem = new Filsystem();
                $fileSystem->remove("$this->targetDirectory/$anterior");
            }

        } catch (FileException $e) {
            return $anterior;
        }

        return $fichero;
    }


    public function delete(string $fileName) {
        $filesystem = new Filesystem;
        $filesystem->remove("$targetDirectory/$fileName");
    }



    public function setTargetDirectory($targetDirectory) {
        $this->targetDirectory = $targetDirectory;
        return $this;
    }

    public function getTargetDirectory() {
        return $this->targetDirectory;
    }


}


