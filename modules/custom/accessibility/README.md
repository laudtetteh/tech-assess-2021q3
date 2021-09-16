## Contents of this file

 * What I've done
 * Bugs
 * To-Do
 * Notes




### What I've done

Had to learn some cool new things, as my Drupal knowledge is still mostly rudimentary.

Here's a summary of what I was able to do:

 1. Create a custom module called "accessibility"
 2. Create a block where a form is submitted by a button that is handled by an instance of Drupal's `ajaxResponse`
 3. Create a controller to supply an internal API endpoint for the front end ajax form
 4. Make an HTTP GET request to a cloudfunctions resource which returns json
 5. Process the response and display rendered HTML under the form on the front end




### Bugs

There were a couple of things I didn't have time to properly troubleshoot:

 1. In the form method `\Drupal\accessibility\Form\AccessibilityAjaxForm::getViolationCounts`, I'm attempting to create an associative array of violations by category. Not sure why that piece of code keeps returning count '1' for all violations
 2. The first time you install the module and add the block to the sidebar (or each time you clear drupal caches), the ajax button works as expected. Once you refresh the page, it reverts to 'submitting' to self, rather than the designated ajax handler. Weird stuff.




### To-do

A few additional tweaks I would put in if I had the chance:

 1. Install the new "accessibility" module programmatically
 2. Place the new "Laud's Button" block in the sidebar programmatically
 3. Color code the violations list according to counts




### Notes

You probably already noticed these, but just to be safe...

 1. Please remember to set the value for `local_site_url` in settings.php
 2. Please log in and manually place the block "Laud's Button" inside the region "Sidebar first"
