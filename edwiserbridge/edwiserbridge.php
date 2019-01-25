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

$PAGE->requires->jquery();
$PAGE->requires->jquery_plugin('ui');
$PAGE->requires->jquery_plugin('ui-css');
$PAGE->set_pagelayout('admin');
$PAGE->set_context($context);
$PAGE->set_url('/local/edwiserbridge/edwiserbridge.php');
$PAGE->set_title(get_string('eb-setting-page-title', 'local_edwiserbridge'));
$PAGE->requires->css('/local/edwiserbridge/styles/style.css');
$PAGE->requires->js_call_amd("local_edwiserbridge/edwiser_bridge", "init");

// $PAGE->requires->css('/local/privatefilemanager/styles/datatable.css');
// $PAGE->requires->css('/local/privatefilemanager/styles/style.css');

echo $OUTPUT->header();

/******   form 1  *********/

$mform1 = new edwiserbridge_form1(null, null, 'post', '', array("id" => "eb_conne_form"), true, null);
$mform2 = new edwiserbridge_form2(null, null, 'post', '', array("id" => "eb_synch_form"), true, null);

// $tablehead = array("Select", "Username", "Email", "Filename", "Uploaded", "Action");
// echo $OUTPUT->heading_with_help(get_string('nav_name', 'local_edwiserbridge'), 'eb-setting-page-title', 'local_edwiserbridge');
echo $OUTPUT->container_start();
$mform1->display();

/******************/

/******   form 2    *******/

// $tablehead = array("Select", "Username", "Email", "Filename", "Uploaded", "Action");
// echo $OUTPUT->heading_with_help(get_string('nav_name', 'local_edwiserbridge'), 'eb-setting-page-title', 'local_edwiserbridge');
// echo $OUTPUT->container_start();
$mform2->display();


/******************/

echo $OUTPUT->container_end();

// echo $OUTPUT->heading_with_help(get_string('nav_name', 'local_edwiserbridge'), 'eb-setting-page-title', 'local_edwiserbridge');
/*echo $privatefilemanager->get_renderer()->render(new \local_edwiserbridge\output\local_edwiserbridge_listprivatefiles("list-private-files", $tablehead));*/

echo $OUTPUT->footer();



//Form processing and displaying is done here
if ($mform1->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform1->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.

/*	var_dump($form_data);
	exit();*/


    save_form1_settings($form_data);
}


if ($mform2->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} elseif ($form_data = $mform2->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.

/*	var_dump($form_data);
	exit();*/

    save_form2_settings($form_data);
}
