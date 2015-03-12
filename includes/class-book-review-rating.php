<?php

/**
 * Register all actions, filters and shortcodes for the plugin.
 *
 * @link       http://donnapeplinskie.com
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 */

/**
 * Register all actions, filters and shortcodes for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions, filters and shortcodes.
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 * @author     Donna Peplinskie <donnapep@gmail.com>
 */
class Book_Review_Rating {
  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @var      string    $plugin_name       The name of the plugin.
   */
  public function __construct( $plugin_name ) {
    $this->plugin_name = $plugin_name;
  }

  /**
   * Return the URL of the rating image.
   *
   * @since    1.0.0
   * @var      string    $rating    User rating of the book.
   * @return   string    URL of the rating image.
   */
  public function get_rating_image( $rating ) {
    if ( !empty( $rating ) && ( $rating != '-1' ) ) {
      $ratings_defaults = array(
        'book_review_rating_default' => 1
      );
      $ratings_option = get_option( 'book_review_ratings' );
      $ratings_option = wp_parse_args( $ratings_option, $ratings_defaults );

      // Use default images.
      if ( $ratings_option['book_review_rating_default'] == '1' ) {
        if ( $rating == '1' ) {
          $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/one-star.png';
        }
        else if ( $rating == '2' ) {
          $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/two-star.png';
        }
        else if ( $rating == '3' ) {
          $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/three-star.png';
        }
        else if ( $rating == '4' ) {
          $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/four-star.png';
        }
        else if ( $rating == '5' ) {
          $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/five-star.png';
        }
      }
      // Use custom images.
      else {
        $src = $ratings_option['book_review_rating_image' . $rating];
      }

      return $src;
    }
    else {
      return '';
    }
  }
}