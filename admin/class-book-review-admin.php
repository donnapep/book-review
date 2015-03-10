<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://donnapeplinskie.com
 * @since      1.0.0
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 * @author     Donna Peplinskie <donnapep@gmail.com>
 */

class Book_Review_Admin {
  /**
   * The ID of this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;
  /**
   * The version of this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    2.1.8
   * @var      string    $plugin_name       The name of this plugin.
   * @var      string    $version           The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    global $wpdb;

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $wpdb->book_review_custom_links = "{$wpdb->prefix}book_review_custom_links";
  }

  /**
   * Register the stylesheets for the Dashboard.
   *
   * @since    2.1.8
   *
   * @param    string    $hook_suffix    Page hook.
   */
  public function enqueue_styles( $hook_suffix ) {
    /**
     * An instance of this class should be passed to the run() function
     * defined in Book_Review_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Book_Review_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    if ( !isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    if ( $hook_suffix == 'edit.php' ) {
      $screen = get_current_screen();

      // Check that this is the Posts admin page.
      if ( $screen->post_type == 'post' ) {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-posts-admin.css', array(), $this->version, 'all' );
      }
    }
    else if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-meta-box.css', array(), $this->version, 'all' );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-admin.css', array(), $this->version, 'all' );
    }
  }

  /**
   * Register the JavaScript for the dashboard.
   *
   * @since    2.1.8
   *
   * @param    string    $hook_suffix    Page hook.
   */
  public function enqueue_scripts( $hook_suffix ) {
    /**
     * An instance of this class should be passed to the run() function
     * defined in Book_Review_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Book_Review_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    if ( !isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
      wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-review-admin-meta-box.js', array( 'jquery' ), $this->version, false );
      wp_enqueue_script( 'jquery-ui-spinner' );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
       wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-review-admin.js', array( 'jquery', 'wp-color-picker' ), $this->version, false );
      // Localize the script - part of delete custom link functionality.
      // $translation_array = array( 'confirm_message' => __( 'Are you sure you want to delete this link? '.
      //   'All of the associated URLs that have been entered in the "Book Info" section of ' .
      //   'every post will also be deleted. This action cannot be undone.', $this->plugin_name ) );
      // wp_localize_script( $handle, 'book_review_confirm', $translation_array );
    }
  }

  /**
   * Register the administration menu for this plugin into the WordPress
   * Dashboard menu.
   *
   * @since    2.0.0
   */
  public function add_plugin_admin_menu() {
    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress
     *        administration menu functions.
     *
     *        Administration Menus:
     *        http://codex.wordpress.org/Administration_Menus
     */
    $this->plugin_screen_hook_suffix = add_options_page(
      __( 'Book Review Settings', $this->plugin_name ),
      __( 'Book Review', $this->plugin_name ),
      'manage_options',
      $this->plugin_name,
      array( $this, 'display_plugin_admin_page' )
    );
  }

  /**
   * Render the settings page for this plugin.
   *
   * @since    2.0.0
   */
  public function display_plugin_admin_page() {
    include_once( 'partials/book-review-admin-tabs.php' );
  }

  /**
   * Add tabbed navigation.
   *
   * @since    2.1.6
   */
  public function render_tabs( ) {
    $tabs = apply_filters( 'book_review_tabs', array(
      'appearance' => __( 'Appearance', $this->plugin_name ),
      'images' => __( 'Rating Images', $this->plugin_name ),
      'links' => __( 'Links', $this->plugin_name ),
      'advanced' => __( 'Advanced', $this->plugin_name )
    ) );

    if ( isset ( $_GET['tab'] ) ) {
      $active_tab = $_GET['tab'];
    }
    else {
      $active_tab = 'appearance';
    }

    foreach( $tabs as $tab => $name ) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';
      echo "<a class='nav-tab$class' href='?page=book-review&tab=$tab'>$name</a>";
    }
  }

  /**
   * Display the content for a particular tab.
   *
   * @since    2.1.4
   */
  public function render_tabbed_content() {
    if ( isset ( $_GET['tab'] ) ) {
      $active_tab = $_GET['tab'];
    }
    else {
      $active_tab = 'appearance';
    }

    $tooltip = '<img src="' . plugins_url( 'images/tooltip.gif', __FILE__ ) . '" />';

    do_action( 'book_review_before_tabs' );

    if ( $active_tab == 'appearance' ) {
      $general_defaults = array(
        'book_review_box_position' => 'top',
        'book_review_bg_color' => '',
        'book_review_border_color' => '',
        'book_review_border_width' => '1',
        'book_review_date_format' => 'none',
      );

      $general = get_option( 'book_review_general' );
      $general = wp_parse_args( $general, $general_defaults );

      include_once( 'partials/book-review-admin-appearance.php' );
    }
    else if ( $active_tab == 'images' ) {
      $ratings_defaults = array(
        'book_review_rating_home' => 0,
        'book_review_rating_default' => 1
      );
      $ratings = get_option( 'book_review_ratings' );
      $ratings = wp_parse_args( $ratings, $ratings_defaults );

      include_once( 'partials/book-review-admin-images.php' );
    }
    else if ( $active_tab == 'links' ) {
      $links_defaults = array(
        'book_review_target' => 0,
      );
      $links_option = get_option( 'book_review_links', $links_defaults );
      $links_option = wp_parse_args( $links_option, $links_defaults );

      // Get custom links.
      global $wpdb;

      $results = $wpdb->get_results( "SELECT * FROM {$wpdb->book_review_custom_links}" );

      include_once( 'partials/book-review-admin-links.php' );
    }
    else if ( $active_tab == 'advanced' ) {
      $advanced = get_option( 'book_review_advanced' );

      include_once( 'partials/book-review-admin-advanced.php' );
    }

    do_action( 'book_review_after_tabs' );
  }

  /**
   * Add settings action link to the plugins page.
   *
   * @since    2.0.0
   */
  public function add_action_links( $links ) {
    return array_merge(
      array(
        'settings' => '<a href="' . admin_url( 'options-general.php?page=' .
          $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) .
        '</a>'
      ), $links);
  }

  /**
   * Register settings so that they will be saved.
   *
   * @since    1.0.0
   */
  public function init_menu() {
    register_setting( 'general_options', 'book_review_general', array( $this, 'validate_appearance' )  );
    register_setting( 'ratings_options', 'book_review_ratings', array( $this, 'validate_rating_images' ) );
    register_setting( 'links_options', 'book_review_links', array( $this, 'validate_links' ) );
    register_setting( 'advanced_options', 'book_review_advanced', array( $this, 'validate_advanced' ) );
  }

  /**
   * Validate fields on the Appearance tab.
   *
   * @since     2.1.9
   */
  public function validate_appearance( $input ) {
    $output = array();
    $output['book_review_box_position'] = $input['book_review_box_position'];
    $output['book_review_bg_color'] = $input['book_review_bg_color'];
    $output['book_review_border_color'] = $input['book_review_border_color'];
    $output['book_review_date_format'] = $input['book_review_date_format'];

    // Validate border width.
    $input['book_review_border_width'] = trim( $input['book_review_border_width'] );
    $output['book_review_border_width'] = intval( $input['book_review_border_width'] );

    if ( !empty( $input['book_review_border_width'] ) && ( intval( $input['book_review_border_width'] ) == 0 ) ) {
      add_settings_error(
        'book_review_appearance',
        'border-width-error',
        'Review Box Border Width must be numeric.'
      );
    }

    return apply_filters( 'book_review_validate_appearance', $output, $input );
  }

  /**
   * Validate rating image URLs.
   *
   * @since     1.0.0
   */
  public function validate_rating_images( $input ) {
    $image_error = false;
    $output = array();
    $output['book_review_rating_home'] = isset( $input['book_review_rating_home'] ) ? $input['book_review_rating_home'] : '';
    $output['book_review_rating_default'] = isset( $input['book_review_rating_default'] ) ? $input['book_review_rating_default'] : '';

    // Iterate over every rating image URL field.
    for ( $i = 1; $i <= 5; $i++ ) {
      $value = trim( $input['book_review_rating_image' . $i] );

      // Not using default rating images.
      if ( empty( $output['book_review_rating_default'] ) ) {
        if ( empty( $value ) ) {
          $image_error = true;
        }
        else {
          $output['book_review_rating_image' . $i] = esc_url_raw( $value );
        }
      }
      // Using default rating images. Save them anyway.
      else {
        $output['book_review_rating_image' . $i] = esc_url_raw( $value );
      }
    }

    if ( $image_error ) {
      add_settings_error(
        'book_review_ratings',
        'image-error',
        'Rating Image URLs are required fields when not using the default
          rating images. Please ensure you enter a URL for each rating.'
      );
    }

    return apply_filters( 'book_review_validate_rating_images', $output, $input );
  }

  /**
   * Validate link image URLs.
   *
   * @since     1.0.0
   */
  public function validate_links( $input ) {
    $output = array();
    $output['book_review_target'] = isset( $input['book_review_target'] ) ? $input['book_review_target'] : '';

    if ( isset( $input ) ) {
      foreach ( $input as $key => $value ) {
        $error = false;

        // Custom Links
        if ( is_array( $value ) ) {
          $id = '';
          $text = '';
          $image_url = '';

          foreach( $value as $link_key => $link_value ) {
            // An unchecked checkbox will not be POSTed and so its value will not be set.
            $active = 0;

            if ( $link_key == 'id' ) {
              $id = trim( $link_value );
            }
            else if ( $link_key == 'text' ) {
              $text = sanitize_text_field( $link_value );

              // Link Text is a required field.
              if ( empty( $text ) ) {
                $error = true;
              }
            }
            else if ( $link_key == 'image' ) {
              $image_url = esc_url_raw( $link_value );
            }
            else if ( $link_key == 'active' ) {
              $active = (int)$link_value;
            }
          }

          if ( !$error ) {
            global $wpdb;

            // Insert a new row.
            if ( empty( $id ) ) {
              $wpdb->insert(
                $wpdb->book_review_custom_links,
                array(
                  'text' => $text,
                  'image_url' => $image_url,
                  'active' => $active
                ),
                array( '%s', '%s', '%d' )
              );
            }
            // Update the existing row.
            else {
              $wpdb->update(
                $wpdb->book_review_custom_links,
                array(
                  'text' => $text,
                  'image_url' => $image_url,
                  'active' => $active
                ),
                array( 'custom_link_id' => $id ),
                array( '%s', '%s', '%d' ),
                array( '%d' )
              );
            }
          }
          else {
            add_settings_error(
              'book_review_links',
              'link-error',
              'Link Text is a required field. Please ensure you enter text for each link.'
            );
          }
        }
      }
    }

    return apply_filters( 'book_review_validate_links', $output, $input );
  }

  /**
   * Validate fields on Advanced tab.
   *
   * @since     2.1.6
   */
  public function validate_advanced( $input ) {
    $output = array();
    $api_key = $input['book_review_api_key'];

    $output['book_review_api_key'] = isset( $api_key ) ? sanitize_text_field( $api_key ) : '';

    return apply_filters( 'book_review_validate_advanced', $output, $input );
  }

  /**
   * Render options in the Release Date Format dropdown.
   *
   * @since     2.0.0
   */
  public function render_date_format_field() {
    $formats = array(
      'none' => __( 'None', $this->plugin_name ),
      'short' => date( 'n/j/Y', current_time( 'timestamp', 0 ) ),
      'european' => date( 'j/n/Y', current_time( 'timestamp', 0 ) ),
      'medium' => date( 'M j Y', current_time( 'timestamp', 0 ) ),
      'long' => date( 'F j, Y', current_time( 'timestamp', 0 ) ),
    );

    $options = get_option( 'book_review_general' );

    foreach( $formats as $type => $format ) {
      $selected = ( $options['book_review_date_format'] == $type ) ?
        'selected="selected"' : '';
      echo '<option value="' . $type . '" '. $selected . '>' . $format .
        '</option>';
    }
  }

  /**
  * Delete a custom link.
  */
  // public function delete_link() {
  //   $id = intval( $_POST['id'] );
  //   $is_valid_nonce = ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'book_review_delete_link' ) );

  //   if ( ( current_user_can( 'manage_options' ) ) && $is_valid_nonce ) {
  //     // TODO: Also delete the URLs associated with this custom link in the wp_postmeta table.
  //     global $wpdb;

  //     $wpdb->delete(
  //       $wpdb->book_review_custom_links,
  //       array( 'custom_link_id' => $id ),
  //       array( '%d')
  //     );

  //     $result['success'] = true;
  //   }
  //   else {
  //     $result['success'] = false;
  //   }

  //   $result = json_encode( $result );

  //   echo $result;
  //   die();
  // }

  /**
   * Add Rating column to Posts Admin screen.
   *
   * NOTE:     Filters are points of execution in which WordPress modifies data
   *           before saving it or sending it to the browser.
   *
   *           Filters: http://codex.wordpress.org/Plugin_API#Filters
   *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
   *
   * @since    1.9.0
   */
  public function column_heading( $columns ) {
    return array_merge( $columns, array( 'rating' => __( 'Rating', $this->plugin_name ) ) );
  }

  /**
   * Populate Rating column on Posts Admin screen.
   *
   * @since    1.9.0
   */
  public function column_content( $column, $post_id ) {
    if ( $column == 'rating' ) {
      $plugin = Book_Review::get_instance();
      $values = get_post_custom( $post_id );

      if ( isset( $values['book_review_rating'] ) != null ) {
        $rating = $values['book_review_rating'][0];

        if ( !empty( $rating ) && ( $rating != '-1' ) ) {
          echo '<img src="' . $plugin->get_rating()->get_rating_image( $rating ) .
            '" class="book_review_column_rating" />';
        }
      }
    }
  }
}
?>