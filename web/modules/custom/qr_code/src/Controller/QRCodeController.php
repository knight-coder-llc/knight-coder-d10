<?php

namespace Drupal\my_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\my_module\Services\QRCodeGeneratorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QRCodeController extends ControllerBase {

  /**
   * The QRCodeGeneratorService.
   *
   * @var \Drupal\my_module\Services\QRCodeGeneratorService
   */
  protected $qrCodeGeneratorService;

  /**
   * {@inheritdoc}
   */
  public function __construct(QRCodeGeneratorService $qrCodeGeneratorService) {
    $this->qrCodeGeneratorService = $qrCodeGeneratorService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('qr_code.qr_code_generator')
    );
  }

  /**
   * Generate a QR code image.
   * 
   * @param string $text
   * @param int $size
   * @param string $eccLevel
   * @param string $logoPath
   * 
   * @return array
   */
  public function generate($text, $size = 300, $eccLevel = 'medium', $logoPath = '') {
    // Use the QRCodeGeneratorService to generate a QR code.
    // $text = 'https://www.example.com';
    // $size = 300;
    // $eccLevel = 'medium';
    // $logoPath = '/path/to/logo.png';
    $imageData = $this->qrCodeGeneratorService->generateQRCode($text, $size, $eccLevel, $logoPath);

    // Render the QR code image in a template.
    return [
      '#theme' => 'qr_code_template',
      '#image_data' => $imageData,
    ];
  }

    /**
     * Download a QR code image.
     * 
     * @param Request $request
     * @param QRCodeGeneratorService $qrCodeGeneratorService
     * 
     * @return Response
     */
    public function downloadQRCode(Request $request, QRCodeGeneratorService $qrCodeGeneratorService){
        // Get the image data from the QRCodeGeneratorService
        $image_data = $qrCodeGeneratorService->generateQRCode($request->get('data'));

        // Create a response object with the image data
        $response = new Response($image_data);

        // Set the response headers to force download and set the filename
        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'qr_code.png'
        );
        $response->headers->set('Content-Disposition', $disposition);

        // Set the content type to image/png
        $response->headers->set('Content-Type', 'image/png');

        // Return the response
        return $response;
    }
}
