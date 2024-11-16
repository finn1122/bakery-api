<?php

namespace App\Features\Ftp\Domain\Repositories;

interface FtpRepositoryInterface
{
    /**
     * Guardar el archivo en el servidor FTP.
     *
     * @param string $directory
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function saveFileToFtp($directory, $file);

    /**
     * Guardar la imagen de perfil de la panadería.
     *
     * @param int $bakeryId
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    public function saveBakeryProfileFile($bakeryId, $file);
}
