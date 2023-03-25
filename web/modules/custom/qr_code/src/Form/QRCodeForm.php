<?php

namespace Drupal\qr_code\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class QrCodeForm extends FormBase {

  /**
   * The redirect destination service.
   *
   * @var \Drupal\Core\Routing\RedirectDestinationInterface
   */
  protected $redirectDestination;

  /**
   * MyForm constructor.
   *
   * @param \Drupal\Core\Routing\RedirectDestinationInterface $redirect_destination
   *   The redirect destination service.
   */
  public function __construct(RedirectDestinationInterface $redirect_destination) {
    $this->redirectDestination = $redirect_destination;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('redirect.destination')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'qr_code_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Add a text field for the user to enter the QR code data.
    $form['qr_code_data'] = [
      '#type' => 'textfield',
      '#title' => $this->t('QR Code Data'),
      '#required' => TRUE,
    ];

    // create upload field for logo
    $form['qr_code_logo'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('QR Code Logo'),
      '#upload_location' => 'public://qr_code_logo',
      '#upload_validators' => [
        'file_validate_extensions' => ['png jpg jpeg'],
      ],
      '#required' => FALSE,
    ];

    // a logo is uploaded, then show the size and ecc level options
    $form['qr_code_size'] = [
      '#type' => 'select',
      '#title' => $this->t('QR Code Size'),
      '#options' => [
        '100' => $this->t('100'),
        '200' => $this->t('200'),
        '300' => $this->t('300'),
        '400' => $this->t('400'),
        '500' => $this->t('500'),
      ],
      '#default_value' => '300',
      '#required' => FALSE,
    ];

    $form['qr_code_ecc_level'] = [
      '#type' => 'select',
      '#title' => $this->t('QR Code ECC Level'),
      '#options' => [
        'low' => $this->t('Low'),
        'medium' => $this->t('Medium'),
        'quartile' => $this->t('Quartile'),
        'high' => $this->t('High'),
      ],
      '#default_value' => 'medium',
      '#required' => FALSE,
    ];

    // Add a submit button to generate the QR code.
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate QR Code'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get the QR code logo from the form submission.
    $qrCodeLogo = $form_state->getValue('qr_code_logo');
    
    // Get the file path of the QR code logo.
    $qrCodeLogoPath = '';
    // Get size and ecc level
    $qrCodeSize = $form_state->getValue('qr_code_size');
    $qrCodeEccLevel = $form_state->getValue('qr_code_ecc_level');

    if (!empty($qrCodeLogo)) {
      $qrCodeLogoPath = \Drupal::service('file_system')->realpath($qrCodeLogo[0]);
    }

    // Get the QR text from the form submission.
    $qrText = urlencode($form_state->getValue('qr_code_data'));
    // Send the values to the QR code generator controller.
    $url = Url::fromRoute('qr_code.generate')
    ->setOption('query',[
      'text' => $qrText,
      'size' => $qrCodeSize,
      'ecc_level' => $qrCodeEccLevel,
      'logo_path' => $qrCodeLogoPath,
    ]);

    $response = new RedirectResponse($url->toString());
    $response->send();
  }
}
