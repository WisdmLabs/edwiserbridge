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



    /*---------------------------------------------------------------------*/


    /**
     * Current step
     *
     * @var string
     */
    private $step = '';

    /**
     * Steps for the setup wizard
     *
     * @var array
     */
    private $steps = array();

    /**
     * Hook in tabs.
     */
    // public function __construct() {

        
    // }

    

    public function eb_setup_save_and_continue() {

    }





    public function enqueue_scripts() {

        $eb_plugin_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_url();

        // Include CSS
        wp_enqueue_style(
            'eb-setup-wizard-css',
            $eb_plugin_url . 'admin/assets/css/eb-setup-wizard.css',
            array('dashicons'),
        );

        wp_register_script(
            'eb-setup-wizard-js',
            $eb_plugin_url . 'admin/assets/js/eb-setup-wizard.js',
            array( 'jquery', 'jquery-ui-dialog' ),
        );



        wp_localize_script(
            'eb-setup-wizard-js',
            'eb_setup_wizard',
            array(
                'ajax_url'                  => admin_url( 'admin-ajax.php' ),
                // 'search_products_nonce'     => wp_create_nonce( 'search-products' ),
                // 'search_customers_nonce'    => wp_create_nonce( 'search-customers' ),
            )
        );


    }


    public function eb_setup_steps_save_handler(){
        

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
                'name'    => 'Edwiser Bridge FREE plugin installation guide',
                'function'    => 'eb_setup_installation_guide',
            ),
            'mdl_plugin_config' => array(
                'name'    => 'Edwiser Bridge Moodle Plugin configuration',
                'function'    => 'eb_setup_plugin_configuration',
            ),
            'web_service' => array(
                'name'    => 'Setting up Web service',
                'function'    => 'eb_setup_web_service',
            ),
            'wordpress_site_details' => array(
                'name'     => 'Setting up Web service',
                'function' => 'eb_setup_wordpress_site_details',
            ),
            'check_permalink' => array(
                'name'     => 'Setting up Web service',
                'function' => 'eb_setup_check_permalink',
            ),
            'user_and_course_sync' => array(
                'name'    => 'Setting up User and course sync',
                'function'    => 'eb_setup_user_and_course_sync',
            ),
            'complete_details' => array(
                'name'     => 'Setting up Web service',
                'function' => 'eb_setup_complete_details',
            )
        );


        return $steps;
    }



    /**
     * Add admin menus/screens.
     */
    public function admin_menus() {
        // if ( ! isset( $_GET['edw-wc-nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['edw-wc-nonce'] ) ), 'edw-wc-nonce' ) ) {
        //  return;
        // }

        // if ( ! isset( $_GET['page'] ) || empty( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) ) {
        //  return;
        // }

        $welcome_page_name  = esc_html__( 'About Edwiser Bridge', 'eb-textdomain' );
        $welcome_page_title = esc_html__( 'Welcome to Edwiser Bridge', 'eb-textdomain' );

        $eb_plugin_url = \app\wisdmlabs\edwiserBridge\wdm_edwiser_bridge_plugin_url();

        add_dashboard_page(
            '',
            '',
            'manage_options',
            'eb-setup-wizard',
            // array( $this, 'eb_setup_wizard_template' )
        );

    }



    public function eb_setup_steps_html() {
        $steps = $this->eb_setup_wizard_get_steps();

        if ( ! empty( $steps ) && is_array( $steps ) ) {
        ?>
        <ul class="eb-setup-steps">

        <?php
            foreach( $steps as $key => $step ) {
            ?>
            <li class="eb-setup-step eb-setup-step-completed-wrap">
                <span class="eb-setup-step-circle" > </span> </span>
                <span class="eb-setup-steps-title eb-setup-step-completed" data-step="<?= $key ?>">
                    <?= $step['name'] ?>
                </span>
            </li>

            <?php
            }
            ?>
        </ul>
        <?php
        }
    }


    /**
     * 
     */
    public function eb_setup_wizard_template(  ) {
        // Intialization.
        error_log(' eb_setup_wizard_template POST :::: '.print_r($_POST, 1));
        // Get current step.
        $step = 'initialize';
        $content_class = "";



        if ( ! empty( $_POST['eb_setup_free_initialize'] ) ) {


            // save set up data.
            get_option( 'eb_setup_data' );
            $chosen_setup = '';
            
            
            if ( isset( $_POST['eb_free_setup'] ) ) {

                $chosen_setup = 'free';
            } elseif ( isset( $_POST['eb_pro_setup'] ) ) {


                $chosen_setup = 'pro';
            } elseif ( isset( $_POST['eb_free_and_pro'] ) ) {



                $chosen_setup = 'both';
            }

            $setup_array = array( 'name' => $chosen_setup );

            update_option( 'eb_setup_data', $setup_array );
            $step = 'installation';
        }


        $this->setup_wizard_header();



        if( 'initialize' === $step ){
            $content_class = "eb_setup_full_width";
        }


            // content area.
            // sidebar.
                ?>

                <div class="eb-setup-content-area">
                <?php   
                
                if( 'initialize' !== $step ){

                ?>
                <!-- Sidebar -->
                    <div class="eb-setup-sidebar">

                        <?php

                        $this->eb_setup_steps_html();

                        ?>

                    </div>
                <?php
                }
                ?>

                    <!-- content -->
                    <div class="eb-setup-content <?php echo esc_attr( $content_class ); ?>">
                        <?php
                        if( 'initialize' === $step ){

                            $this->eb_setup_initialize( 0 );

                        } else {
                            $this->eb_setup_free_installtion_guide( 0 );
                        }
                        ?>
                    </div>

                </div>

                <?php

                // sidebar progress.
            // Content.

        // Footer part.
        $this->setup_wizard_footer();

        exit();

    }



    /**
     * Setup Wizard Header.
     */
    public function setup_wizard_header( $title = '' ) {

        error_log('setup_wizard_header  ::: ');

        $eb_plugin_url = '';


        // same as default WP from wp-admin/admin-header.php.
        // $wp_version_class = 'branch-' . str_replace( array( '.', ',' ), '-', floatval( get_bloginfo( 'version' ) ) );

        // set_current_screen();

    error_log('setup_wizard_header PRINT SCRIPTS :::: 11111 ::: ');


        ?>
        <!DOCTYPE html>
        <html >
        <head>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
            <meta name="viewport" content="width=device-width" />
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title><?php  ?></title>

        </head>


        <body class="wc-setup wp-core-ui ">

        <header class="eb-setup-wizard-header">

            <div class="eb-setup-header-logo">
                <div class="eb-setup-header-logo-img-wrap">
                    <img src="<?= '' ?>" />
                </div>
            </div>

            <div class="eb-setup-header-title-wrap">
                <div class="eb-setup-header-title">

Title
                
                </div>
            </div>
        
        </header>
        <?php
    }

    /**
     * Setup Wizard Footer.
     */
    public function setup_wizard_footer() {
        ?>
            <footer class="eb-setup-wizard-footer">

                <div class="eb-setup-footer-copyright">
                    <?php echo get_string( 'setup_footer', 'local_edwiserbridge' ); ?>
                </div>

                <div class="eb-setup-footer-button">
                    <a>
                        <?php echo get_string( 'setup_contact_us', 'local_edwiserbridge' ); ?>
                    </a>
                </div>

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




    public function eb_setup_installation_guide( $ajax = 1 ) {

        if ( $ajax ) {
            ob_start();
        }
        $step = 'installation_guide';
        $is_next_sub_step  = 0;


        $next_step = $this->get_next_step( $step );
        ?>
        <div class="eb_setup_installation_guide">
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
                    <div class="accordion"> <?php echo get_string( 'setup_installation_faq', 'local_edwiserbridge' ); ?> </div>

                    <div class="panel">

                        <div>
                            <button class="eb_setup_sec_btn"> <?php echo get_string( 'mdl_edwiser_bridge_txt_download', 'local_edwiserbridge' ); ?> </button>
                        </div>

                        <p>
                            <p> <?php echo get_string( 'setup_faq_steps', 'local_edwiserbridge' ); ?> </p>

                            <ul>
                                <li> <?php echo get_string( 'setup_faq_step1', 'local_edwiserbridge' ); ?></li>
                                <li><?php echo get_string( 'setup_faq_step2', 'local_edwiserbridge' ); ?></li>
                                <li><?php echo get_string( 'setup_faq_step3', 'local_edwiserbridge' ); ?></li>
                                <li><?php echo get_string( 'setup_faq_step4', 'local_edwiserbridge' ); ?></li>
                            </ul>

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
        <div class="eb_plugin_configuration">
            <div>
                <p> <?php echo get_string( 'setup_mdl_plugin_note1', 'local_edwiserbridge' ); ?> </p>

                <div class="eb_plugin_configuration_checks">

                    <p class="eb_setup_h3"> <i class="fa-solid fa-circle-check eb_enable_rest_protocol"></i><?php echo get_string( 'no_1', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check1', 'local_edwiserbridge'); ?> <i class="fa-solid fa-circle-exclamation"></i> </p>

                    <p class="eb_setup_h3"> <i class="fa-solid fa-circle-check eb_enable_web_service"></i> <?php echo get_string( 'no_2', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check2', 'local_edwiserbridge'); ?> <i class="fa-solid fa-circle-exclamation"></i> </p>

                    <p class="eb_setup_h3"> <i class="fa-solid fa-circle-check eb_disable_pwd_policy"></i> <?php echo get_string( 'no_3', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check3', 'local_edwiserbridge'); ?> <i class="fa-solid fa-circle-exclamation"></i> </p>

                    <p class="eb_setup_h3"> <i class="fa-solid fa-circle-check eb_allow_extended_char"></i> <?php echo get_string( 'no_4', 'local_edwiserbridge' ) . ". " . get_string( 'setup_mdl_plugin_check4', 'local_edwiserbridge'); ?> <i class="fa-solid fa-circle-exclamation"></i> </p>

                </div>

                <div class="eb_setup_btn_wrap">
                    <span class="eb_enable_plugin_settings_label"> <?php echo get_string( 'setup_mdl_plugin_note2', 'local_edwiserbridge' ); ?> </span>

                    <button class="eb_setup_btn eb_enable_plugin_settings" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_enble_settings', 'local_edwiserbridge' ); ?> </button>

                    <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>

                    <div class="eb_setup_settings_success_msg"> <i class="fa-solid fa-circle-check"></i> <?php echo get_string( 'setup_mdl_settings_success_msg', 'local_edwiserbridge' ); ?> </div>

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
        <div class="eb_setup_web_service">
            <div>
                <p> <?php echo get_string( 'setup_web_service_note1', 'local_edwiserbridge' ); ?> </p>

                <div class="eb_setup_p_wrap">

                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_web_service_h1', 'local_edwiserbridge'); ?> </p>

                    <div class="eb_setup_separator">
                        <div class="eb_setup_hr"><hr></div>
                        <div> <span> <?php echo get_string( 'or', 'local_edwiserbridge'); ?> </span> </div>
                        <div class="eb_setup_hr"><hr></div>
                    </div>

                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_web_service_h1', 'local_edwiserbridge'); ?> </p>

                </div>


                <p> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </p>


                <div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'sum_web_services', 'local_edwiserbridge' ); ?></label></p>

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

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'new_service_inp_lbl', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp eb_setup_web_service_name" name="eb_setup_web_service_name" type="text" >
                    </div>

                    <div class="eb_setup_btn_wrap">
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







    public function eb_setup_wordpress_site_details( $ajax = 1 ) {

        if ( $ajax ) {
            ob_start();
        }
        $step     = 'wordpress_site_details';
        $is_next_sub_step  = 1;
        $sites         = get_site_list();

        $next_step = $this->get_next_step( $step );


        ?>
        <div class="eb_setup_wordpress_site_details">
            <div>

                <div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p> <?php echo get_string( 'setup_wp_site_note1', 'local_edwiserbridge' ); ?> </p>
        
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'setup_wp_site_dropdown', 'local_edwiserbridge' ); ?></label></p>

                        <select name="eb_setup_wp_sites" class="eb_setup_inp eb_setup_wp_sites" >
                            <?php
                            foreach ( $sites as $key => $value ) {
                            ?>
                                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class=" eb_setup_conn_url_inp_wrap">
                        <span> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </span>

                        <p><label class="eb_setup_h2"> <?php echo get_string( 'name', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp eb_setup_site_name" name="eb_setup_site_name" type="text" >
                    </div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'url', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp eb_setup_site_url" name="eb_setup_site_url" type="text" >
                    </div>

                    <div class="eb_setup_btn_wrap">
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



    public function eb_setup_check_permalink( $ajax = 1 ){
        if ( $ajax ) {
            ob_start();
        }

        $step      = 'check_permalink';
        $is_next_sub_step  = 0;

        $next_step = $this->get_next_step( $step );


        ?>
        <div class="eb_setup_check_permalink">
            <div>

                <div>

                    <p class=""> <?php echo get_string( 'setup_permalink_note1', 'local_edwiserbridge'); ?> </p>
                    <p class=""> <?php echo get_string( 'setup_permalink_note2', 'local_edwiserbridge'); ?> </p>
                    <p class=""> <?php echo get_string( 'setup_permalink_note3', 'local_edwiserbridge'); ?> </p>

                </div>


                <div>

                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>
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




    public function eb_setup_user_and_course_sync($ajax = 1) {

        if ( $ajax ) {
            ob_start();
        }
        $step     = 'user_and_course_sync';
        $is_next_sub_step  = 1;

        $next_step = $this->get_next_step( $step );


        ?>
        <div class="eb_setup_user_and_course_sync">
            <div>

                <div>

                    <p> <?php echo get_string( 'setup_sync_note1', 'local_edwiserbridge' ); ?> </p>


                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_all' id='eb_setup_sync_all' >
                        <label class="eb_setup_h2"> <?php echo get_string( 'select_all', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <hr>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_user_enrollment' id='eb_setup_sync_user_enrollment'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_enrollment', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_user_unenrollment' id='eb_setup_sync_user_unenrollment'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_unenrollment', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_user_creation' id='eb_setup_sync_user_creation'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_creation', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_user_deletion' id='eb_setup_sync_user_deletion'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_deletion', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_user_update' id='eb_setup_sync_user_update'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'user_update', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_course_creation' id='eb_setup_sync_course_creation'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'course_creation', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_inp_wrap">
                        <input type="checkbox" name='eb_setup_sync_course_deletion' id='eb_setup_sync_course_deletion'>
                        <label class="eb_setup_h2"> <?php echo get_string( 'course_deletion', 'local_edwiserbridge' ); ?></label>
                    </div>


                    <div class="eb_setup_btn_wrap">

                        <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>
                        <button class="eb_setup_sec_btn"> <?php echo get_string( 'skip', 'local_edwiserbridge' ); ?> </button>
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
        $step      = 'complete_details';
        $is_next_sub_step  = 0;

        $next_step = $this->get_next_step( $step );
        $sitename =  $CFG->eb_setup_wp_site_name;

        $connectionsettings = get_connection_settings();
        $connectionsettings = $connectionsettings[$sitename];

        ?>
        <div class="eb_setup_complete_details">
            <div>

                <div>

                    <span class='eb_setup_h2' > <?php echo get_string( 'what_next', 'local_edwiserbridge'); ?> </span>
                    <p class='' > <?php echo get_string( 'setup_completion_note1', 'local_edwiserbridge'); ?> </p>

                </div>


                <div class="eb_setup_complete_card_wrap">

                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_completion_note2', 'local_edwiserbridge'); ?> </p>

                    <div class="eb_setup_complete_cards">

                        <div class="eb_setup_complete_card">
                            <p class="eb_setup_h2">
                                <?php echo get_string( 'mdl_url', 'local_edwiserbridge'); ?>
                            </p>
                            <p> <?php echo $connectionsettings['wp_name'] ?> </p>
                        </div>

                        <div class="eb_setup_complete_card">
                            <p class="eb_setup_h2">
                                <?php echo get_string( 'wp_token', 'local_edwiserbridge'); ?>
                            </p>
                            <p> <?php echo $connectionsettings['wp_token'] ?> </p>

                        </div>

                        <div class="eb_setup_complete_card">
                            <p class="eb_setup_h2">
                                <?php echo get_string( 'eb_mform_lang_desc', 'local_edwiserbridge'); ?>
                            </p>

                            <p> <?php echo $CFG->lang; ?> </p>

                        </div>

                    </div>


                    <p class="eb_setup_h2"> <i class="fa-solid fa-circle-chevron-right"></i> <?php echo get_string( 'setup_completion_note3', 'local_edwiserbridge'); ?> </p>

                    <button class="eb_setup_sec_btn"> <?php echo get_string( 'mdl_edwiser_bridge_txt_download', 'local_edwiserbridge' ); ?> </button>

                </div>



                <div>

                    <p class=""> <?php echo get_string( 'setup_completion_note4', 'local_edwiserbridge'); ?> </p>
                        
                    <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>
                    <button class="eb_setup_btn eb_setup_save_and_continue" data-step='<?php echo $step ?>' data-next-step='<?php echo $next_step ?>' data-is-next-sub-step='<?php echo $is_next_sub_step ?>' > <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            return ob_get_clean();
            
        }
    }



    /*----------------------------------------------------------------------*/




}
