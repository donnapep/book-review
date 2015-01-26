<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://donnapeplinskie.com
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.1.8
 * @package    Book_Review
 * @subpackage Book_Review/includes
 * @author     Donna Peplinskie <donnapep@gmail.com>
 */
class Book_Review_i18n {
  /**
   * The domain specified for this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $domain    The domain identifier for this plugin.
   */
  private $domain;

  /**
   * Load the plugin text domain for translation.
   *
   * @since    2.1.8
   */
  public function load_plugin_textdomain() {
    load_plugin_textdomain(
      $this->domain,
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );
  }

  /**
   * Set the domain equal to that of the specified domain.
   *
   * @since    2.1.8
   * @param    string    $domain    The domain that represents the locale of this plugin.
   */
  public function set_domain( $domain ) {
    $this->domain = $domain;
  }
}