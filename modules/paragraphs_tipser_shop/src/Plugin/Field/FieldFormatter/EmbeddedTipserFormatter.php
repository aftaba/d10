<?php
namespace Drupal\paragraphs_tipser_shop\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'embedded_tipser_shop' formatter.
 *
 * @FieldFormatter(
 *   id = "embedded_tipser_shop",
 *   label = @Translation("Embedded tipser"),
 *   field_types = {
 *     "string", "string_long"
 *   }
 * )
 */
class EmbeddedTipserFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Display embedded tipser.');

    return $summary;
  }
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    foreach ($items as $delta => $item) {
        $element[$delta] = [
          '#theme' => 'embedded_tipser_shop',
          '#bildsize' => $item->value,
          '#product_id' => '',
          '#tipserCarousel' => '',
          '#tipserCollection' => '',
          '#collection_id' => '',
          '#height' => $item->height,
          '#width' => render($item->field_width->value),
      ];
    }
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getTargetEntityTypeId() === 'paragraph';
  }

}
