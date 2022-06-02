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
 * This file is responsible for WordPress connection related functionality.
 *
 * @package     local_edwiserbridge
 * @copyright   2021 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Wisdmlabs
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Handles API requests and response from WordPress.
 *
 * @package     local_edwiserbridge
 * @copyright   2021 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class eb_setup_wizard {

    /**
     * Hook in tabs.
     */
    public function __construct() {

    }


    /**
     * 
     */
    public function eb_setup_wizard_get_steps() {

        /**
         * Loop through the steps.
         * Ajax call for each of the steps and save.
         * step change logic.
         * load data on step change.
         * 
         */
        $steps = array(
            'installation_guide' => array(
                'name'     => 'Edwiser Bridge FREE plugin installation guide',
                'title'    => 'Edwiser Bridge FREE plugin installation guide',
                'function' => 'eb_setup_installation_guide',
                'sub_step' => 0,
            ),
            'mdl_plugin_config' => array(
                'name'     => 'Edwiser Bridge Moodle Plugin configuration',
                'title'    => 'Edwiser Bridge - Moodle Plugin configuration',
                'function' => 'eb_setup_plugin_configuration',
                'sub_step' => 0,
            ),
            'web_service' => array(
                'name'     => 'Setting up Web service',
                'title'    => 'Setting up Web service',
                'function' => 'eb_setup_web_service',
                'sub_step' => 0,
            ),
            'wordpress_site_details' => array(
                'name'     => 'Setting up Web service',
                'title'    => 'WordPress site details',
                'function' => 'eb_setup_wordpress_site_details',
                'sub_step' => 1,
            ),
            'check_permalink' => array(
                'name'     => 'Setting up Web service',
                'title'    => 'Check permalink structure',
                'function' => 'eb_setup_check_permalink',
                'sub_step' => 1,
            ),
            'test_connection' => array(
                'name'     => 'Setting up Web service',
                'title'    => 'Test connection between Moodle and WordPress',
                'function' => 'eb_setup_test_connection',
                'sub_step' => 1,
            ),
            'user_and_course_sync' => array(
                'name'     => 'Setting up User and course sync',
                'title'     => 'Set data synchronization events',
                'function' => 'eb_setup_user_and_course_sync',
                'sub_step' => 0,
            ),
            'complete_details' => array(
                'name'     => 'Setting up User and course sync',
                'title'     => 'Edwiser Bridge FREE Moodle plugin setup complete',
                'function' => 'eb_setup_complete_details',
                'sub_step' => 1,
            )
        );


        return $steps;
    }



    /**
     * Setup Wizard Steps HTML content
     */
    public function eb_setup_steps_html( $current_step = '' ) {
        global $CFG;

        $steps = $this->eb_setup_wizard_get_steps();

        /**
         * Get completed steps data.
         */
        $progress  = isset( $CFG->eb_setup_progress ) ? $CFG->eb_setup_progress : '';
        $completed = 1;

        if ( empty( $progress ) ) {
            $completed = 0;
        }

        if ( ! empty( $steps ) && is_array( $steps ) ) {

            ?>
            <ul class="eb-setup-steps">

                <?php
                foreach ( $steps as $key => $step ) {
                    if ( ! $step['sub_step'] ) {
                        $class = '';
                        $html  = '<span class="eb-setup-step-circle eb_setup_sidebar_progress_icons" > </span>';

                        if ( 1 === $completed ) {
                            $class = 'eb-setup-step-completed';
                            $html  = '<i class="fa-solid fa-circle-check eb_setup_sidebar_progress_icons"></i>';
                            // $html  = '<span class="dashicons dashicons-arrow-right-alt2"></span>';
                        } elseif ( $current_step === $key ) {
                            $class = 'eb-setup-step-active';
                            $html  = '</i><i class="fa-solid fa-circle-chevron-right eb_setup_sidebar_progress_icons"></i>';
                            // $html  = '<i class="fa-solid fa-circle-chevron-right eb_setup_sidebar_progress_icons"></i>';
                        }

                        if ( $key === $progress ) {
                            $completed = 0;
                        }

                        ?>
                        <li class='eb-setup-step  <?php echo ' eb-setup-step-' . $key . ' ' . $class . '-wrap'; ?>' >
                            <?php echo $html; ?>
                            <span class='eb-setup-steps-title <?php echo $class; ?>' data-step="<?php echo $key; ?>">
                                <?php echo $step['name']; ?>
                            </span>
                        </li>

                        <?php
                    }
                }
                ?>
            </ul>
            <?php
        }
    }


    /**
     * Setup Wizard get step title.
     *
     * @param string $step Step name.
     */
    public function eb_get_step_title( $step ) {
        $steps = $this->eb_setup_wizard_get_steps();
        return isset( $steps[ $step ]['title'] ) ? $steps[ $step ]['title'] : '';
    }



    /**
     * Setup Wizard Page submission or refresh handler
     */
    public function eb_setup_handle_page_submission_or_refresh() {

        $steps = $this->eb_setup_wizard_get_steps();
        $step  = 'installation_guide';

        /**
         * Handle page refresh.
         */
        if ( isset( $_GET['current_step'] ) && ! empty( $_GET['current_step'] ) ) {
            $step = $_GET['current_step'];
        }

        return $step;
    }



    /**
     * 
     */
    public function eb_setup_wizard_template( $step = 'installation_guide' ) {
        // Get current step.
        $content_class = "";

        $steps = $this->eb_setup_wizard_get_steps();
        $step  = $this->eb_setup_handle_page_submission_or_refresh();
        $title = $this->eb_get_step_title( $step );

        $this->setup_wizard_header( $title );

            // content area.
            // sidebar.
            ?>

            <div class='eb-setup-content-area'>

                <!-- Sidebar -->
                <div class='eb-setup-sidebar'>

                    <?php
                    $this->eb_setup_steps_html( $step );
                    ?>

                </div>

                <!-- content -->
                <div class="eb-setup-content <?php echo $content_class; ?>">

                    <?php
                    $function = $steps[ $step ]['function'];
                    $this->$function( 0 );
                    ?>

                </div>

            </div>

            <?php
                // sidebar progress.
            // Content.

        // Footer part.
        $this->setup_wizard_footer();
    }



    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header( $title = '' ) {
        global $CFG;

        $eb_plugin_url = '';

        ?>
        <!DOCTYPE html>
        <html >
        <head>
            <title><?php echo get_string( 'edwiserbridge', 'local_edwiserbridge' ); ?></title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

        </head>


        <body class="wc-setup wp-core-ui ">

            <header class="eb-setup-wizard-header">

                <div class="eb-setup-header-logo">
                    <div class="eb-setup-header-logo-img-wrap">
                        <img src="<?php echo 'images/moodle-logo.png' ?>" />
                        <!-- <img src="<?php echo $CFG->dirroot . '/local/edwiserbridge/images/moodle-logo.png' ?>" /> -->
                        <!-- <img src="<?php echo  '../images/moodle-logo.png' ?>" /> -->
                    </div>
                </div>

                <div class="eb-setup-header-title-wrap">
                    <div class="eb-setup-header-title"><?php echo $title; ?></div>
                    <div class='eb-setup-close-icon'> <i class="fa-solid fa-xmark"></i> </div>

                </div>
            
            </header>
        <?php
    }

    /**
     * Setup Wizard Footer.
     */
    public function setup_wizard_footer() {
        ?>
            <footer class='eb-setup-wizard-footer'>

                <div class='eb-setup-footer-copyright'>
                    <?php echo get_string( 'setup_footer', 'local_edwiserbridge' ); ?>
                </div>

                <div class='eb-setup-footer-button'>
                    <a>
                        <?php echo get_string( 'setup_contact_us', 'local_edwiserbridge' ); ?>
                    </a>
                </div>

                <div> <?php echo $this->eb_setup_close_setup(); ?> </div>

            </footer>

        </body>
    </html>


        <?php
    }


    public function get_next_step( $current_step ) {
        $steps = $this->eb_setup_wizard_get_steps();
        $step = '';
        $found_step = 0;

        foreach ($steps as $key => $value) {
            if ( $found_step ) { 
                $step = $key;
                break;
            }

            if ( $current_step == $key ) {
                $found_step = 1;
            }
        }

        return $step;
    }


    public function get_prev_step( $current_step ) {

        $steps = $this->eb_setup_wizard_get_steps();
        $step = '';
        $found_step = 0;
        $prevkey = '';
        foreach ($steps as $key => $value) {
            if ( $current_step == $key ) {
                $found_step = 1;
            }

            if ( $found_step ) {
                $step = $prevkey;
                break;
            }

            $prevkey = $key;
        }

        return $step;
    }




    public function eb_setup_installation_guide( $ajax = 1 ) {

        if ( $ajax ) {
            ob_start();
        }
        $step = 'installation_guide';
        $is_next_sub_step  = 0;


        $next_step = $this->get_next_step( $step );
        ?>
        <div class="eb_setup_installation_guide es-w-80">
            <div>
                <p class="eb_setup_p"> <?php echo get_string( 'setup_installation_note1', 'local_edwiserbridge' ); ?> </p>

                <div class="eb_setup_p_wrap">

                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'modulename', 'local_edwiserbridge') . ' ' . get_string( 'setup_free', 'local_edwiserbridge') . ' ' . get_string( 'setup_wp_plugin', 'local_edwiserbridge' ); ?> </p>

                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'modulename', 'local_edwiserbridge') . ' ' . get_string( 'setup_free', 'local_edwiserbridge' ) . ' ' . get_string( 'setup_mdl_plugin', 'local_edwiserbridge' ); ?> </p>

                </div>


                <p class="eb_setup_p"> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </p>

                <div class="eb_setup_btn_wrap">
                    <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>'> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                </div>

            </div>

            <div>
                <div>
                    <div class="accordion"> <i class="fa-solid fa-circle-question"></i> <!-- <span class="dashicons dashicons-editor-help"></span> --> <?php echo get_string( 'setup_installation_faq', 'local_edwiserbridge' ); ?> <i class="fa-solid fa-chevron-down"></i> <i class="fa-solid fa-chevron-up"></i> <!-- <span class="dashicons dashicons-arrow-down-alt2"></span><span class="dashicons dashicons-arrow-up-alt2"></span> --></div>

                    <div class="panel">

                        <div>
                            <!-- <button class="eb_setup_sec_btn"> <?php echo get_string( 'setup_faq_download_plugin', 'local_edwiserbridge' ); ?> </button> -->
                            <a class="eb_setup_sec_btn" href='https://downloads.wordpress.org/plugin/edwiser-bridge.zip'> <?php echo get_string( 'setup_faq_download_plugin', 'local_edwiserbridge' ); ?> </a>
                        </div>

                        <p>
                            <p class='es-p-t-10'> <?php echo get_string( 'setup_faq_steps', 'local_edwiserbridge' ); ?> </p>

                            <ol>
                                <li class='es-p-b-10'> <?php echo get_string( 'setup_faq_step1', 'local_edwiserbridge' ); ?></li>
                                <li class='es-p-b-10'><?php echo get_string( 'setup_faq_step2', 'local_edwiserbridge' ); ?></li>
                                <li class='es-p-b-10'><?php echo get_string( 'setup_faq_step3', 'local_edwiserbridge' ); ?></li>
                                <li class='es-p-b-10'><?php echo get_string( 'setup_faq_step4', 'local_edwiserbridge' ); ?></li>
                            </ol>

                        </p>
                    </div>
                </div>
            </div>
    
    
        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
             
        }
    }

    
   



    public function eb_setup_plugin_configuration($ajax = 1){

        if ( $ajax ) {
            ob_start();
        }

        $step = 'mdl_plugin_config';
        $is_next_sub_step  = 0;


        $next_step = $this->get_next_step( $step );


        ?>
        <div class="eb_plugin_configuration es-w-80">
            <div>
                <p> <?php echo get_string( 'setup_mdl_plugin_note1', 'local_edwiserbridge' ); ?> </p>

                <div class="eb_plugin_configuration_checks">

                    <p class="eb_setup_h3">
                        <i class="fa-solid fa-circle-check eb_enable_rest_protocol"></i> <?php echo get_string( 'no_1', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check1', 'local_edwiserbridge'); ?>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'enabling_rest_tip', 'local_edwiserbridge'); ?></span></i> 
                     </p>

                    <p class="eb_setup_h3">
                        <i class="fa-solid fa-circle-check eb_enable_web_service"></i> <?php echo get_string( 'no_2', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check2', 'local_edwiserbridge'); ?>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'enabling_service_tip', 'local_edwiserbridge'); ?></span></i> 
                    </p>

                    <p class="eb_setup_h3">
                        <i class="fa-solid fa-circle-check eb_disable_pwd_policy"></i> <?php echo get_string( 'no_3', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check3', 'local_edwiserbridge'); ?>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'disable_passw_policy_tip', 'local_edwiserbridge'); ?></span></i>
                    </p>

                    <p class="eb_setup_h3">
                        <i class="fa-solid fa-circle-check eb_allow_extended_char"></i> <?php echo get_string( 'no_4', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check4', 'local_edwiserbridge'); ?> 
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'allow_exte_char_tip', 'local_edwiserbridge'); ?></span></i>
                    </p>

                    <div class="eb_setup_settings_success_msg"> <i class="fa-solid fa-circle-check"></i> <?php echo get_string( 'setup_mdl_settings_success_msg', 'local_edwiserbridge' ); ?> </div>

                </div>

                <div>
                    <span class="eb_enable_plugin_settings_label"> <?php echo get_string( 'setup_mdl_plugin_note2', 'local_edwiserbridge' ); ?> </span>

                    <button class="eb_setup_btn eb_enable_plugin_settings" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_enble_settings', 'local_edwiserbridge' ); ?> </button>
                </div>

                <div class="eb_setup_btn_wrap">
                    

                    <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>


                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
        }
    }





    public function eb_setup_web_service( $ajax = 1 ){

        if ( $ajax ) {
            ob_start();
        }

        $step = 'web_service';
        $is_next_sub_step  = 1;

        $next_step = $this->get_next_step( $step );

        $existingservices = eb_get_existing_services();


        ?>
        <div class="eb_setup_web_service es-w-80">
            <div>
                <p> <?php echo get_string( 'setup_web_service_note1', 'local_edwiserbridge' ); ?> </p>

                <div class="eb_setup_p_wrap">

                    <div class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_web_service_h1', 'local_edwiserbridge'); ?> </div>

                    <div class="eb_setup_separator">
                        <div class="eb_setup_hr"><hr></div>
                        <div> <span> <?php echo get_string( 'or', 'local_edwiserbridge'); ?> </span> </div>
                        <div class="eb_setup_hr"><hr></div>
                    </div>

                    <div class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_web_service_h1', 'local_edwiserbridge'); ?> </div>

                </div>

                <div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p>
                            <label class="eb_setup_h2"> <?php echo get_string( 'sum_web_services', 'local_edwiserbridge' ); ?></label>
                            <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'web_service_tip', 'local_edwiserbridge'); ?></span></i>
                        </p>

                        <select name="eb_setup_web_service_list" class="eb_setup_inp eb_setup_web_service_list" >
                            <?php
                            foreach ( $existingservices as $key => $value ) {
                            ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        

                    </div>

                    <div class="eb_setup_conn_url_inp_wrap eb_setup_web_service_name_wrap">
                        <p>
                            <label class="eb_setup_h2"> <?php echo get_string( 'new_service_inp_lbl', 'local_edwiserbridge' ); ?></label>
                            <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'name_web_service_tip', 'local_edwiserbridge'); ?></span></i>
                        </p>
                        <input class="eb_setup_inp eb_setup_web_service_name" name="eb_setup_web_service_name" type="text" >
                    </div>

                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_btn disabled eb_setup_web_service_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' disabled> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php
        if ( $ajax ) {
            return ob_get_clean();
        }
    }





    public function eb_setup_wordpress_site_details( $ajax = 1 ) {

        if ( $ajax ) {
            ob_start();
        }
        $step     = 'wordpress_site_details';
        $is_next_sub_step  = 1;
        $sites = get_site_list();
        // $sites1 = get_connection_settings();

        $next_step = $this->get_next_step( $step );

        ?>
        <div class="eb_setup_wordpress_site_details es-w-80">
            <div>

                <div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p> <?php echo get_string( 'setup_wp_site_note1', 'local_edwiserbridge' ); ?> </p>
        
                        <p>
                            <label class="eb_setup_h2"> <?php echo get_string( 'setup_wp_site_dropdown', 'local_edwiserbridge' ); ?></label>
                            <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'wp_site_tip', 'local_edwiserbridge'); ?></span></i>
                        </p>

                        <select name="eb_setup_wp_sites" class="eb_setup_inp eb_setup_wp_sites" >
                            <option value=""><?php echo get_string( 'select', 'local_edwiserbridge' ); ?></option>
                            <option value="create"><?php echo get_string( 'create_wp_site', 'local_edwiserbridge' ); ?></option>
                            <?php
                            foreach ( $sites as $key => $value ) {
                            ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        

                    </div>

                    <div class=" eb_setup_conn_url_inp_wrap eb_setup_wp_site_details_wrap">
                        <span> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </span>

                        <p>
                            <label class="eb_setup_h2"> <?php echo get_string( 'name', 'local_edwiserbridge' ); ?></label>
                            <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'wp_site_name_tip', 'local_edwiserbridge'); ?></span></i>
                        </p>
                        <input class="eb_setup_inp eb_setup_site_name" name="eb_setup_site_name" type="text" >
                    </div>

                    <div class="eb_setup_conn_url_inp_wrap eb_setup_wp_site_details_wrap">
                        <p>
                            <label class="eb_setup_h2"> <?php echo get_string( 'url', 'local_edwiserbridge' ); ?></label>
                            <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'wp_site_url_tip', 'local_edwiserbridge'); ?></span></i>
                        </p>
                        <input class="eb_setup_inp eb_setup_site_url" name="eb_setup_site_url" type="text" >
                    </div>

                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_btn disabled eb_setup_wp_details_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
        }
    }



    public function eb_setup_check_permalink( $ajax = 1 ){
        global $CFG;
        if ( $ajax ) {
            ob_start();
        }

        $step      = 'check_permalink';
        $is_next_sub_step  = 0;
        $next_step = $this->get_next_step( $step );
        $prevstep = $this->get_prev_step( $step );
        $prevurl = $CFG->wwwroot . '/local/edwiserbridge/setup_wizard.php?current_step=' . $prevstep;

        $sitename =  $CFG->eb_setup_wp_site_name;

        // $sites = get_site_list();
        $sites = get_connection_settings();
        $sites = $sites['eb_connection_settings'];

        $url = '';
        if ( isset($sites[$sitename])) {
            $url = $sites[$sitename]['wp_url'];
        }

        $url = $url . '/wp-admin/options-permalink.php';

        ?>
        <div class='eb_setup_check_permalink es-w-80'>
            <div>

                <div>
                    <p class=""> <?php echo get_string( 'setup_permalink_note1', 'local_edwiserbridge'); ?> </p>
                    <p class="">
                    <?php echo get_string( 'setup_permalink_click', 'local_edwiserbridge') . '  <a href="' . $url . '">' . $url . '</a>  ' . get_string( 'setup_permalink_note2', 'local_edwiserbridge') ; ?> </p>
                    <p class=""> <?php echo get_string( 'setup_permalink_note3', 'local_edwiserbridge'); ?> </p>
                </div>


                <div>

                    <div class="eb_setup_btn_wrap">
                        <a class="eb_setup_sec_btn" href="<?php echo $prevurl; ?>"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </a>
                        <!-- <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button> -->
                        <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'confirmed', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
            
        }
    }



    public function eb_setup_test_connection($ajax = 1){
        global $CFG;
        if ( $ajax ) {
            ob_start();
        }
        $step = 'test_connection';
        $is_next_sub_step  = 1;
        $sitename =  $CFG->eb_setup_wp_site_name;

        // $sites = get_site_list();
        $sites = get_connection_settings();
        $sites = $sites['eb_connection_settings'];

        $name = '';
        $url = '';
        if ( isset($sites[$sitename])) {
            $name = $sitename;
            $url = $sites[$sitename]['wp_url'];
        }

        $next_step = $this->get_next_step( $step );

        $prevstep = $this->get_prev_step( $step );
        $prevurl = $CFG->wwwroot . '/local/edwiserbridge/setup_wizard.php?current_step=' . $prevstep;

        ?>
        <div class="eb_setup_wordpress_site_details es-w-80">
            <div>

                <div class=" eb_setup_conn_url_inp_wrap">
                    <span> <?php echo get_string( 'wp_site_details_note', 'local_edwiserbridge' ); ?> </span>

                    <p>
                        <label class="eb_setup_h2"> <?php echo get_string( 'name', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'wp_site_name_tip', 'local_edwiserbridge'); ?></span></i>
                    </p>
                    <input class="eb_setup_inp eb_setup_site_name" name="eb_setup_site_name" type="text" value="<?php echo $name; ?>" >
                </div>

                <div class="eb_setup_conn_url_inp_wrap">
                    <p>
                        <label class="eb_setup_h2"> <?php echo get_string( 'url', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'wp_site_url_tip', 'local_edwiserbridge'); ?></span></i>
                    </p>
                    <input class="eb_setup_inp eb_setup_site_url" name="eb_setup_site_url" type="url" value="<?php echo $url; ?>">

                    <div class="eb_setup_test_conn_resp_msg"></div>

                </div>

                <div class="eb_setup_btn_wrap">
                    <a class="eb_setup_sec_btn" href="<?php echo $prevurl; ?>"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </a>

                    <!-- <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button> -->

                    <button class="eb_setup_btn eb_setup_test_connection_btn" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'wp_test_conn_btn', 'local_edwiserbridge' ); ?> </button>

                    <button class="eb_setup_btn eb_setup_save_and_continue eb_setup_test_connection_continue_btn" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
        }
    }




    public function eb_setup_user_and_course_sync($ajax = 1) {
        global $CFG;

        if ( $ajax ) {
            ob_start();
        }
        $step     = 'user_and_course_sync';
        $is_next_sub_step  = 1;

        $next_step = $this->get_next_step( $step );

        $prevstep = $this->get_prev_step( $step );
        $prevurl = $CFG->wwwroot . '/local/edwiserbridge/setup_wizard.php?current_step=' . $prevstep;
        $nexturl = $CFG->wwwroot . '/local/edwiserbridge/setup_wizard.php?current_step=' . $next_step;

        ?>
        <div class="eb_setup_user_and_course_sync es-w-80">
            <div>

                <div>

                    <p> <?php echo get_string( 'setup_sync_note1', 'local_edwiserbridge' ); ?> </p>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_all' id='eb_setup_sync_all' >
                        <label class="eb_setup_h2"> <?php echo get_string( 'select_all', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <hr>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_user_enrollment' id='eb_setup_sync_user_enrollment'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_enrollment', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'user_enrollment_tip', 'local_edwiserbridge'); ?></span></i>
                        
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_user_unenrollment' id='eb_setup_sync_user_unenrollment'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_unenrollment', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'user_unenrollment_tip', 'local_edwiserbridge'); ?></span></i>
                        
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_user_creation' id='eb_setup_sync_user_creation'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_creation', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'user_creation_tip', 'local_edwiserbridge'); ?></span></i>
                        
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_user_deletion' id='eb_setup_sync_user_deletion'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_deletion', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'user_deletion_tip', 'local_edwiserbridge'); ?></span></i>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_user_update' id='eb_setup_sync_user_update'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_update', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'user_update_tip', 'local_edwiserbridge'); ?></span></i>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_course_creation' id='eb_setup_sync_course_creation'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'course_creation', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'course_creation_tip', 'local_edwiserbridge'); ?></span></i>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" class="eb_setup_sync_cb" name='eb_setup_sync_course_deletion' id='eb_setup_sync_course_deletion'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'course_deletion', 'local_edwiserbridge' ); ?></label>
                        <i class="fa-solid fa-circle-exclamation eb-tooltip"><span class='eb-tooltiptext'><?php echo get_string( 'course_deletion_tip', 'local_edwiserbridge'); ?></span></i>
                    </div>


                    <div class="eb_setup_btn_wrap">
                        <a class="eb_setup_sec_btn" href="<?php echo $prevurl; ?>"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </a>
                        <a class="eb_setup_sec_btn" href="<?php echo $nexturl; ?>"> <?php echo get_string( 'skip', 'local_edwiserbridge' ); ?> </a>

                        <!-- <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button> -->
                        <!-- <button class="eb_setup_sec_btn"> <?php echo get_string( 'skip', 'local_edwiserbridge' ); ?> </button> -->
                        <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();            
        }

    }



    public function eb_setup_complete_details( $ajax = 1 ) {
        global $CFG;

        if ( $ajax ) {
            ob_start();
        }
        $step = 'complete_details';
        $is_next_sub_step  = 0;

        $next_step = $this->get_next_step( $step );
        $sitename =  $CFG->eb_setup_wp_site_name;

        $sites = get_connection_settings();
        $sites = $sites['eb_connection_settings'];


        $url   = $CFG->wwwroot;
        $token = '';
        if (isset($sites[$sitename])) {
            // $url   = $sites[$sitename]['wp_url'];
            $token = $sites[$sitename]['wp_token'];
        }

        $prevstep = $this->get_prev_step( $step );
        $prevurl = $CFG->wwwroot . '/local/edwiserbridge/setup_wizard.php?current_step=' . $prevstep;

        ?>
        <div class="eb_setup_complete_details es-w-80">
            <div>

                <div>

                    <span class='eb_setup_h2' > <?php echo get_string( 'what_next', 'local_edwiserbridge'); ?> </span>
                    <p class='' > <?php echo get_string( 'setup_completion_note1', 'local_edwiserbridge'); ?> </p>

                </div>


                <div class="eb_setup_complete_card_wrap">

                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_completion_note2', 'local_edwiserbridge'); ?> </p>

                    <div class="eb_setup_complete_cards" data-copy='<?php echo $url; ?>'>

                        <div class="eb_setup_complete_card">
                            <div>
                                <span class="eb_setup_h2"><?php echo get_string( 'mdl_url', 'local_edwiserbridge'); ?></span>
                                <div class="eb_setup_copy_url"> <?php echo $url; ?> </div>
                            </div>
                            <div class="eb_setup_copy_icon" data-copy='<?php echo $url; ?>' ><i class="fa-solid fa-copy"></i></div>
                        </div>

                        <div class="eb_setup_complete_card eb_setup_copy" data-copy='<?php echo $token; ?>'>
                            <div>
                                <span class="eb_setup_h2"><?php echo get_string( 'wp_token', 'local_edwiserbridge'); ?></span>
                                <div class="eb_setup_copy_token" style="word-break:break-all;"> <?php echo $token; ?> </div>
                            </div>
                            <div class="eb_setup_copy_icon" data-copy='<?php echo $token; ?>' ><i class="fa-solid fa-copy"></i></div>

                        </div>

                        <div class="eb_setup_complete_card eb_setup_copy" data-copy='<?php echo $CFG->lang; ?>'>
                            <div>
                                <span class="eb_setup_h2"><?php echo get_string( 'eb_mform_lang_desc', 'local_edwiserbridge'); ?></span>
                                <div class="eb_setup_copy_lang" > <?php echo $CFG->lang; ?> </div>
                            </div>
                            <div class="eb_setup_copy_icon" data-copy='<?php echo $CFG->lang; ?>' ><i class="fa-solid fa-copy"></i></div>

                        </div>

                    </div>


                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_completion_note3', 'local_edwiserbridge'); ?> </p>

                    <button class="eb_setup_sec_btn eb_setup_download_creds"> <?php echo get_string( 'mdl_edwiser_bridge_txt_download', 'local_edwiserbridge' ); ?> </button>

                </div>


                <div>

                    <p class=""> <?php echo get_string( 'setup_completion_note4', 'local_edwiserbridge'); ?> </p>
                    

                    <!-- <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button> -->
                    <div class="eb_setup_btn_wrap">

                        <form method='POST'>
                            <a class="eb_setup_sec_btn" href="<?php echo $prevurl; ?>"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </a>

                            <input type="submit" class="eb_setup_btn" name="eb_setup_completed" value='<?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?>' >
                        </form>
                    </div>

                    <!-- <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button> -->

                    <!-- <a class="eb_setup_btn" href="<?php echo $url . '/wp-admin/admin.php?page=eb-setup-wizard'?>"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </a> -->

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
        }
    }






    /**
     * Setup Wizard close setup.
     */
    public function eb_setup_close_setup() {
        global $CFG;
        ob_start();
        ?>
        <div class='eb_setup_popup_content_wrap' style='display: none;'>
            <div class='eb_setup_popup_content'>

                <div class=''>
                    <p> <i class="fa-solid fa-triangle-exclamation eb_setup_pupup_warning_icon"></i> </p>

                    <p class='eb_setup_h2'> <?php echo get_string( 'close_quest', 'local_edwiserbridge'); ?></p>

                    <div class="eb_setup_user_sync_btn_wrap">
                        <a href=' <?php echo $CFG->wwwroot; ?>' class='eb_setup_btn' > <?php echo get_string( 'yes', 'local_edwiserbridge'); ?> </a>
                        <button class='eb_setup_sec_btn eb_setup_do_not_close'> <?php echo get_string( 'no', 'local_edwiserbridge'); ?> </button>
                    </div>

                </div>

                <div>
                    <fieldset>
                        <legend> <?php echo get_string( 'note', 'local_edwiserbridge' ); ?> </legend>
                        <div>
                            <?php echo get_string( 'close_note', 'local_edwiserbridge' ); ?>
                        </div>
                    </fieldset>
                </div>

            </div>
        </div>

        <?php
        return ob_get_clean();
    }







}
