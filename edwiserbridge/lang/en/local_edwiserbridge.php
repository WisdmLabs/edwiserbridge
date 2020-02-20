<?php

/**
 * Language File
 *
 * @package   local_edwiser
 * @author    Wisdmlabs
 */


/**************  by default strings used by the moodle  ****************/

$string["local_edwiserbridgedescription"] = "";
$string['modulename'] = "Edwiser Bridge";
$string['modulenameplural'] = "Edwiser Bridge";
$string['pluginname'] = 'Edwiser Bridge';
$string['pluginadministration'] = "Edwiser Bridge administrator";
$string['modulename_help'] = '';
$string['blank'] = '';

/**************  end of the strings used by default by the moodle  ****************/

/*** TABS  ***/

$string['tab_conn'] = 'Connection Settings';
$string['tab_synch'] = 'Synchronization Settings';
$string['tab_service'] = 'Service Settings';

/*******/

/******* navigation menu and settings page   ********/

$string["wp_site_settings_title"] = "Site Settings :";

// $string["nav_name"] = "Edwiser Bridge Settings";
$string["nav_name"] = "Connection / Synchronization Settings";
$string["default_settings_nav"] = "Settings";


$string["edwiserbridge"] = "Edwiser Bridge";
$string["eb-setting-page-title"] = "Edwiser Bridge Two Way Synchronization Settings";
$string["eb-setting-page-title_help"] = "Edwiser Bridge Two Way Synchronization Settings";

$string[""] = "Wordpress Synchronization Settings";
$string["enrollment_checkbox"] = "Enable user enrollment.";
$string["enrollment_checkbox_desc"] = "Enroll user from Moodle to Wordpress for linked users";
$string["unenrollment_checkbox"] = "Enable user un-enrollment.";
$string["unenrollment_checkbox_desc"] = "Unenroll user from Moodle to Wordpress for linked users";
$string["user_creation"] = "Enable user creation";
$string["user_creation_desc"] = "Create user In linked Wordpress site when created in Moodle Site";
$string["user_deletion"] = "Enable user deletion";
$string["user_deletion_desc"] = "Delete user In linked Wordpress site when deleted in Moodle Site";

$string["wp_settings_section"] = "Wordpress Connection Settings";
$string["wordpress_url"] = "Wordpress url";
$string["wp_token"] = "Access Token";
$string["wp_test_conn_btn"] = "Test Connection";
$string["wp_test_remove_site"] = "Remove Site";
$string["add_more_sites"] = "Add New Site";
$string["wordpress_site_name"] = "Site Name";
$string["site-list"] = "Site List";

$string["token_help"] = "Please enter access token used in Wordpress in connection setting";
$string["wordpress_site_name_help"] = "Please enter unique site name.";
$string["wordpress_url_help"] = "Please enter Wordpress site URL.";

$string["token"] = "Access Token";

$string['existing_web_service_desc'] = 'Select existing web service if you have created already.';
$string['new_web_service_desc'] = 'Create new web service';
$string['new_web_new_service'] = 'Create new web service';

$string['new_service_inp_lbl'] = 'Web service name';
$string['new_serivce_user_lbl'] = 'Select User';
$string['existing_serice_lbl'] = 'Select web service';

$string['web_service_token'] = 'Token generated after creating service';
$string['moodle_url'] = 'Moodle site url.';
$string['web_service_name'] = 'Web service Name';
$string['web_service_auth_user'] = 'Authorized user.';


$string['existing_service_desc'] = 'Edwiser web-service functions will get added into it and also be used as reference for upcoming updates.';
$string['auth_user_desc'] = 'All admin users used as Authorized User while creating token.';

$string['eb_mform_service_desc'] = 'Service desc';
$string['eb_mform_service_desc_help'] = 'Edwiser web-service functions will get added into it and also be used as reference for upcoming updates.';

$string['eb_mform_token_desc'] = 'Token';
$string['eb_mform_token_desc_help'] = 'This is your last created token used in wp for site integration.';


/*********************************/



/*********** Settings page validation and Modal strings************/
$string['create_service_shortname_err'] = 'Unable to create the webservice please contact plugin owner.';
$string['create_service_name_err'] =   'This name is already in use please use different name.';
$string['create_service_creation_err'] = 'Unable to create the webservice please contact plugin owner.';
$string['empty_userid_err'] = 'Please select the user.';

$string['dailog_title'] = 'Token And Url';
$string['site_url'] = 'Site Url ';
$string['token'] = 'Token ';
$string['copy'] = 'Copy';
$string['copied'] = 'Copied !!!';
$string['process'] = 'Process';

/**********************************/



/******  Form validation.  ******/
$string['required'] = "- You must supply a value here.";
$string['sitename-duplicate-value'] = " - Site Name already exists, Please provide a different value.";
$string['url-duplicate-value'] = " - Wordpress Url already exists, Please provide a different value.";
/************/


/*****  web service  *******/
$string["web_service_wp_url"] = "Wordpress site URL.";
$string["web_service_wp_token"] = "Web service token.";

$string["web_service_test_conn_status"] = '1 if successful connection and 0 on failure.';
$string["web_service_test_conn_msg"] = 'Success or error message.';

$string["web_service_site_index"] = "Site index is the nth no. of site saved in Edwiser Bridge settings.";


$string["web_service_course_enrollment"] = "Checks if the course enrollment is performed for the saved site";
$string["web_service_course_un_enrollment"] = "Checks if the course un-enrollment is performed for the saved site";
$string["web_service_user_creation"] = "Checks if the user creation is performed for the saved site";
$string["web_service_user_deletion"] = "Checks if the user deletion is performed for the saved site";


$string["web_service_offset"] = "This is the offset for the select query.";
$string["web_service_limit"] = "This limits the number of users returned.";
$string["web_service_search_string"] = "This string will be searched in the select query.";
$string["web_service_total_users"] = "Total number of users present in Moodle.";


$string["web_service_id"] = "User Id.";
$string["web_service_username"] = "Username of the user.";
$string["web_service_firstname"] = "Firstname of the user.";
$string["web_service_lastname"] = "Lastname of the user.";
$string["web_service_email"] = "Email of the user.";
/******/


/****  error handling  ***/
$string["default_error"] = "Please check the URL or wordpress site permalink: to know more about this error <a href='https://knowledgebase.edwiser.org/'  target='_blank'> click here </a>";

$string['eb_empty_name_err'] = 'Please enter valid service name.';
$string['eb_empty_user_err'] = 'Please select user.';

/**/




/*****************************  ADDED FOR SETTINGS PAGE   *****************************/


$string["manual_notification"] = "MANUAL NOTIFICATION";





