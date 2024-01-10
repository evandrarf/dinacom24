<?php

namespace App\Actions\Utility;

use App\Services\FileService;
use chillerlan\QRCode\QRCode;

class GenerateQrCode
{
    public function handle($text)
    {
        $fileService = new FileService();
        $qrCode =  new QRCode();

        $folderPath = storage_path('app/tmp/');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $tmpFileName = storage_path('app/tmp/' . $text . '.svg');

        $qrCode->render($text, $tmpFileName);

        $stored_image = $fileService->storeFile($tmpFileName, 'qr_code');

        unlink($tmpFileName);

        return $stored_image;
    }
}
