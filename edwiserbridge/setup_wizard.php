<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Edwiser Bridge - WordPress and Moodle integration.
 * File displays the edwiser bridge settings.
 *
 * @package     local_edwiserbridge
 * @copyright   2021 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Wisdmlabs
 */



require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
// require_once('mod_form.php');
require_once('classes/class-setup-wizard.php');
require_once(dirname(__FILE__) . '/lib.php');

global $CFG, $COURSE, $PAGE;


// if ( isset( $_POST['eb_setup_completed'] ) ) {

//     $sitename =  $CFG->eb_setup_wp_site_name;

//     $sites = get_connection_settings();
//     $sites = $sites['eb_connection_settings'];

//     $url   = '';
//     if (isset($sites[$sitename])) {
//         $url   = $sites[$sitename]['wp_url'];
//     }

//     if(substr($url , -1)=='/') {
//         redirect( $url . 'wp-admin/admin.php?page=eb-setup-wizard&current_step=test_connection' );
//     } else {
//         redirect( $url . '/wp-admin/admin.php?page=eb-setup-wizard&current_step=test_connection' );
//     }

//     // redirect('http://localhost/wp/wp-admin/admin.php?page=eb-setup-wizard');
//     die();
//     // header($url . '/wp-admin/admin.php?page=eb-setup-wizard');
// }



$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');

// Restrict normal user to access this page.
admin_externalpage_setup('edwiserbridge_conn_synch_settings');

$stringmanager = get_string_manager();
$strings = $stringmanager->load_component_strings('local_edwiserbridge', 'en');
$PAGE->requires->strings_for_js(array_keys($strings), 'local_edwiserbridge');



// Require Login.
require_login();
$context = context_system::instance();
$baseurl = $CFG->wwwroot . '/local/edwiserbridge/edwiserbridge.php?tab=connection';



/*
 * Necessary page requirements.
 */

$PAGE->set_pagelayout("popup");

$PAGE->set_context($context);
$PAGE->set_url('/local/edwiserbridge/edwiserbridge.php?tab=settings');

$PAGE->set_title(get_string('eb-setup-page-title', 'local_edwiserbridge'));


$PAGE->requires->css('/local/edwiserbridge/styles/style.css');
$PAGE->requires->css('/local/edwiserbridge/styles/setup-wizard.css');
$PAGE->requires->js(new moodle_url('/local/edwiserbridge/js/eb_settings.js'));

// $PAGE->requires->js(new moodle_url('/local/edwiserbridge/js/eb_setup_wizard.js'));
// $PAGE->requires->js_call_amd('local_edwiserbridge/eb_settings', 'init');



// $PAGE->requires->js_call_amd("local_edwiserbridge/edwiser_bridge", "init");


/*----------*/

echo $OUTPUT->header();
echo $OUTPUT->container_start();

$setupwizard = new eb_setup_wizard();


/*$setupwizard->setup_wizard_header();

?>
<div class="eb-setup-content-area">
    <div class="eb-setup-sidebar">
        <?php
        $setupwizard->eb_setup_steps_html();
        ?>
    </div>

    <div class="eb-setup-content">
        <?php
        $setupwizard->eb_setup_installation_guide( 0 );
        ?>
    </div>
</div>

<?php
$setupwizard->setup_wizard_footer();*/


$setupwizard->eb_setup_wizard_template();


echo $OUTPUT->container_end();
echo $OUTPUT->footer();





