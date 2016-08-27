<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              Donna Peplinskie <support@wpreviewplugins.com>
 * @since             1.0.0
 * @package           Book_Review
 *
 * @wordpress-plugin
 * Plugin Name:       Book Review
 * Plugin URI:        http://wpreviewplugins.com/product/book-review/
 * Description:       Add book information such as title, author, publisher and cover photo to enhance your review posts.
 * Version:           2.3.8
 * Author:            Donna Peplinskie
 * Author URI:        http://donnapeplinskie.com/
 * Text Domain:       book-review
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/donnapep/book-review
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-book-review-activator.php
 */
function activate_book_review( $network_wide ) {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-book-review-activator.php';
  Book_Review_Activator::activate( $network_wide );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-book-review-deactivator.php
 */
function deactivate_book_review() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-book-review-deactivator.php';
  Book_Review_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_book_review' );
register_deactivation_hook( __FILE__, 'deactivate_book_review' );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-book-review.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_book_review() {
  $plugin = Book_Review::get_instance();
  $plugin->run();

  return $plugin;
}

run_book_review();