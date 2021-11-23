<?php
/**
 * Plugin class initializer
 *
 * @since   1.0.1
 * @package CTBP
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'CTBP' ) ) :
	final class CTBP {
		
		private $version;
        private $slug;
        private $plugin_url;
        private $plugin_path;
        private $page_url;
        private $option_name;
        
        private $data;

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
        
        public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CTBP ) ) {

				self::$instance = new self();
				self::$instance->setup();
				self::$instance->include();
                self::$instance->actions();
			}
			return self::$instance;
		}

		/**
		 * Setup
		 *
		 * @access private
		 * @since  1.0.1
		 * @static
		 */
		private function setup(){
            /* Setup variables */
            $this->data        = new stdClass();
            $this->version     = CTBP_VERSION;
            $this->slug        = CTBP_SLUG;
            $this->option_name = self::sanitize_key( $this->slug );
            $this->plugin_url  = CTBP_URI;
            $this->plugin_path = CTBP_PATH;
            $this->page_url    = admin_url( 'admin.php?page=' . $this->slug );
            $this->data->admin = true;
        }

            private function sanitize_key( $key ) {
            return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
        }

		/**
		 * Include
		 *
		 * @access private
		 * @since  1.0.1
		 * @static
		 */
		private function include(){
			if(is_admin()){
				require_once( $this->plugin_path . 'includes/class-admin.php');
			}
            require_once( $this->plugin_path . 'includes/class-form.php');

		}

		/**
		 * Action
		 *
		 * @access private
		 * @since  1.0.1
		 * @static
		 */
		private function actions(){
			// Activate plugin
            register_activation_hook(CTBP_CORE_FILE, array($this, 'activate'));
            
            // Deactivate plugin
            register_deactivation_hook(CTBP_CORE_FILE, array($this, 'deactivate'));
            
            if(is_admin()){
                add_action( 'init', array( $this, 'admin' ) );
            }
            add_action( 'init', array( $this, 'form' ) );
            
		}

		/**
		 * Activate plugin
		 * 
		 * @access public
		 * @since  1.0.1
		 * @static
		 */
        public function activate(){
          

           
 
            $wordpress_page = array(
                  'post_title'    => 'Form',
                  'post_content'  => '[custom_quote_form]',
                  'post_status'   => 'publish',
                  'post_author'   => 1,
                  'post_type' => 'page'
                   );
                 wp_insert_post( $wordpress_page );  
                  
  self::set_plugin_state( true );


		}

		/**
		 * Deactivate plugin
		 *
		 * @access public
		 * @since  1.0.1
		 * @static
		 */
		public function deactivate(){		  
			self::set_plugin_state( false );
	   	}

        /**
		 * Admin area init
		 *
		 * @access public
		 * @since  1.0.1
		 * @static
		 */
        public function admin(){
            return CTBP_Admin::instance();
        }

        /**
		 * Booking init
		 *
		 * @access public
		 * @since  1.0.1
		 */
        public function form(){
            return CTBP_Form::instance();
        }
		
		/**
         * Set plugin state
		 * 
         * @access private
		 * @since  1.0.1
		 * @static
         */
        private function set_plugin_state( $value ) {
            self::set_option( 'is_plugin_active', $value );
        }
        
        /**
         * Set option value
		 * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function set_option($name, $option){
            $options = self::get_options();
            $name = self::sanitize_key( $name );
            $options[ $name ] = esc_html( $option );
            $this->set_options($options);
        }
        
        /**
         * Set the options
		 * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function set_options( $options ) {
            update_option( $this->option_name, $options );
		}
		
		/**
         * Return the options
		 * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_options() {
            return get_option( $this->option_name, array() );
        }
        
        /**
         * Return option value
		 * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_option( $name, $default = '' ) {
            $options = self::get_options();
            $name    = self::sanitize_key( $name );
            return isset( $options[ $name ] ) ? $options[ $name ] : $default;
		}
		
		/**
         * Get slug
         * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_slug(){
            return $this->slug;
        }
        
        /**
         * Get version
         * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_version() {
            return $this->version;
        }
        
        /**
         * Return the plugin url
         * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_plugin_url() {
            return $this->plugin_url;
        }

        /**
         * Return the plugin path
         * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_plugin_path() {
            return $this->plugin_path;
        }

        /**
         * Return the plugin page URL
         * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_page_url() {
            return $this->page_url;
        }
        
        /**
         * Return the option settings name
         * 
         * @access public
		 * @since  1.0.1
		 * @static
         */
        public function get_option_name() {
            return $this->option_name;
        }


       
    }
endif;