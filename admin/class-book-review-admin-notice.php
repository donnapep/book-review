<?php

/**
 * Display admin notice when the plugin is activated.
 *
 * @link       http://wpreviewplugins.com/
 * @since      2.1.13
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 */

/**
 * Display admin notice when the plugin is activated.
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
 */

class Book_Review_Admin_Notice {

  /**
   * The ID of this plugin.
   *
   * @since    2.1.13
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Class constructor.
   *
   * @since    2.1.13
   */
  public function __construct( $plugin_name ) {
    $this->plugin_name = $plugin_name;
  }

  /**
   * Show a notice when the plugin is activated.
   *
   * @since    2.1.13
   */
  public function show_activation_notice() {
    global $pagenow;

    if ( $pagenow == 'plugins.php' ) {
      // Show the banner only if the user hasn't already dismissed it.
      if ( !get_user_meta( get_current_user_id(), '_book_review_notice_dismissed', true ) ) {
        include_once( 'partials/book-review-admin-notice.php' );
      }
    }
  }

  /**
   * Hide the notice and remember it's hidden state.
   *
   * @since    2.1.13
   */
  public function dismiss_activation_notice() {
    if ( !isset( $_GET['book_review_dismiss_notice'] ) ) {
      return;
    }

    check_admin_referer( 'book_review_dismiss_notice', 'book_review_dismiss_notice' );
    update_user_meta( get_current_user_id(), '_book_review_notice_dismissed', 1 );
  }
}
?>