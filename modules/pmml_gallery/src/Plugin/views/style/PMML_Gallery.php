<?php

namespace Drupal\pmml_gallery\Plugin\views\style;

use Drupal\core\form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;

/**
 * Style plugin to render a list of years and months
 * in reverse chronological order linked to content.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "pmml_gallery",
 *   title = @Translation("PMML Gallery"),
 *   help = @Translation("Display a PMML Gallery"),
 *   theme = "pmml_gallery",
 *   theme_file = "pmml_gallery.theme.inc",
 *   display_types = { "normal" }
 * )
 */
class PMML_Gallery extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  protected $usesRowPlugin = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $usesFields = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $usesOptions = TRUE;

  /**
   * {@inheritdoc}
   */
  public function evenEmpty() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['id'] = ['default' => 'pmml_gallery'];
    return $options;
  }
    
}
