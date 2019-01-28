<?php


require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('mod_form.php');

// Restrict normal user to access this page
admin_externalpage_setup('edwiserbridge');

// Require Login
require_login();
$context = context_system::instance();
$baseurl = $CFG->wwwroot.'/local/edwiserbridge/edwiserbridge.php';
$mform1 = new edwiserbridge_form1(null, null, 'post', '', array("id" => "eb_conne_form"), true, null);
$mform2 = new edwiserbridge_form2(null, null, 'post', '', array("id" => "eb_synch_form"), true, null);

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->set_url('/local/edwiserbridge/edwiserbridge.php');
$PAGE->set_title(get_string('eb-setting-page-title', 'local_edwiserbridge'));
$PAGE->requires->css('/local/edwiserbridge/styles/style.css');
$PAGE->requires->js_call_amd("local_edwiserbridge/edwiser_bridge", "init");


echo $OUTPUT->header();
echo $OUTPUT->container_start();


/******   form 1  *********/
$mform1->display();
/******************/


/******   form 2    *******/
$mform2->display();
/******************/

echo $OUTPUT->container_end();
echo $OUTPUT->footer();


//Form processing and displaying is done here
if ($mform1->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform1->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    save_form1_settings($form_data);
}


if ($mform2->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform2->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    save_form2_settings($form_data);
}
