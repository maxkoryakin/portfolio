<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private string $uploadsDirectory)
    {
    }

    public function upload(UploadedFile $file, string $subDirectory): string
    {
        $targetDir = rtrim($this->uploadsDirectory, '/').'/'.trim($subDirectory, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $extension = $file->guessExtension() ?: 'bin';
        $fileName = bin2hex(random_bytes(16)).'.'.$extension;
        $file->move($targetDir, $fileName);

        return $fileName;
    }

    public function remove(?string $fileName, string $subDirectory): void
    {
        if (!$fileName) {
            return;
        }

        $path = rtrim($this->uploadsDirectory, '/').'/'.trim($subDirectory, '/').'/'.$fileName;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
