<?php

/**
* 
*/


require_once($CFG->libdir . "/externallib.php");


class eb_settings_handler
{
	
	public function __construct()
	{

	}


	public function eb_create_externle_service($name, $userid)
	{
		global $DB, $CFG;
		//response initializations.
		$response['status']            = 1;
		$response['msg']               = '';
		$response['token']             = 0;
		$response['site_url']          = $CFG->wwwroot;
		$response['service_id']        = 0;


		//service creation data
    	$servicedata                   = array();
   		$service['name']               = $name;
   		$service['enabled']            = 1;
   		$service['requiredcapability'] = NULL;
   		$service['restrictedusers']    = 1;
   		$service['component']          = NULL;
        $service['timecreated']        = time();
   		$service['timemodified']       = NULL;

   		$service['shortname']          = $this->eb_generate_service_shortname();


   		//User id validation.
   		if (empty($userid)) {
   			$response['status'] = 0;
			$response['msg']    = get_string('empty_userid_err', 'local_edwiserbridge');
			return $response;
   		}


   		//creates unique shortname
   		if (empty($service['shortname'])) {
   			$response['status'] = 0;
			$response['msg']    = get_string('create_service_shortname_err', 'local_edwiserbridge');
			return $response;
   		}

   		//checks if the name is avaialble.
   		if (!$this->eb_check_if_service_name_available($name)) {
   			$response['status'] = 0;
			$response['msg']    = get_string('create_service_name_err', 'local_edwiserbridge');
			return $response;
   		}

   		$service['downloadfiles'] = 0;
   		$service['uploadfiles']   = 0;

        $serviceid = $DB->insert_record('external_services', $service);

        if ($serviceid) {
        	//Adding functions in web service
        	$this->eb_add_web_service_functions($serviceid);

        	//Creating token iwith service id.
        	$token = $this->eb_create_token($serviceid, $userid);
        	$response['service_id'] = $serviceid;
        	$response['token'] = $token;
        } else {
        	$response['status'] = 0;
			$response['msg']    = get_string('create_service_creation_err', 'local_edwiserbridge');
			return $response;
        }

        return $response;
	}


	public function eb_generate_service_shortname()
	{
		global $DB;
   		$shortname = 'edwiser';

	    do {
	        $numtries ++;
	        $newshortname = $shortname . $numtries;
	        if ($numtries > 100) {
	        	return 0;
	            break;
	        }
	    } while ($DB->record_exists('external_services', array('shortname' => $newshortname)));

	    return $newshortname;
	}



	public function eb_check_if_service_name_available($servicename)
	{
		global $DB;
		if($DB->record_exists('external_services', array('name' => $servicename)))
		{
			return 0;
		}
		return 1;
	}




	public function eb_add_web_service_functions($serviceid)
	{
		global $DB;
		$functions = array(
			array('externalserviceid' => $serviceid, 'functionname' => 'core_user_create_users'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_user_get_users_by_field'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_user_update_users'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_course_get_courses'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_course_get_categories'),
			array('externalserviceid' => $serviceid, 'functionname' => 'enrol_manual_enrol_users'),
			array('externalserviceid' => $serviceid, 'functionname' => 'enrol_manual_unenrol_users'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_enrol_get_users_courses'),
			array('externalserviceid' => $serviceid, 'functionname' => 'eb_test_connection'),
			array('externalserviceid' => $serviceid, 'functionname' => 'eb_get_site_data'),
			array('externalserviceid' => $serviceid, 'functionname' => 'eb_get_course_progress')
		);

		$DB->insert_records('external_services_functions', $functions);
	}



	public function eb_extensions_web_service_function($serviceid)
	{

		$ssofunctions = array(
			array('externalserviceid' => $serviceid, 'functionname' => 'wdm_sso_verify_token'),
		);

		$selectivesynchfunctions = array(
			array('externalserviceid' => $serviceid, 'functionname' => 'eb_get_users'),
		);

		$bulk_purchase = array(
			array('externalserviceid' => $serviceid, 'functionname' => 'core_cohort_add_cohort_members'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_cohort_create_cohorts'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_role_assign_roles'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_role_unassign_roles'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_cohort_delete_cohort_members'),
			array('externalserviceid' => $serviceid, 'functionname' => 'core_cohort_get_cohorts'),
			array('externalserviceid' => $serviceid, 'functionname' => 'eb_manage_cohort_enrollment'),
			array('externalserviceid' => $serviceid, 'functionname' => 'eb_delete_cohort'),
			array('externalserviceid' => $serviceid, 'functionname' => 'wdm_manage_cohort_enrollment')
		);


	}






	public function eb_create_token($serviceid, $userid)
	{
		$tokentype = EXTERNAL_TOKEN_PERMANENT; // check this add for testing purpose
		// $serviceorid = $serviceid;
		// $userid = $userid;
		$contextorid = 1;

		$token = external_generate_token($tokentype, $serviceid, $userid, $contextorid);
    	set_config("edwiser_bridge_last_created_token", $token);
    	set_config('ebexistingserviceselect', $serviceid);
		return $token;
	}



	//add function to handle the ajax call.



}




