<?php
/**
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <thebookwookie@gmail.com>
 * @license   GPL-2.0+
 * @link      http://donnapeplinskie.com
 * @copyright 2014 Donna Peplinskie
 *
 * @wordpress-plugin
 * Plugin Name:       Book Review
 * Plugin URI:        http://donnapeplinskie.com/wordpress-book-review-plugin/
 * Description:       Add book information such as title, author, publisher and cover photo to enhance your review posts.
 * Version:           2.1.0
 * Author:            Donna Peplinskie
 * Author URI:        http://donnapeplinskie.com
 * Text Domain:       book-review
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/book-review.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'Book_Review', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Book_Review', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Book_Review', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/book-review-admin.php' );
	add_action( 'plugins_loaded', array( 'Book_Review_Admin', 'get_instance' ) );

}