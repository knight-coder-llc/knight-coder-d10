<?php

namespace Drupal\qr_code\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Utility\UrlHelper;
use Drupal\qr_code\Services\QRCodeGeneratorService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QRCodeController extends ControllerBase {

  /**
   * The QRCodeGeneratorService.
   *
   * @var \Drupal\qr_code\Services\QRCodeGeneratorService
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
  public function generate(Request $request) {
    // Use the QRCodeGeneratorService to generate a QR code.
    // $text = 'https://www.example.com';
    // $size = 300;
    // $eccLevel = 'medium';
    // $logoPath = '/path/to/logo.png';
    $text = $request->query->get('text');
    $size = $request->query->get('size');
    $eccLevel = $request->query->get('eccLevel');
    $logoPath = $request->query->get('logoPath');

    // decode the url
    $decoded_url = UrlHelper::decodePath($text);
    $imageData = $this->qrCodeGeneratorService->generateQRCode($decoded_url, $size, $eccLevel, $logoPath);

    // return the markup
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
