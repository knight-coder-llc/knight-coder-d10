<?php

namespace Drupal\qr_code\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRCodeGeneratorService {

  protected $imageFactory;

  public function __construct() {
    $this->imageFactory = \Drupal::service('image.factory');
  }

  public function generateQRCode($text, $size = 300, $eccLevel = 'medium', $logoPath = '') {
    
    // Set error correction level.
    switch ($eccLevel) {
      case 'low':
        $errorCorrectionLevel = QRCode::ECC_L;
        break;
      case 'medium':
        $errorCorrectionLevel = QRCode::ECC_M;
        break;
      case 'high':
      default:
        $errorCorrectionLevel = QRCode::ECC_H;
        break;
    }

    $options = new QROptions(
      [
        'eccLevel' =>  $errorCorrectionLevel,
        'outputType' => QRCode::OUTPUT_MARKUP_SVG,
        'version' => 5,
      ]
    );

    // Set the QR code size.
    // $renderer = new Png();
    // $renderer->setWidth($size);
    // $renderer->setHeight($size);

    // // Add a logo to the QR code if specified.
    // if (!empty($logoPath)) {
    //   $logoImage = $this->imageFactory->get($logoPath);
    //   if (!empty($logoImage)) {
    //     $logoWidth = $size / 5;
    //     $logoHeight = $size / 5;
    //     $logoImage->resize($logoWidth, $logoHeight);
    //     $logoImageData = $logoImage->get('png');
    //     $renderer->setLogoPath($logoImageData);
    //     $renderer->setLogoWidth($logoWidth);
    //     $renderer->setLogoHeight($logoHeight);
    //   }
    // }

    // // Write the QR code image data.
    // $writer = new Writer($renderer);
    // $imageData = $writer->writeString($qrCode);

    // Encode the text into a QR code.
    $qrCode =(new QRCode($options))->render($text);
    
    \Drupal::logger('qr_code')->notice($qrCode);
    return $qrCode;
  }
}
