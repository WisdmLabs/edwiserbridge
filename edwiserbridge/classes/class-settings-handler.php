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
		global $DB;
		//create service
    	$servicedata                   = array();
   		$service['name']               = $name;
   		$service['enabled']            = 1;
   		$service['requiredcapability'] = NULL;
   		$service['restrictedusers']    = 1;
   		$service['component']          = NULL;
        $service['timecreated']        = time();
   		$service['timemodified']       = NULL;

   		$service['shortname']          = $this->eb_generate_service_shortname();
   		var_dump($service['shortname']);
   		if (empty($service['shortname'])) {
   			return 'error';
   		}

   		$service['downloadfiles'] = 0;
   		$service['uploadfiles']   = 0;
        $serviceid = $DB->insert_record('external_services', $service);



        //Validation for name and shortname duplication






        if ($serviceid) {
        	$this->eb_add_web_service_functions($serviceid);
        	$this->eb_create_token($serviceid, $userid);
        }

	}


	public function eb_generate_service_shortname()
	{
		global $DB;
   		$shortname = 'edwiser';

	    do {
	        $numtries ++;
	        $newshortname = $shortname . $numtries;
	        if ($numtries > 40) {
	        	return 0;
	            break;
	        }
	    } while ($DB->record_exists('external_services', array('shortname' => $newshortname)));

	    return $newshortname;
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




	public function eb_create_token($serviceid, $userid)
	{
		$tokentype = EXTERNAL_TOKEN_PERMANENT; // check this add for testing purpose
		$serviceorid = $serviceid;
		$userid = $userid;
		$contextorid = 1;
		
		$token = external_generate_token($tokentype, $serviceorid, $userid, $contextorid);

		var_dump($token);
		// external_generate_token($tokentype, $serviceorid, $userid, $contextorid, $validuntil=0, $iprestriction='');

	}



	//add function to handle the ajax call.



}




