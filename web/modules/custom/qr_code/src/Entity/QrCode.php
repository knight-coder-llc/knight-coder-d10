<?php
namespace Drupal\qr_code\Entity;

use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines the QR code entity.
 *
 * @ContentEntityType(
 *   id = "qr_code",
 *   label = @Translation("QR Code"),
 *   base_table = "qr_code",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "label" = "label",
 *     "image_data" = "image_data",
 *     "status" = "status",
 *     "created" = "created",
 *     "changed" = "changed",
 *     "expiration_date" = "expiration_date"
 *   },
 *   handlers = {
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "list_builder" = "Drupal\Core\Entity\EntityListBuilder",
 *     "form" = {
 *   "default" = "Drupal\Core\Entity\ContentEntityForm",
 *     "add" = "Drupal\Core\Entity\ContentEntityForm",
 *     "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *     "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *   },
 *     "access" = "Drupal\qr_code\QrCodeEntityAccessControlHandler",
 *   },
 *   field_ui_base_route = "entity.qr_code.settings",
 *   links = {
 *     "canonical" = "/qr_code/{qr_code}",
 *     "add-form" = "/qr_code/add",
 *     "edit-form" = "/qr_code/{qr_code}/edit",
 *     "delete-form" = "/qr_code/{qr_code}/delete",
 *     "collection" = "/admin/content/qr_code",
 *   },
 *   admin_permission = "administer_qr_code",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 * )
 */
class QrCode extends ContentEntityBase {
  use EntityChangedTrait;

  public function getName() {
    return $this->get('label')->value;
  }

  public function setName($name) {
    $this->set('label', $name);
    return $this;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the QR Code entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the QR Code entity.'))
      ->setReadOnly(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(new TranslatableMarkup('Language'))
      ->setDescription(new TranslatableMarkup('The language code of the QR code entity.'));

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setDescription(t('The name of the QR Code entity.'))
      ->setRequired(TRUE);

    $fields['image_data'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Image Data'))
      ->setDescription(t('The base64-encoded image data of the QR Code.'))
      ->setRequired(TRUE);
      
    $fields['expiration_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Expiration Date'))
      ->setDescription(t('The description of the QR Code entity.'))
      ->setDefaultValue(14 * 24 * 60 * 60) // 14 days
      ->setRequired(FALSE);

    $fields['created'] = BaseFieldDefinition::create('created')
        ->setLabel(t('Created'))
        ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
        ->setLabel(t('Changed'))
        ->setDescription(t('The time that the entity was last edited.'));

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Published'))
      ->setDescription(t('A boolean indicating whether the QR Code is published.'))
      ->setDefaultValue(TRUE);

    return $fields;

    }
}

