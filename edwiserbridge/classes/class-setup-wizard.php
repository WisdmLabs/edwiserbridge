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
        
        error_log(' eb_setup_wizard_template POST :::: '.print_r($_POST, 1));

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
            
            'installtion_guide' => array(
                'name'    => 'Edwiser Bridge FREE plugin installation guide',
                'view'    => array( $this, 'eb_setup_free_installtion_guide' ),
                'function'    => 'eb_setup_free_installtion_guide',

                'sidebar' => 1,
                'handler' => array( $this, 'eb_setup_free_installtion_guide_save' ),
            ),
            'mdl_plugin_config' => array(
                'name'    => 'Edwiser Bridge Moodle Plugin configuration',
                'sidebar' => 1,
                'view'    => array( $this, 'eb_setup_test_connection' ),
                'function'    => 'eb_setup_test_connection',

                'handler' => array( $this, 'eb_setup_' ),
            ),
            'web_service' => array(
                'sidebar' => 1,
                'name'    => 'Setting up Web service',
                'view'    => array( $this, 'eb_setup_course_sync' ),
                'function'    => 'eb_setup_course_sync',

                'handler' => array( $this, 'eb_setup_' ),
            ),
            'user_and_course_sync' => array(
                'sidebar' => 1,
                'name'    => 'Setting up User and course sync',
                'view'    => array( $this, 'eb_setup_user_sync' ),
                'function'    => 'eb_setup_user_sync',

                'handler' => array( $this, 'eb_setup_recommended_save' ),
            ),

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


