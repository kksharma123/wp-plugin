<?php
/**
 * Plugin class admin
 *
 * @since   1.0.1
 * @package CTBP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if( ! class_exists( 'CTBP_Admin' ) ) :
    final class CTBP_Admin {
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
                self::$instance->actions();
            }
            return self::$instance;
        }

        public function actions(){
            add_action('admin_menu', array($this, 'menu_init'));
            add_action('admin_init', array($this, 'register_settings'));
            add_action( "wp_ajax_ctbp_create_gallery", array($this, 'ctbp_create_gallery'));
        }

        public function menu_init() {
           
            $page = add_menu_page(
                __('Custom Quote Form', 'CTBP'),
                __('Custom Quote Form', 'CTBP'),
                'manage_options',
                ctbp_init()->get_slug(),
                false,
            
            );
            
            add_submenu_page( 
                ctbp_init()->get_slug(), 
                __('Quote Form Setting','CTBP'), 
                __('Quote Form Setting','CTBP'), 
                'manage_options',
                ctbp_init()->get_slug(),
                array( $this, 'render_create_form_setting')
            );
            add_submenu_page( 
                ctbp_init()->get_slug(), 
                __('Setting','CTBP'), 
                __('Setting','CTBP'), 
                'manage_options',
                ctbp_init()->get_slug() . '-creategallery',
                array( $this, 'render_create_form_setting')
            );

            add_action('admin_print_styles', array($this, 'admin_enqueue_style'));
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_style'), 22);

           
        }

        public function register_settings() {
                add_settings_section(
                    'cs_1',
                    __('Form Settings', 'CTBP'),
                    array($this, 'options_settings_callback'),
                    'cs_1'
                );
                $cs_2 = array(
                    'cs_1_url'        => 'API URL',
                    'cs_1_token'        => 'API TOKEN',
                   
                );
                foreach($cs_2 as $key => $value){
                    add_settings_field(
                        $key,
                        __($value, 'CTBP'),
                        array($this, 'options_settings_callback'),
                        'cs_1',
                        'cs_1',
                        array( 'type' => 'text', 'option_name' => $key, 'label_for' => $key)
                    );
                }
            register_setting('booking_form_settings', 'booking_form_settings');

        }


         public function options_settings_callback($args) {
            require( ctbp_init()->get_plugin_path() . 'views/callback/form-fields.php' );
        }


        public function render_all_galleries_page() {
            require( ctbp_init()->get_plugin_path() . 'views/admin/page-all-galleries.php' );
        }

        private function fonts_url() {
            $fonts_url = '';
            $font_families = array('Jost:wght@400;500;600');
            $query_args = array(
                'family' => implode('|', $font_families),
                'display' => urlencode('swap'),
                'subset' => urlencode('latin'),
            );
            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
            return esc_url_raw($fonts_url);
        }

        public function admin_enqueue_style() {
            
            wp_enqueue_style(ctbp_init()->get_slug(), ctbp_init()->get_plugin_url() . 'assets/css/ctbp-admin.css', array(), ctbp_init()->get_version());
            wp_enqueue_script('ctbp-axios-js', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.min.js', array(), ctbp_init()->get_version(), true);
            wp_enqueue_style(ctbp_init()->get_slug(), ctbp_init()->get_plugin_url() . 'assets/css/ctbp-admin.css', array(), ctbp_init()->get_version());
                if ( ! did_action( 'wp_enqueue_media' ) ) {
                    wp_enqueue_media();
                }
              wp_enqueue_script(ctbp_init()->get_slug(), ctbp_init()->get_plugin_url() . 'assets/js/ctbp-admin.js', array('jquery'), ctbp_init()->get_version(), true);
              $customdata = array(
                'nonce' => wp_create_nonce($this->action),
                'action' => $this->action,
                'apiUrl' => home_url('wp-json/cars/v2')
            );
            wp_scripts()->add_data(
                ctbp_init()->get_slug(),
                'data',
                sprintf('const _ctbp = %s;', wp_json_encode($customdata))
            );   
        }

        
        public function render_create_form_setting() {   
            require( ctbp_init()->get_plugin_path() . 'views/admin/quote-form-setting.php' );
        }
    
        

       

    }
endif;