<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;

class UrlHelper
{
    /**
     * Construir la URL completa para la imagen de perfil.
     *
     * @param string $filename
     * @return string
     */
    public static function getServerFtpUrl(string $file_path): string
    {
        Log::info('getServerFtpUrl');
        $ftpServerUrl = env('FTP_SERVER_URL', 'http://example.com');

        // Asegúrate de que el URL del servidor termina con una barra
        if (substr($ftpServerUrl, -1) !== '/') {
            $ftpServerUrl .= '/';
        }

        return $ftpServerUrl . $file_path;
    }
}
