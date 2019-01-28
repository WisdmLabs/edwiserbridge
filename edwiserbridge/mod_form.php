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
 * settings mod form
 * @package   local_edwiserbridge
 * @author    Wisdmlabs
 */

defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");

/**
*form shown while adding activity.
*/
class edwiserbridge_form1 extends moodleform
{
    public function definition()
    {
        $defaultvalues = get_connection_settings();
        $mform = $this->_form;



/*        $mform->addElement('advcheckbox', 'showdescription', get_string('showdescription'));
        $mform->addHelpButton('showdescription', 'showdescription');*/

 /*       $mform->addElement('header', 'synchronization_settings', get_string('wp_settings_section', 'local_edwiserbridge'));
        $mform->addElement('advcheckbox', 'course_enrollment', get_string('enrollment_checkbox', 'local_edwiserbridge'), get_string("enrollment_checkbox_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
        $mform->addElement('advcheckbox', 'course_un_enrollment', get_string('unenrollment_checkbox', 'local_edwiserbridge'), get_string("unenrollment_checkbox_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
        $mform->addElement('advcheckbox', 'user_creation', get_string('user_creation', 'local_edwiserbridge'), get_string("user_creation_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
        $mform->addElement('advcheckbox', 'user_deletion', get_string('user_deletion', 'local_edwiserbridge'), get_string("user_deletion_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
*/


        $mform->addElement(
            'html',
            '<div class="eb-tabs-cont">
                <div id="eb-conn-tab" class="eb-tabs active-tab">
                    Connection settings
                </div>
                <div id="eb-synch-tab" class="eb-tabs">
                    Synchronization settings
                </div>
            </div>'
        );


        $repeatarray = array();
        $repeatarray[] = $mform->createElement('text', 'wp_name', get_string('wordpress_site_name', 'local_edwiserbridge'));
        $repeatarray[] = $mform->createElement('text', 'wp_url', get_string('wordpress_url', 'local_edwiserbridge'));
        $repeatarray[] = $mform->createElement('text', 'wp_token', get_string('wp_token', 'local_edwiserbridge'));


        $buttonarray = array();
        $buttonarray[] = $mform->createElement('button', 'eb_test_connection', get_string("wp_test_conn_btn", "local_edwiserbridge"), "", "", "class=test_eb");
        $buttonarray[] = $mform->createElement('button', 'eb_remove_site', get_string("wp_test_remove_site", "local_edwiserbridge"));
        $repeatarray[] = $mform->createElement("group", "eb_buttons", "", $buttonarray);






        /*$repeatarray[] = $mform->createElement(
            'html',
            '<div class="form-group row pb-5">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                    <button class="btn btn-success">
                        '.get_string("wp_test_conn_btn", "local_edwiserbridge").'
                    </button>
                    <button class="btn btn-danger">
                        '.get_string("wp_test_remove_site", "local_edwiserbridge").'
                    </button>
                </div>
            </div>'
        );*/


/*
        $repeatarray[] = $mform->createElement('button', 'test_connection', get_string("wp_test_conn_btn", "local_edwiserbridge"));
        $repeatarray[] = $mform->createElement('button', 'test_connection', get_string("wp_test_remove_site", "local_edwiserbridge"));*/
        // $repeatarray[] = $mform->createElement('hidden', 'optionid', 0);

        /*if ($this->_instance){
            $repeatno = $DB->count_records('choice_options', array('choiceid'=>$this->_instance));
            $repeatno += 2;
        } else {
            $repeatno = 5;
        }*/


        $repeateloptions = array();
        $repeateloptions['wp_url']['type'] = PARAM_TEXT;
        $repeateloptions['wp_token']['type'] = PARAM_TEXT;
        $repeateloptions['wp_name']['type'] = PARAM_TEXT;

        $repeateloptions['wp_token']['helpbutton'] = array("token", "local_edwiserbridge");


        $count = 1;
        if (!empty($defaultvalues) && !empty($defaultvalues["eb_connection_settings"])) {

// var_dump("DEFAULT VALUES ::: ".print_r($defaultvalues, 1));


            $count = count($defaultvalues["eb_connection_settings"]);
            /*for ($i = 0; $i < $count; $i++) {
                $mform->setDefault("wp_url[".$i."]", $defaultvalues["eb_connection_settings"][$i]["wp_url"]);
                $mform->setDefault("wp_token[".$i."]", $defaultvalues["eb_connection_settings"][$i]["wp_token"]);
                $mform->setDefault("wp_name[".$i."]", $defaultvalues["eb_connection_settings"][$i]["wp_name"]);
            }*/

            $siteNo = 0;
            foreach ($defaultvalues["eb_connection_settings"] as $key => $value) {
                $mform->setDefault("wp_url[".$siteNo."]", $value["wp_url"]);
                $mform->setDefault("wp_token[".$siteNo."]", $value["wp_token"]);
                $mform->setDefault("wp_name[".$siteNo."]", $value["wp_name"]);
                $siteNo++;
            }
        }


        // $mform->setType('optionid', PARAM_TEXT);

        $this->repeat_elements($repeatarray, $count, $repeateloptions, 'option_repeats', 'option_add_fields', 1, get_string("add_more_sites", "local_edwiserbridge"), true);


        // $mform->addElement('header', 'connection_settings', get_string('wp_settings_section', 'local_edwiserbridge'));
        /*$mform->addElement('text', 'wp_url', get_string('wordpress_url', 'local_edwiserbridge'));
        $mform->setType('wp_url', PARAM_TEXT);
        $mform->addElement('text', 'wp_token', get_string('wp_token', 'local_edwiserbridge'));
        $mform->setType('wp_token', PARAM_TEXT);*/
        // $mform->addElement('button', 'test_connection', get_string("wp_test_conn_btn", "local_edwiserbridge"));

        //fill form with the existing values

        $this->add_action_buttons();
    }




