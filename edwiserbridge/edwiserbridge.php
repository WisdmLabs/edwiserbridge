<?php
/*
 * File displays the edwiser bridge settings.
 */

require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('mod_form.php');

// Restrict normal user to access this page
admin_externalpage_setup('edwiserbridge_conn_synch_settings');


$stringmanager = get_string_manager();
$strings = $stringmanager->load_component_strings('local_edwiserbridge', 'en');
$PAGE->requires->strings_for_js(array_keys($strings), 'local_edwiserbridge');






// Require Login
require_login();
$context = context_system::instance();
$baseurl = $CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=connection';



/*
* Creating Objects of the all settings mform
*/

/*$mform_service        = new edwiserbridge_service_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=service', null, 'post', '', array("id" => "eb_service_form"), true, null);
$mform_connection      = new edwiserbridge_connection_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=connection', null, 'post', '', array("id" => "eb_conne_form"), true, null);
$mform_synchronization = new edwiserbridge_synchronization_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=synchronization', null, 'post', '', array("id" => "eb_synch_form"), true, null);
$mform_settings = new edwiserbridge_required_settings_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=settings', null, 'post', '', array("id" => "eb_settings_form"), true, null);*/


$mform_service        = new edwiserbridge_service_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=service', null, 'post', '', array("id" => "eb_service_form"), true, null);
$mform_connection      = new edwiserbridge_connection_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=synchronization', null, 'post', '', array("id" => "eb_conne_form"), true, null);
$mform_synchronization = new edwiserbridge_synchronization_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=settings', null, 'post', '', array("id" => "eb_synch_form"), true, null);
$mform_settings = new edwiserbridge_required_settings_form($CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php?tab=settings', null, 'post', '', array("id" => "eb_settings_form"), true, null);




$mform_navigation      = new edwiserbridge_navigation_form();

/*
 * Necessary page requirements.
 */
$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
// $PAGE->set_url('/local/edwiserbridge/edwiserbridge.php?tab=connection');
$PAGE->set_url('/local/edwiserbridge/edwiserbridge.php?tab=service');

$PAGE->set_title(get_string('eb-setting-page-title', 'local_edwiserbridge'));
$PAGE->requires->css('/local/edwiserbridge/styles/style.css');
$PAGE->requires->js_call_amd("local_edwiserbridge/edwiser_bridge", "init");


echo $OUTPUT->header();
echo $OUTPUT->container_start();


/*
 * Navigation form
 */
$mform_navigation->display();



//Connection form processing and displaying.
if ($mform_connection->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform_connection->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    save_connection_form_settings($form_data);

    // print_object($form_data);
    // $mform_connection->display();

} else {
    //Display connection form  for the first time.
    if (isset($_GET["tab"]) && $_GET["tab"] == "connection") {
        $mform_connection->display();
    }
}


//synchronization form processing and displaying.
if ($mform_synchronization->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform_synchronization->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    save_synchronization_form_settings($form_data);
    //print_object($form_data);
    // $mform_synchronization->display();
} else {
    // Display synchronization form for the first time.
    if (isset($_GET["tab"]) && $_GET["tab"] == "synchronization") {
        $mform_synchronization->display();
    }
}


//synchronization form processing and displaying.
if ($mform_service->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform_service->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    save_synchronization_form_settings($form_data);
    //print_object($form_data);
    // $mform_service->display();
} else {
    // Display synchronization form for the first time.
    if (isset($_GET["tab"]) && $_GET["tab"] == "service") {
        $mform_service->display();
    }
}




//synchronization form processing and displaying.
if ($mform_settings->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform_settings->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    save_required_settings_form($form_data);
    //print_object($form_data);
    // $mform_settings->display();
} else {
    // Display synchronization form for the first time.
    if (isset($_GET["tab"]) && $_GET["tab"] == "settings") {
        $mform_settings->display();
    }
}



/*echo '<pre>';
var_dump($CFG);
echo '</pre>';*/





/*global $DB;


$custom_cert_module_id = $DB->get_record_sql("SELECT id FROM {modules} WHERE name = 'customcert'");


// Check if the same activity id is present in the mdl_course_modules table

$activities = $DB->get_records_sql("SELECT id FROM {course_modules} WHERE module = ? && course = ?", array($custom_cert_module_id->id, 2));




echo print_r($custom_cert_module_id, 1);
echo print_r($activities, 1);*/



echo $OUTPUT->container_end();
echo $OUTPUT->footer();