error_log('eb_setup_wizard_template :::: '.print_r($_POST, 1));


        if ( ! empty( $_POST['eb_setup_free_initialize'] ) ) {


error_log('eb_setup_wizard_template  1111 :::: ');

            
            // save set up data.
            get_option( 'eb_setup_data' );
            $chosen_setup = '';
            
            
            if ( isset( $_POST['eb_free_setup'] ) ) {


error_log('eb_setup_wizard_template  2222 :::: ');


                $chosen_setup = 'free';
            } elseif ( isset( $_POST['eb_pro_setup'] ) ) {


error_log('eb_setup_wizard_template  3333 :::: ');


                $chosen_setup = 'pro';
            } elseif ( isset( $_POST['eb_free_and_pro'] ) ) {


error_log('eb_setup_wizard_template  4444 :::: ');


                $chosen_setup = 'both';
            }

            $setup_array = array( 'name' => $chosen_setup );

error_log('eb_setup_wizard_template 222 UPDATING :::: '.print_r($setup_array, 1));


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






    public function eb_setup_free_installtion_guide( $ajax = 1 ) {

        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>
                <span> <?php echo get_string( 'setup_installation_note1', 'local_edwiserbridge' ); ?> </span>

                <div>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'modulename', 'local_edwiserbridge') . ' ' . get_string( 'setup_free', 'local_edwiserbridge') . ' ' . get_string( 'setup_wp_plugin', 'local_edwiserbridge' ); ?> <p>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'modulename', 'local_edwiserbridge') . ' ' . get_string( 'setup_free', 'local_edwiserbridge' ) . ' ' . get_string( 'setup_mdl_plugin', 'local_edwiserbridge' ); ?> <p>

                </div>


                <span> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </span>

                <div class="eb_setup_btn_wrap">
                    <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                </div>

            </div>

            <div>
                <div>
                    <div class="accordion"> <?php echo get_string( 'setup_installation_faq', 'local_edwiserbridge' ); ?> </div>

                    <div class="panel">

                        <div>
                            <button class="eb_setup_sec_btn"> <?php echo get_string( 'setup_download_plugin', 'local_edwiserbridge' ); ?> </button>
                        </div>

                        <p>
                            <span> <?php echo get_string( 'setup_faq_steps', 'local_edwiserbridge' ); ?> </span>

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
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }


    }

    
   



    public function eb_setup_plugin_configuration($ajax = 1){

        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>
                <span> <?php echo get_string( 'setup_mdl_plugin_note1', 'local_edwiserbridge' ); ?> </span>

                <div>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_mdl_plugin_check1', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_mdl_plugin_check2', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_mdl_plugin_check3', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_mdl_plugin_check4', 'local_edwiserbridge'); ?> </p>

                </div>


                <span> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </span>

                <div class="eb_setup_btn_wrap">
                    <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }


    }










    public function eb_setup_web_service( $ajax = 1 ){

        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>
                <span> <?php echo get_string( 'setup_web_service_note1', 'local_edwiserbridge' ); ?> </span>

                <div>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_web_service_h1', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_web_service_h1', 'local_edwiserbridge'); ?> </p>

                </div>


                <span> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </span>


                <div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'sum_web_services', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp" type="text" >
                    </div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'new_service_inp_lbl', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp" type="text" >
                    </div>

                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }


    }







    public function eb_setup_wordpress_site_details( $ajax = 1 ){

        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>

                <span> <?php echo get_string( 'setup_wp_site_note1', 'local_edwiserbridge' ); ?> </span>


                <div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'setup_wp_site_dropdown', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp" type="text" >
                    </div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <span> <?php echo get_string( 'setup_installation_note2', 'local_edwiserbridge' ); ?> </span>

                        <p><label class="eb_setup_h2"> <?php echo get_string( 'sum_web_services', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp" type="text" >
                    </div>

                    <div class="eb_setup_conn_url_inp_wrap">
                        <p><label class="eb_setup_h2"> <?php echo get_string( 'new_service_inp_lbl', 'local_edwiserbridge' ); ?></label></p>
                        <input class="eb_setup_inp" type="text" >
                    </div>

                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }


    }



    public function eb_setup_check_permalink( $ajax = 1 ){
        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>

                <div>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_permalink_note1', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_permalink_note2', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_permalink_note3', 'local_edwiserbridge'); ?> </p>

                </div>

                <span> <?php echo get_string( 'setup_wp_site_note1', 'local_edwiserbridge' ); ?> </span>


                <div>

                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>
                        <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }
    }




    public function eb_setup_user_and_course_sync(){

        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>

                <div>

                    <span> <?php echo get_string( 'setup_sync_note1', 'local_edwiserbridge' ); ?> </span>


                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'select_all', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <hr>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'user_enrollment', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'user_unenrollment', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'user_creation', 'local_edwiserbridge' ); ?></label>
                        <hr>
                    </div>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'user_deletion', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'user_update', 'local_edwiserbridge' ); ?></label>
                        <hr>
                    </div>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'course_creation', 'local_edwiserbridge' ); ?></label>
                    </div>

                    <div class="eb_setup_user_sync_inp_wrap">
                        <input type="checkbox" >
                        <label> <?php get_string( 'course_deletion', 'local_edwiserbridge' ); ?></label>
                        <hr>
                    </div>


                    <div class="eb_setup_btn_wrap">
                        <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>
                        <button class="eb_setup_sec_btn"> <?php echo get_string( 'skip', 'local_edwiserbridge' ); ?> </button>
                        <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>
                    </div>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }

    }



        public function eb_setup_complete( $ajax = 1 ){
        if ( $ajax ) {
            ob_start();
        }

        ?>
        <div class="eb_setup_installation_guide">
            <div>

                <div>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'what_next', 'local_edwiserbridge'); ?> </p>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_completion_note1', 'local_edwiserbridge'); ?> </p>

                </div>


                <div>
                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_completion_note2', 'local_edwiserbridge'); ?> </p>

                    <div>
                        <div>
                            <p>
                                <?php echo get_string( 'what_next', 'local_edwiserbridge'); ?>
                            </p>
                            <p>
                                url
                            </p>
                        </div>

                        <div>
                            <p>
                                <?php echo get_string( 'what_next', 'local_edwiserbridge'); ?>
                            </p>
                            <p>
                                token
                            </p>
                        </div>

                        <div>
                            <p>
                                <?php echo get_string( 'what_next', 'local_edwiserbridge'); ?>
                            </p>
                            <p>
                                en
                            </p>
                        </div>

                    </div>



                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_completion_note3', 'local_edwiserbridge'); ?> </p>

                    <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>


                </div>



                <div>

                    <p class="eb_setup_h2"> <span class="dashicons dashicons-arrow-right-alt2"></span> <?php echo get_string( 'setup_completion_note4', 'local_edwiserbridge'); ?> </p>
                        
                    <button class="eb_setup_sec_btn"> <?php echo get_string( 'back', 'local_edwiserbridge' ); ?> </button>
                    <button class="eb_setup_btn eb_setup_save_and_continue"> <?php echo get_string( 'setup_continue_btn', 'local_edwiserbridge' ); ?> </button>

                </div>

            </div>

        </div>

        <?php

        if ( $ajax ) {
            $html = ob_get_clean();
            $return = array('content' => $html);
            wp_send_json_success($return);
        }
    }



    /*----------------------------------------------------------------------*/




}