    public function data_postprocessing($data)
    {
        // var_dump($data->_form);
        // return $data->_form;
    }

    public function validation($data, $files)
    {
        return array();
    }
}




/**
*form shown while adding activity.
*/
class edwiserbridge_form2 extends moodleform
{
    public function definition()
    {

        $mform = $this->_form;
/*        $mform->addElement('advcheckbox', 'showdescription', get_string('showdescription'));
        $mform->addHelpButton('showdescription', 'showdescription');*/

        // $mform->addElement('header', 'synchronization_settings', get_string('wp_settings_section', 'local_edwiserbridge'));


        $sites = get_site_list();
        $site_keys = array_keys($sites);
        $defaultvalues = get_synch_settings($site_keys[0]);

        $mform->addElement('select', 'wp_site_list', get_string('site-list', 'local_edwiserbridge'), $sites);

        $mform->addElement('advcheckbox', 'course_enrollment', get_string('enrollment_checkbox', 'local_edwiserbridge'), get_string("enrollment_checkbox_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
        $mform->addElement('advcheckbox', 'course_un_enrollment', get_string('unenrollment_checkbox', 'local_edwiserbridge'), get_string("unenrollment_checkbox_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
        $mform->addElement('advcheckbox', 'user_creation', get_string('user_creation', 'local_edwiserbridge'), get_string("user_creation_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));
        $mform->addElement('advcheckbox', 'user_deletion', get_string('user_deletion', 'local_edwiserbridge'), get_string("user_deletion_desc", "local_edwiserbridge"), array('group' => 1), array(0, 1));

/*        $mform->addElement('header', 'connection_settings', get_string('wp_settings_section', 'local_edwiserbridge'));
        $mform->addElement('text', 'wp_url', get_string('wordpress_url', 'local_edwiserbridge'));
        $mform->setType('wp_url', PARAM_TEXT);
        $mform->addElement('text', 'wp_token', get_string('wp_token', 'local_edwiserbridge'));
        $mform->setType('wp_token', PARAM_TEXT);
        $mform->addElement('button', 'test_connection', get_string("wp_test_conn_btn", "local_edwiserbridge"));*/

        //fill form with the existing values
        if (!empty($defaultvalues)) {
            $mform->setDefault("course_enrollment", $defaultvalues["course_enrollment"]);
            $mform->setDefault("course_un_enrollment", $defaultvalues["course_un_enrollment"]);
            $mform->setDefault("user_creation", $defaultvalues["user_creation"]);
            $mform->setDefault("user_deletion", $defaultvalues["user_deletion"]);
        }
        $this->add_action_buttons();
    }


    public function data_postprocessing($data)
    {
        // var_dump($data->_form);
        // return $data->_form;
    }

    public function validation($data, $files)
    {
        return array();
    }
}
