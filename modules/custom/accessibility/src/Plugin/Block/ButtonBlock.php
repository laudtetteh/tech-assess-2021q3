<?php
/**
@file
Contains \Drupal\accessibility\Plugin\Block;
*/
namespace Drupal\accessibility\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
* Provides a 'Button' Block.
*
* @Block(
*   id = "lauds_button",
*   admin_label = @Translation("Laud's Button"),
*   category = @Translation("Forms"),
* )
*/
class ButtonBlock extends BlockBase {
    /**
    * {@inheritdoc}
    */
    public function build() {
        // Call the button form
        $form = \Drupal::formBuilder()->getForm('Drupal\accessibility\Form\AccessibilityAjaxForm');

        return $form;
    }
}
