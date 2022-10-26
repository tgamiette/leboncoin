<?php

namespace App\Service;

use App\Entity\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
        private string $targetDirectory
    )
    {
    }

    public function uploadFile(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            throw new $e;
        }

        return $fileName;
    }

    private function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

}
