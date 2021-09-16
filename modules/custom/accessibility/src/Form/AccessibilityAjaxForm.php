<?php
/**
@file
Contains \Drupal\accessibility\Form\AccessibilityAjaxForm.
*/
namespace Drupal\accessibility\Form;

use Drupal\Core\Site\Settings;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
* My simple form class.
*/
class AccessibilityAjaxForm extends FormBase {

    /**
    * {@inheritdoc}
    */
    public function getFormId() {
        return 'accessibility_ajax_form';
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // Build a form instance
        $form['actions'] = [
            '#type' => 'button',
            '#value' => $this->t('Click Me!!'),
            '#ajax' => [
                'callback' => '::printResults',
                'wrapper' => 'print-output',
                'method' => 'replace',
                'effect' => 'fade',
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Getting results...'),
                ],
            ],
        ];

        $form['results'] = [
            '#type' => 'markup',
            '#markup' => '<div id="print-output"></div>',
        ];

        return $form;
    }

    /**
    * Prints the results per category
    */
    public function printResults(array &$form, FormStateInterface $form_state) {
        // Create an instance of AjaxResponse
        $ajax_response = new AjaxResponse();
        // Get violation counts
        $violations = $this->getViolationCounts();
        // Start html string
        $html = '';
        // If violations exist, then build an html list
        if( !empty($violations) ) {
            $html .= '<ul>';

            foreach( $violations as $violation => $count ) {
                // Count is good if less than or equal to 2, bad if more
                $color = count($count) <= 2 ? 'green' : 'red';
                // Color code list items by count
                $html .= "<li>$violation: $count</li>";
            }

            $html .= '</ul>';
        }
        // Return the HTML markup we built above in the response
        $ajax_response->addCommand(new HtmlCommand('#print-output', $html));

        return $ajax_response;
    }

    /**
    * Gets accessibility violation counts by category ('id')
    */
    public function getViolationCounts() {
        // Get results for current node
        $nodeResults = $this->fetchNodeResults();
        $violations = $nodeResults['violations'];
        $counts = [];
        // Loop over violations array
        foreach( $violations as $violation ) {
            // Set the violation id as category
            // $category = str_replace('-', '_', $violation['id']);
            $category = $violation['id'];

            if( isset($counts[$category]) ) {
                // If we've already encountered this category, then incremement it
                $counts[$category]++;

            } else {
                // Otherwise start counting it
                $counts[$category] = 1;

            }
        }

        return $counts;
    }

    /**
    * Gets the accessibility analysis for current node
    */
    public function fetchNodeResults() {
        // Get current node object
        $node = \Drupal::routeMatch()->getParameter('node');
        $node_id = 1;

        if ($node instanceof \Drupal\node\NodeInterface) {
        // Get current node id
            $node_id = $node->id();
        }
        // Build query string for internal api
        // Please remember to set the value for `local_site_url` in settings.php
        $site_url = Settings::get('local_site_url', 'http://localhost:8888/tableau_takehome');
        // Pass node id to internal api endpoint
        $internal_api_endpoint = "/api/accessibility/?nid=$node_id";
        $request_url = "$site_url$internal_api_endpoint";
        // Hit internal api endpoint
        $curl = curl_init($request_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Set HTTP headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        // Convert returned json to PHP array
        $payload = json_decode($response, true);

        return $payload;
    }

    /**
    * {@inheritdoc}
    */
    public function submitForm(array &$form, FormStateInterface $form_state) {
    // Silence is golden
    }
}
