<?php

/**
 * Fired during plugin activation.
 *
 * @link       http://donnapeplinskie.com
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.1.8
 * @package    Book_Review
 * @subpackage Book_Review/includes
 * @author     Donna Peplinskie <donnapep@gmail.com>
 */
class Book_Review_Activator {
  /**
   * The current version of the plugin.
   *
   * @since    2.1.8
   * @access   protected
   * @var     string
   */
  const VERSION = '2.1.10';

  /**
   * Fired when the plugin is activated.
   *
   * @since    2.1.6
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate( $network_wide ) {
    if ( function_exists( 'is_multisite' ) && is_multisite() ) {
      if ( $network_wide ) {
        // Get all blog ids.
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {
          switch_to_blog( $blog_id );
          self::single_activate();
        }

        restore_current_blog();
      }
      else {
        self::single_activate();
      }
    }
    else {
      self::single_activate();
    }
  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    2.1.6
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids() {
    global $wpdb;

    // Get an array of blog ids.
    $sql = "SELECT blog_id FROM $wpdb->blogs
      WHERE archived = '0' AND spam = '0'
      AND deleted = '0'";

    return $wpdb->get_col( $sql );
  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    2.1.6
   */
  private static function single_activate() {
    $version = get_option( 'book_review_version' );

    if ( empty( $version ) ) {
      add_option( 'book_review_version', self::VERSION );
      self::create_tables();
      self::convert_data();
    }
    else if ( $version != self::VERSION ) {
      update_option( 'book_review_version', self::VERSION );
    }
  }

  /**
   * Creates the tables that the plugin uses.
   *
   * @since    2.1.6
   */
  private static function create_tables() {
    global $wpdb;

    /*
     * We'll set the default character set and collation for this table.
     * If we don't do this, some characters could end up being converted
     * to just ?'s when saved in our table.
     */
    $charset_collate = '';

    if ( !empty( $wpdb->charset ) ) {
      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if ( !empty( $wpdb->collate ) ) {
      $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    // Create table for custom links.
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}book_review_custom_links (
      custom_link_id int NOT NULL AUTO_INCREMENT,
      text varchar(100) NOT NULL,
      image_url varchar(200),
      active int(1) NOT NULL DEFAULT 1,
      UNIQUE KEY id (custom_link_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // Create table for custom link URLs.
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}book_review_custom_link_urls (
      post_id bigint(20) NOT NULL,
      custom_link_id int NOT NULL,
      url varchar(200),
      UNIQUE KEY id (post_id, custom_link_id)
    ) $charset_collate;";

    dbDelta( $sql );
  }

  /**
   * Migrates custom links from native tables to custom tables.
   *
   * @since    2.1.6
   */
  private static function convert_data() {
    global $wpdb;

    $links = array();
    $links_option = get_option( 'book_review_links' );
    $target = isset( $links_option['book_review_link_target'] ) ? $links_option['book_review_link_target'] : -1;
    $num_links = isset( $links_option['book_review_num_links'] ) ? ( int )$links_option['book_review_num_links'] : 0;

    // Iterate over all of the custom links in the options table.
    for ( $i = 1; $i <= $num_links; $i++ ) {
      $text = $links_option['book_review_link_text' . $i];
      $image_url = $links_option['book_review_link_image' . $i];
      $new_link = array(
        'text' => $text,
        'image_url' => $image_url
      );

      array_push( $links, $new_link );
    }

    // Add the link text and image to the book_review_custom_links table.
    foreach ( $links as $link ) {
      $success = $wpdb->insert(
        $wpdb->prefix . "book_review_custom_links",
        array(
          'text' => $link['text'],
          'image_url' => $link['image_url']
        ),
        array( '%s', '%s' )
      );

      // In case of failure, remove any rows that may already have been inserted.
      if ( $success != 1 ) {
        $sql = "DELETE FROM {$wpdb->prefix}book_review_custom_links";
        $wpdb->query($sql);
        $wpdb->print_error();
        return;
      }
    }

    // Delete the links option and then save the target back since it's the
    // only setting that needs to be preserved.
    if ( $target != -1 ) {
      delete_option( 'book_review_links' );
      add_option( 'book_review_links', array( 'book_review_target' => $target ) );
    }

    // Populate the book_review_custom_link_urls table.
    $sql = "INSERT INTO {$wpdb->prefix}book_review_custom_link_urls (post_id, custom_link_id, url)
      SELECT meta.post_id, links.custom_link_id, meta.meta_value
      FROM {$wpdb->prefix}postmeta AS meta
      INNER JOIN {$wpdb->prefix}book_review_custom_links AS links ON links.custom_link_id = SUBSTRING(meta.meta_key, -1)
      WHERE meta_key IN ('book_review_link1', 'book_review_link2', 'book_review_link3', 'book_review_link4', 'book_review_link5')
        AND meta_value <> ''";

    // Run query.
    $success = $wpdb->query($sql);

    // There was a problem executing the query.
    if ($success === false) {
      $wpdb->print_error();
      return;
    }
    // Delete links from the postmeta table.
    else {
      $sql = "DELETE FROM {$wpdb->prefix}postmeta
        WHERE meta_key IN ('book_review_link1', 'book_review_link2', 'book_review_link3', 'book_review_link4', 'book_review_link5')";
      $success = $wpdb->query($sql);

      if ($success === false) {
        $wpdb->print_error();
        return;
      }
    }
  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    2.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  // TODO: Should this be static or non-static?
  public static function activate_new_site( $blog_id ) {
    if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
      return;
    }

    switch_to_blog( $blog_id );
    self::single_activate();
    restore_current_blog();
  }
}