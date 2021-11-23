<?php
/**
 * Plugin Name: Quote Form Plugin
 * Plugin URI: 
 * Description: Add Quote form to your website using Shortcode.
 * Version: 1.0.1
 * Author: Krishan Kant
 * Author URI: 
 * Text Domain: CTBP
 * Domain Path: /languages
 * 
 * @package  Custom Gallery Upload 
 * @category Plugin
 * @author   krishan Kant 
 * @version  1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Constant version */
define('CTBP_VERSION', '1.0.1');

/* Constant slug */
define('CTBP_SLUG', basename(plugin_dir_path(__FILE__)));

/* Constant path to the main file for activation call */
define('CTBP_CORE_FILE', __FILE__);

/* Constant path to plugin directory */
define('CTBP_PATH', trailingslashit(plugin_dir_path(__FILE__)));

/* Constant uri to plugin directory */
define('CTBP_URI', trailingslashit(plugin_dir_url(__FILE__)));

/* Ajax Action */
define('CTBP_AJAX', 'ctbp');

require_once( CTBP_PATH . 'includes/class-init.php' );


/* Initialization */
if( ! function_exists( 'ctbp_init' ) ) :
	function ctbp_init(){
		return CTBP::instance();
	}
endif;
ctbp_init();