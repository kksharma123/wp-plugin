<?php
/**
 * Plugin class booking
 *
 * @since   1.0.1
 * @package CTBP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( ! class_exists( 'CTBP_Form' ) ) :
	final class CTBP_Form {

        private $action = CTBP_AJAX;

        private static $instance = null;

        private function __construct() {
            /* Nothing here! */
        }

        public function __clone() {
			_doing_it_wrong( __FUNCTION__, __("Please don't hack me!", 'CTBP'), '1.0.1' );
		}

		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __("Please don't hack me!", 'CTBP'), '1.0.1' );
        }

        public static function instance(){
            if( !isset( self::$instance)){
                self::$instance = new self();
                self::$instance->action();
            }
            
            return self::$instance;
        }

        /**
		 * Action
		 *
		 * @access public
		 * @since  1.0.1
		 * @static
		 */

        public function action(){
            add_action( "wp_ajax_ctbp_submit_form", array($this, 'ctbp_submit_form'));
            add_action( "wp_ajax_nopriv_ctbp_submit_form", array($this, 'ctbp_submit_form'));

            
            add_action('wp_enqueue_scripts', array($this, 'scripts'), 22);
            add_shortcode('custom_quote_form',  array($this, 'shortcode_temmplate'));

        }

        public function fonts_url() {
            $fonts_url = '';
            $font_families = array('Montserrat:wght@300;400;500;600;700');
            $query_args = array(
                'family' =>  implode( '|', $font_families ) ,
                'display' => urlencode( 'swap' ),
            );
            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css2' );
            return esc_url_raw( $fonts_url );
        }

        
        public function shortcode_temmplate(){
            global $wpdb;
            //echo "test";
            require( ctbp_init()->get_plugin_path() . 'views/parts/quote_form-view.php' );
                 
            
        }
            
        /**
		 * Scripts
		 *
		 * @access public
		 * @since  1.0.1
		 * @static
		 */
        public function scripts(){
            wp_enqueue_style('ctbp-front-css', ctbp_init()->get_plugin_url() . 'assets/css/ctbp-front.css', array(), ctbp_init()->get_version());      
            wp_enqueue_script('ctbp-front-js', ctbp_init()->get_plugin_url() . 'assets/js/ctbp-front.js', array('jquery', 'wp-util'), ctbp_init()->get_version(), true); 
            
        }

        public function ctbp_submit_form(){
        	if (!check_ajax_referer('ctbp', 'nonce', false)) {
                    status_header(400);
                    wp_send_json_error('bad_nonce');
                } elseif ('POST' !== $_SERVER['REQUEST_METHOD']) {
                    status_header(405);
                    wp_send_json_error('bad_method');
                }
                print_r($_POST);
        }

        
      
    }
endif;