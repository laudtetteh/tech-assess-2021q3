<?php
/**
@file
Contains \Drupal\accessibility\Controller\AccessibilityController.
*/
namespace Drupal\accessibility\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Controller\ControllerBase;

/**
* Accessibility Controller
*/
class AccessibilityController extends ControllerBase {
    /**
    * Handles the get request for `/api/accessibility`
    */
    public function getAccessibilityAnalysis( Request $request ) {
    // Get node id passed from ajax form, or default to 1
        $node_id = isset($_GET['nid']) ? $_GET['nid'] : 1;
        // Build cloudfunctions request url
        $ext_url = 'https://us-central1-api-project-30183362591.cloudfunctions.net/axe-puppeteer-test';
        $node_url = "https://dev-tech-homework.pantheonsite.io/node/$node_id";
        $request_url = $ext_url . '/?url=' . $node_url;
        // Initialize curl request
        $curl = curl_init($request_url);
        // Set request parameters
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // Set HTTP headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'x-tableau-auth: AOaxT3DBGfyXtR68PgFzcZma4bfzLeuLFaLuX9jGHC',
            'Content-Type: application/json',
        ]);

        $response = curl_exec($curl);
        curl_close($curl);
        // Decode received json object
        $response = json_decode($response, true);
        // Return a Drupal json response
        return new JsonResponse($response);
    }
}
