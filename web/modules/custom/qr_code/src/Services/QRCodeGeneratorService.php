<?php

namespace Drupal\qr_code\Services;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Examples\QRImageWithLogo;

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
        'version' => 7,
        'eccLevel' =>  $errorCorrectionLevel,
        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
      ]
    );
    \Drupal::logger('qr_code')->notice($logoPath);
    if(!empty($logoPath)) {
      $options->logoSpaceWidth = 13;
      $options->logoSpaceHeight = 13;
      $options->scale = 5;
      $options->imageTransparent = false;

      $qrOutputInterface = new QRImageWithLogo($options, (new QRCode($options))->getMatrix($text));
      $qrCode = $qrOutputInterface->dump(null, $logoPath);
    } else {
      // Encode the text into a QR code.
      $qrCode =(new QRCode($options))->render($text);
    }
    
    return $qrCode;
  }
}
