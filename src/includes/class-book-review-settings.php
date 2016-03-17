<?php

/**
 * Handle getting settings from the database.
 *
 * @link       http://wpreviewplugins.com/
 * @since      2.3.0
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 */

/**
 * Handle getting settings from the database.
 *
 * Get settings from the database and use defaults when appropriate.
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 * @author     WP Review Plugins <support@wpreviewplugins.com>
 */
class Book_Review_Settings {

  /**
   * Initialize the class and set its properties.
   *
   * @since     2.3.0
   */
  public function __construct() {}

  /**
   * Retrieve the general settings.
   *
   * @since    2.3.0
   *
   * @return   array    Array of general settings.
   */
  public function get_book_review_general_option( $use_defaults = true ) {
    $settings = get_option( 'book_review_general' );

    if ( $use_defaults ) {
      $defaults = array(
        'book_review_box_position' => 'top',
        'book_review_bg_color' => '',
        'book_review_border_color' => '',
        'book_review_border_width' => 1,
        'book_review_post_types' => array(
          'post' => '1'
        )
      );

      // Maintain backwards compatibility by showing the meta box for posts and all custom post types,
      // but only if the Post Types setting has never been saved.
      $args = array(
        'public'   => true,
        '_builtin' => false
      );

      $cpts = get_post_types( $args, 'names' );

      // Show on custom post types by default. Will be overridden by settings later if applicable.
      foreach ( $cpts as $cpt ) {
        $defaults['book_review_post_types'][$cpt] = '1';
      }

      $settings = wp_parse_args( $settings, $defaults );
    }

    return apply_filters( 'book_review_general_option', $settings );
  }

  /**
   * Retrieve the ratings settings.
   *
   * @since    2.3.0
   *
   * @return   array    Array of ratings settings.
   */
  public function get_book_review_ratings_option( $use_defaults = true ) {
    $settings = get_option( 'book_review_ratings' );

    if ( $use_defaults ) {
      $defaults = array(
        'book_review_rating_home' => '',
        'book_review_rating_default' => '1',
        'book_review_rating_image1' => '',
        'book_review_rating_image2' => '',
        'book_review_rating_image3' => '',
        'book_review_rating_image4' => '',
        'book_review_rating_image5' => ''
      );

      $settings = wp_parse_args( $settings, $defaults );
    }

    return apply_filters( 'book_review_ratings_option', $settings );
  }

  /**
   * Retrieve the links settings.
   *
   * @since    2.3.0
   *
   * @return   array    Array of links settings.
   */
  public function get_book_review_links_option( $use_defaults = true ) {
    $settings = get_option( 'book_review_links' );

    if ( $use_defaults ) {
      $defaults = array(
        'book_review_target' => '',
        'sites' => array(
          'book_review_goodreads' => array(
            'type' => 'button',
            'text' => 'Goodreads',
            'url' => '',
            'active' => '0'
          ),
          'book_review_barnes_noble' => array(
            'type' => 'button',
            'text' => 'Barnes & Noble',
            'url' => '',
            'active' => '0'
          )
        )
      );

      $settings = wp_parse_args( $settings, $defaults );
    }

    return apply_filters( 'book_review_links_option', $settings );
  }

  /**
   * Retrieve the fields settings.
   *
   * @since    2.3.0
   *
   * @return   array    Array of fields settings.
   */
  public function get_book_review_fields_option() {
    $defaults = array(
      'fields' => array()
    );
    $settings = get_option( 'book_review_fields' );
    $settings = wp_parse_args( $settings, $defaults );

    return apply_filters( 'book_review_fields_option', $settings );
  }

  /**
   * Retrieve the advanced settings.
   *
   * @since    2.3.0
   *
   * @return   array    Array of advanced settings.
   */
  public function get_book_review_advanced_option() {
    $defaults = array(
      'book_review_api_key' => '',
      'book_review_country' => ''
    );
    $settings = get_option( 'book_review_advanced' );
    $settings = wp_parse_args( $settings, $defaults );

    return apply_filters( 'book_review_advanced_option', $settings );
  }
}