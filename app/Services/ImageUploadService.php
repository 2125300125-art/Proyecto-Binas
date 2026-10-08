<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    /**
     * Formatos permitidos por la API.
     */
    private const FORMATOS_PERMITIDOS = ['jpeg', 'png', 'jpg', 'webp', 'gif', 'bmp'];

    /**
     * Tamaño máximo en kilobytes (ejemplo: 10 MB = 10240 KB).
     */
    private const TAMANO_MAXIMO_KB = 10240;

    /**
     * Sube un archivo de imagen a la API externa (ImgBB) y devuelve su URL pública.
     *
     * @param UploadedFile|null $archivo
     * @return string URL pública de la imagen
     * @throws \InvalidArgumentException|\RuntimeException
     */
    public function subirImagen(?UploadedFile $archivo): string
    {
        // 1. Validar que se haya enviado un archivo
        if ($archivo === null || !$archivo->isValid()) {
            throw new \InvalidArgumentException('No se ha seleccionado ningún archivo de imagen válido.');
        }

        // 2. Validar que sea realmente una imagen
        $mime = $archivo->getMimeType() ?? '';
        if (!str_starts_with($mime, 'image/')) {
            throw new \InvalidArgumentException('El archivo seleccionado no es una imagen válida.');
        }

        // 3. Validar el formato/extensión permitida
        $extension = strtolower($archivo->getClientOriginalExtension());
        if (!in_array($extension, self::FORMATOS_PERMITIDOS, true)) {
            throw new \InvalidArgumentException(
                'El formato .' . $extension . ' no está permitido. Formatos aceptados: ' . implode(', ', self::FORMATOS_PERMITIDOS)
            );
        }

        // 4. Validar el tamaño del archivo
        $tamanoKb = $archivo->getSize() / 1024;
        if ($tamanoKb > self::TAMANO_MAXIMO_KB) {
            throw new \InvalidArgumentException(
                'La imagen supera el tamaño máximo permitido de ' . (self::TAMANO_MAXIMO_KB / 1024) . ' MB.'
            );
        }

        // 5. Obtener configuración de la API desde config/services.php
        $apiKey = config('services.imgbb.key');
        $apiUrl = config('services.imgbb.url', 'https://api.imgbb.com/1/upload');

        if (empty($apiKey)) {
            Log::error('ImgBB API: La clave de API no está configurada en .env (IMGBB_API_KEY).');
            throw new \RuntimeException('La clave de la API de almacenamiento de imágenes no está configurada en el sistema.');
        }

        // 6. Preparar la imagen en Base64 para envío HTTP confiable
        $contenido = file_get_contents($archivo->getRealPath());
        if ($contenido === false) {
            throw new \RuntimeException('No se pudo leer el contenido del archivo de imagen.');
        }

        $base64 = base64_encode($contenido);

        // 7. Enviar la imagen a la API externa mediante HTTP POST
        try {
            $response = Http::timeout(25)
                ->asForm()
                ->post($apiUrl, [
                    'key'   => $apiKey,
                    'image' => $base64,
                    'name'  => pathinfo($archivo->getClientOriginalName(), PATHINFO_FILENAME),
                ]);
        } catch (\Exception $e) {
            Log::error('Error de conexión con la API de imágenes: ' . $e->getMessage());
            throw new \RuntimeException('La API externa de almacenamiento no está disponible temporalmente. Intente más tarde.');
        }

        // 8. Procesar la respuesta de la API externa
        if (!$response->successful()) {
            $codigoError = $response->status();
            $mensajeError = $response->json('error.message') ?? 'La API externa rechazó la solicitud.';
            Log::error("ImgBB API Error [{$codigoError}]: {$mensajeError}");

            throw new \RuntimeException("La API de imágenes devolvió un error: {$mensajeError}");
        }

        $datos = $response->json();

        // 9. Extraer la URL pública permanente
        $urlPublica = $datos['data']['url'] ?? $datos['data']['display_url'] ?? null;

        if (empty($urlPublica) || !filter_var($urlPublica, FILTER_VALIDATE_URL)) {
            Log::error('ImgBB API: No se obtuvo una URL pública válida en la respuesta.', $datos);
            throw new \RuntimeException('No se pudo obtener una URL pública válida de la API externa.');
        }

        return $urlPublica;
    }
}

