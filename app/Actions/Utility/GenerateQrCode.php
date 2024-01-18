<?php

namespace App\Actions\Utility;

use App\Services\FileService;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRGdImagePNG;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class GenerateQrCode
{
    public function handle($text)
    {
        $fileService = new FileService();
        $options = new QROptions;
        $options->outputType = 'png';

        $qrCode =  new QRCode($options);

        $folderPath = storage_path('app/tmp/');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $tmpFileName = storage_path('app/tmp/' . $text . '.png');

        $qrCode->render($text, $tmpFileName);

        $stored_image = $fileService->storeFile($tmpFileName, 'qr_code');

        unlink($tmpFileName);

        return $stored_image;
    }
}
