<?php

namespace Drupal\qr_code\Services;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;

class QRCodeGeneratorService {

  protected $imageFactory;

  public function __construct() {
    $this->imageFactory = \Drupal::service('image.factory');
  }

  public function generateQRCodeImage($text, $size = 300, $eccLevel = 'medium', $logoPath = '') {

    // Set error correction level.
    switch ($eccLevel) {
      case 'low':
        $errorCorrectionLevel = ErrorCorrectionLevel::L;
        break;
      case 'high':
        $errorCorrectionLevel = ErrorCorrectionLevel::H;
        break;
      case 'medium':
      default:
        $errorCorrectionLevel = ErrorCorrectionLevel::M;
        break;
    }

    // Encode the text into a QR code.
    $qrCode = Encoder::encode($text, $errorCorrectionLevel);

    // Set the QR code size.
    $renderer = new Png();
    $renderer->setWidth($size);
    $renderer->setHeight($size);

    // Add a logo to the QR code if specified.
    if (!empty($logoPath)) {
      $logoImage = $this->imageFactory->get($logoPath);
      if (!empty($logoImage)) {
        $logoWidth = $size / 5;
        $logoHeight = $size / 5;
        $logoImage->resize($logoWidth, $logoHeight);
        $logoImageData = $logoImage->get('png');
        $renderer->setLogoPath($logoImageData);
        $renderer->setLogoWidth($logoWidth);
        $renderer->setLogoHeight($logoHeight);
      }
    }

    // Write the QR code image data.
    $writer = new Writer($renderer);
    $imageData = $writer->writeString($qrCode);

    return $imageData;
  }
}
