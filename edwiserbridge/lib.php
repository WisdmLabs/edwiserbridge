<?php

require_once(dirname(__FILE__)."/classes/class-api-handler.php");
require_once("{$CFG->libdir}/completionlib.php");


function local_edwiserbridge_extend_settings_navigation($settingsnav, $context)
{
    /*global $CFG, $PAGE;
    // Only add this settings item on non-site course pages.
    if (!$PAGE->course or $PAGE->course->id == 1) {
        return;
    }

    // Only let users with the appropriate capability see this settings item.
    if (!has_capability('moodle/backup:backupcourse', context_course::instance($PAGE->course->id))) {
        return;
    }

    if ($settingnode = $settingsnav->find('courseadmin', navigation_node::TYPE_COURSE)) {
        $strfoo = get_string('nav_nmae', 'local_edwiserbridge');
        $url = new moodle_url('/local/edwiserbridge/test.php', array('id' => $PAGE->course->id));
        $foonode = navigation_node::create(
            $strfoo,
            $url,
            navigation_node::NODETYPE_LEAF,
            'edwiserbridge',
            'edwiserbridge',
            new pix_icon('t/addcontact', $strfoo)
        );
        if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
            $foonode->make_active();
        }
        $settingnode->add_node($foonode);
    }*/
}



function save_form1_settings($form_data)
{
    // $connection_settings["wp_url"] = $form_data->wp_url;
    // $connection_settings["wp_token"] = $form_data->wp_token;

    if (count($form_data->wp_url) != count($form_data->wp_token)) {
        return;
    }


// var_dump("DATA :: ".print_r($form_data, 1));


    $connection_settings = array();
    for ($i=0; $i<count($form_data->wp_url); $i++) {
        if (!empty($form_data->wp_url[$i]) && !empty($form_data->wp_token[$i]) && !empty($form_data->wp_name[$i])) {
            $connection_settings[$form_data->wp_name[$i]] = array(
                "wp_url" => $form_data->wp_url[$i],
                "wp_token" => $form_data->wp_token[$i],
                "wp_name" => $form_data->wp_name[$i]
            );
        }
    }

    set_config("eb_connection_settings", serialize($connection_settings));

   /* $synch_settings["course_enrollment"] = $form_data->course_enrollment;
    $synch_settings["course_un_enrollment"] = $form_data->course_un_enrollment;
    $synch_settings["user_creation"] = $form_data->user_creation;
    $synch_settings["user_deletion"] = $form_data->user_deletion;

    set_config("eb_synch_settings", serialize($synch_settings));*/
}


function save_form2_settings($form_data)
{
    // $connection_settings["wp_url"] = $form_data->wp_url;
    // $connection_settings["wp_token"] = $form_data->wp_token;

/*    if (count($form_data->wp_url) != count($form_data->wp_token)) {
        return;
    }

    $connection_settings = array();
    for ($i=0; $i<count($form_data->wp_url); $i++) {
        $connection_settings[$i] = array("wp_url" => $form_data->wp_url[$i], "wp_token" => $form_data->wp_token[$i]);
    }*/
    global $CFG;
    $synch_settings = array();
    $connection_settings = unserialize($CFG->eb_connection_settings);
    $connection_settings_keys = array_keys($connection_settings);


    if (in_array($form_data->wp_site_list, $connection_settings_keys)) {
        $existing_synch_settings = unserialize($CFG->eb_synch_settings);
        $synch_settings = $existing_synch_settings;
        $synch_settings[$form_data->wp_site_list] = array(
        // "course_enrollment"    => $form_data->wp_site_list,
        "course_enrollment"    => $form_data->course_enrollment,
        "course_un_enrollment" => $form_data->course_un_enrollment,
        "user_creation"        => $form_data->user_creation,
        "user_deletion"        => $form_data->user_deletion
        );
    } else {
        $synch_settings[$form_data->wp_site_list] = array(
            // "course_enrollment"    => $form_data->wp_site_list,
            "course_enrollment"    => $form_data->course_enrollment,
            "course_un_enrollment" => $form_data->course_un_enrollment,
            "user_creation"        => $form_data->user_creation,
            "user_deletion"        => $form_data->user_deletion
        );
    }
    set_config("eb_synch_settings", serialize($synch_settings));
}

function get_connection_settings()
{
    global $CFG;
    $reponse["eb_connection_settings"] = isset($CFG->eb_connection_settings) ? unserialize($CFG->eb_connection_settings) : false;
    /*$reponse["eb_synch_settings"] = isset($CFG->eb_synch_settings) ? unserialize($CFG->eb_synch_settings) : false;*/
    return $reponse;
}


function get_synch_settings($index)
{
    global $CFG;
    $reponse = isset($CFG->eb_synch_settings) ? unserialize($CFG->eb_synch_settings) : false;

    $data = array(
        "course_enrollment"    => 0,
        "course_enrollment"    => 0,
        "course_un_enrollment" => 0,
        "user_creation"        => 0,
        "user_deletion"        => 0
    );

    if (isset($reponse[$index]) && !empty($reponse[$index])) {
        return $reponse[$index];
    }
    return $data;
}


function get_site_list()
{
    global $CFG;
    $reponse = isset($CFG->eb_connection_settings) ? unserialize($CFG->eb_connection_settings) : false;

    $sites = array();
    if ($reponse && count($reponse)) {
        foreach ($reponse as $key => $value) {
            $sites[$key] = $value["wp_name"];
        }
    } else {
        return array("" => "--- No Sites Available ---");
    }
    return $sites;
}






/**
 * Returns the main instance of EDW to prevent the need to use globals.
 *
 * @since  1.0.0
 *
 * @return EDW
 */
function api_handler_instance()
{
    error_log("api_handler_instance");
    return api_handler::instance();
}



function get_array_of_enrolled_courses($user_id)
{

    $enrolled_courses = enrol_get_users_courses($user_id);

    $courses = array();

    foreach ($enrolled_courses as $value) {
        array_push($courses, $value->id);
    }
    return $courses;
}


function remove_processed_coures($course_id, $courses)
{
    if (($key = array_search($course_id, $courses)) !== false) {
        unset($courses[$key]);
    }
    return $courses;
}
