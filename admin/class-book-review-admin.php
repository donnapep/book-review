<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wpreviewplugins.com/
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
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
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
   * The settings of this plugin.
   *
   * @since    2.3.0
   * @access   private
   * @var      Book_Review_Settings    $settings    Instance of Book_Review_Settings for
   *                                                getting the settings.
   */
  private $settings;

  /**
   * The book information.
   *
   * @since    2.3.0
   * @access   private
   * @var      Book_Review_Book_Info    $book_info   Instance of Book_Review_Book_Info for
   *                                                 getting information about a book.
   */
  private $book_info;

  /**
   * Initialize the class and set its properties.
   *
   * @since    2.1.8
   * @param    string                 $plugin_name  Plugin name
   * @param    string                 $version      Plugin version
   * @param    Book_Review_Settings   $settings     Instance of Book_Review_Settings for
   *                                                  getting the settings.
   * @param    Book_Review_Book_Info  $book_info    Instance of Book_Review_Book_Info for
   *                                                  getting information about a book.
   */
  public function __construct( $plugin_name, $version, $settings, $book_info ) {
    global $wpdb;

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->settings = $settings;
    $this->book_info = $book_info;

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
    else if ( $hook_suffix == 'plugins.php' ) {
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-admin-notice.css', array(), $this->version, 'all' );
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

      $translation_array = array(
        'no_isbn' => esc_html__( 'Please enter an ISBN.', $this->plugin_name ),
        'not_found' => esc_html__( 'A book with this ISBN was not found in the Google Books database.', $this->plugin_name ),
        'unknown_error' => sprintf( __( '<p>Sorry, but something went wrong. Please check to ensure that you have entered your Google API Key correctly on the <em>Advanced</em> tab of the <a href="%s">Book Review Settings</a>, and that you have selected a <em>Country</em> from the dropdown.</p><p>Please also check to ensure that the correct IP address of your server has been entered into the <a href="%s" target="_blank">Google Developers Console</a>. See the <a href="%s" target="_blank">documentation</a> for more information.</p><p>If you are still having trouble, please leave a message in the <a href="%s" target="_blank">General Support forum</a>. Be sure to include the URL of your web site in your post. Thanks!', $this->plugin_name ), esc_url( admin_url( 'options-general.php?page=' . $this->plugin_name ) . '&tab=advanced' ), esc_url( 'https://code.google.com/apis/console' ), esc_url( 'http://wpreviewplugins.com/documentation/settings-advanced/' ), esc_url( 'http://wpreviewplugins.com/support/forum/general-support/' ) ) );
      wp_localize_script( $this->plugin_name, 'book_review_google_api', $translation_array );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
       wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-review-admin.js',
        array( 'jquery', 'wp-color-picker' ), $this->version, false );
       wp_enqueue_script( 'jquery-ui-sortable' );
    }
  }

  /**
   * Register the administration menu for this plugin into the WordPress
   * Dashboard menu.
   *
   * @since    2.0.0
   */
  public function add_plugin_admin_menu() {
    $this->plugin_screen_hook_suffix = add_options_page(
      esc_html__( 'Book Review Settings', $this->plugin_name ),
      esc_html__( 'Book Review', $this->plugin_name ),
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
  public function render_tabs() {
    $tabs = apply_filters( 'book_review_tabs', array(
      'appearance' => __( 'Appearance', $this->plugin_name ),
      'images' => __( 'Rating Images', $this->plugin_name ),
      'links' => __( 'Links', $this->plugin_name ),
      'fields' => __( 'Custom Fields', $this->plugin_name ),
      'advanced' => __( 'Advanced', $this->plugin_name )
    ) );

    if ( isset( $_GET['tab'] ) ) {
      $active_tab = $_GET['tab'];
    }
    else {
      $active_tab = 'appearance';
    }

    foreach ( $tabs as $tab => $name ) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';

      echo '<a class="' . esc_attr( 'nav-tab' . $class ) . '" href="' . esc_url( '?page=book-review&tab=' . $tab )
        . '">' . esc_html( $name ) . '</a>';
    }
  }

  /**
   * Display the content for a particular tab.
   *
   * @since    2.1.4
   */
  public function render_tabbed_content() {
    if ( isset( $_GET['tab'] ) ) {
      $active_tab = $_GET['tab'];
    }
    else {
      $active_tab = 'appearance';
    }

    do_action( 'book_review_before_tabs' );

    if ( $active_tab == 'appearance' ) {
      $general_option = $this->settings->get_book_review_general_option();
      $post_types = $this->get_post_types();
      $keys = array_keys( $post_types );

      include_once( 'partials/book-review-admin-appearance.php' );
    }
    else if ( $active_tab == 'images' ) {
      $ratings_option = $this->settings->get_book_review_ratings_option();

      include_once( 'partials/book-review-admin-images.php' );
    }
    else if ( $active_tab == 'links' ) {
      $links_option = $this->settings->get_book_review_links_option();

      // Get links.
      global $wpdb;

      $results = $wpdb->get_results( "SELECT * FROM {$wpdb->book_review_custom_links}" );

      include_once( 'partials/book-review-admin-links.php' );
    }
    else if ( $active_tab == 'fields' ) {
      $fields_option = $this->settings->get_book_review_fields_option();

      include_once( 'partials/book-review-admin-custom-fields.php' );
    }
    else if ( $active_tab == 'advanced' ) {
      $advanced_option = $this->settings->get_book_review_advanced_option();

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
        'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=' .
          $this->plugin_name ) ) . '">' . esc_html__( 'Settings', $this->plugin_name ) . '</a>'
      ), $links);
  }

  /**
   * Register settings so that they will be saved.
   *
   * @since    1.0.0
   */
  public function register_settings() {
    register_setting( 'general_options', 'book_review_general', array( $this, 'save_appearance' )  );
    register_setting( 'ratings_options', 'book_review_ratings', array( $this, 'save_rating_images' ) );
    register_setting( 'links_options', 'book_review_links', array( $this, 'save_links' ) );
    register_setting( 'fields_options', 'book_review_fields', array( $this, 'save_custom_fields' ) );
    register_setting( 'advanced_options', 'book_review_advanced', array( $this, 'save_advanced' ) );
  }

  /**
   * Sanitize a checkbox.
   *
   * @since    2.3.0
   * @param    string       $value        Unsanitized checkbox value
   * @return   string                     Sanitized checkbox value
   */
  public function sanitize_checkbox( $value ) {
    if ( '1' === trim( $value ) ) {
      return '1';
    }
    else {
      return '';
    }
  }

  /**
   * Sanitize text.
   *
   * @since    2.3.0
   * @param    string       $text     Unsanitized text
   * @return   string                 Sanitized text
   */
  public function sanitize_text( $text ) {
    return sanitize_text_field( $text );
  }

  /**
   * Sanitize a URL.
   *
   * @since    2.3.0
   * @param    string       $url        Unsanitized URL
   * @return   string                   Sanitized URL
   */
  public function sanitize_url( $url ) {
    return esc_url_raw( $url );
  }

  /**
   * Save Appearance tab.
   *
   * @since     2.1.9
   */
  public function save_appearance( $input = array() ) {
    $output = array();

    foreach ( $input as $key => $value ) {
      // Post Types
      if ( is_array( $value ) ) {
        foreach ( $value as $post_type => $post_type_value ) {
          $sanitized_post_type = apply_filters( 'sanitize_book_review_post_type', $post_type, $post_type_value );

          // Save if valid post type.
          if ( $sanitized_post_type !== false ) {
            $output[$key][$post_type] = $sanitized_post_type;
          }
        }
      }
      else {
        $sanitized_value = apply_filters( 'sanitize_' . $key, $value );

        // Save if valid value.
        if ( $sanitized_value !== false ) {
          $output[$key] = $sanitized_value;
        }
      }
    }

    // Save unchecked post types as they will not be POSTed.
    $post_types = array_keys( $this->get_post_types() );

    // Check if any post types have been saved thus far.
    if ( ( count( $post_types ) > 0) && !isset( $output['book_review_post_types'] ) ) {
      $output['book_review_post_types'] = array();
    }

    // Save unchecked post types as '0'.
    foreach ( $post_types as $post_type ) {
      if ( !array_key_exists( $post_type, $output['book_review_post_types'] ) ) {
        $output['book_review_post_types'][$post_type] = apply_filters( 'sanitize_book_review_post_type', $post_type, '0' );
      }
    }

    return $output;
  }

  /**
   * Sanitize review box position.
   *
   * @since    2.3.0
   * @param    string    $position    Unsanitized position
   * @return   string                 Sanitized position
   */
  public function sanitize_position( $position ) {
    $position = sanitize_text_field( $position );
    $allowed_positions = array( 'top', 'bottom' );

    if ( in_array( $position, $allowed_positions ) ) {
      return $position;
    }

    return 'top';
  }

  /**
   * Sanitize a hex color.
   *
   * @since    2.3.0
   * @param    string    $color    Unsanitized hex color
   * @return   string              Sanitized hex color|empty string if invalid
   */
  public function sanitize_color( $color ) {
    $color = $this->sanitize_hex_color( $color );

    if ( is_null( $color ) ) {
      return '';
    }
    else {
      return $color;
    }
  }

  /**
   * Sanitize a hex color.
   *
   * @since    2.3.0
   * @param    string       $color    Unsanitized hex color
   * @return   string|void            ''|3 or 6 digit hex color (with #)|void
   */
  private function sanitize_hex_color( $color ) {
    if ( '' === $color ) {
      return '';
    }

    // 3 or 6 hex digits, or the empty string.
    if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
      return $color;
    }
  }

  /**
   * Sanitize border width.
   *
   * @since    2.3.0
   * @param    string    $width    Unsanitized border width
   * @return   int                 Sanitized border width|false if invalid
   */
  public function sanitize_border_width( $width ) {
    $width = trim( $width );
    $sanitized_width = intval( $width );

    // Zero width
    if ( $width == '0' ) {
      return 0;
    }

    // Non-integer width or negative width
    if ( ( $sanitized_width == 0 ) || ( $sanitized_width < 0 ) ) {
      $general_option = $this->settings->get_book_review_general_option( false );

      if ( $sanitized_width == 0 ) {
        add_settings_error(
          'book_review_appearance',
          'non-integer-border-width-error',
          esc_html__( 'Review Box Border Width must be numeric.', $this->plugin_name )
        );
      }
      else {
        add_settings_error(
          'book_review_appearance',
          'negative-border-width-error',
          esc_html__( 'Review Box Border Width must be greater than or equal to 0.', $this->plugin_name )
        );
      }

      // Return previous value.
      return isset( $general_option['book_review_border_width'] ) ? $general_option['book_review_border_width'] : false;
    }

    return $sanitized_width;
  }

  /**
   * Sanitize a post type value.
   *
   * @since    2.3.0
   * @param    string       $post_type    Post type
   * @param    string       $value        Unsanitized post type value
   * @return   string|bool                '1'|'0'|false if invalid post type
   */
  public function sanitize_post_type( $post_type, $value ) {
    $allowed_post_types = array_keys( $this->get_post_types() );

    if ( in_array( $post_type, $allowed_post_types ) ) {
      if ( ( $value === '0' ) || ( $value === '1' ) ) {
        return $value;
      }
    }

    // Invalid post type or post type value.
    return false;
  }

  /**
   * Save Rating Images tab.
   *
   * @since     1.0.0
   *
   */
  public function save_rating_images( $input = array() ) {
    $output = array();

    foreach ( $input as $key => $value ) {
      // Search backwards starting from $key length characters from the end.
      if ( strrpos( $key, 'book_review_rating_image', -strlen( $key ) ) !== false ) {
        $sanitized_url = apply_filters( 'sanitize_' . $key, $key, $value,
          isset( $output['book_review_rating_default'] ) ? $output['book_review_rating_default'] : '' );

        // Save if valid URL.
        if ( $sanitized_url !== '' ) {
          $output[$key] = $sanitized_url;
        }
      }
      else {
        $output[$key] = apply_filters( 'sanitize_' . $key, $value );
      }
    }

    // Save unchecked checkboxes as they will not be POSTed.
    if ( !array_key_exists( 'book_review_rating_home', $output ) ) {
      $output['book_review_rating_home'] = apply_filters( 'sanitize_book_review_rating_home', '' );
    }

    if ( !array_key_exists( 'book_review_rating_default', $output ) ) {
      $output['book_review_rating_default'] = apply_filters( 'sanitize_book_review_rating_default', '' );
    }

    return $output;
  }

  /**
   * Sanitize a rating image URL.
   *
   * @since    2.3.0
   * @param    string       $option         Option name
   * @param    string       $url            Unsanitized URL
   * @param    string       $use_default    Sanitized value for 'Default Rating Images' checkbox
   * @return   string                       Sanitized rating image URL|empty string if invalid
   */
  public function sanitize_rating_image( $option, $url, $use_default ) {
    $url = $this->sanitize_url( $url );

    // Not using default rating images and no rating image URL.
    if ( ( $use_default === '' ) && ( $url === '' ) ) {
      $ratings_option = $this->settings->get_book_review_ratings_option( false );

      add_settings_error(
        'book_review_ratings',
        'rating-image-error',
        esc_html__( 'Rating Image URLs are required fields when not using the default rating images. Please ensure you enter a URL for each rating.', $this->plugin_name )
      );

      // Return previous value.
      return isset( $ratings_option[$option] ) ? $ratings_option[$option] : '';
    }

    return $url;
  }

  /**
   * Save Links tab.
   *
   * @since     1.0.0
   */
  public function save_links( $input = array() ) {
    $output = array();

    foreach ( $input as $key => $value ) {
      // Links
      if ( is_array( $value ) ) {
        $id = '';
        $text = '';
        $url = '';
        $active = 0;

        foreach ( $value as $link_key => $link_value ) {
          if ( $link_key == 'id' ) {
            $id = apply_filters( 'sanitize_book_review_link_id', $link_value );
          }
          else if ( $link_key == 'text' ) {
            $text = apply_filters( 'sanitize_book_review_link_text', $link_value );
          }
          else if ( $link_key == 'image' ) {
            $url = apply_filters( 'sanitize_book_review_link_url', $link_value );
          }
          else if ( $link_key == 'active' ) {
            $active = apply_filters( 'sanitize_book_review_link_status', $link_value );
          }
        }

        $this->save_link( $id, $text, $url, $active );
      }
      else {
        $output[$key] = apply_filters( 'sanitize_' . $key, $value );
      }
    }

    // Save unchecked checkbox as it will not be POSTed.
    if ( !array_key_exists( 'book_review_target', $output ) ) {
      $output['book_review_target'] = apply_filters( 'sanitize_book_review_target', '' );
    }

    return $output;
  }

  /**
   * Sanitize link ID.
   *
   * @since    2.3.0
   * @param    string       $id        Unsanitized link ID
   * @return   int                     Sanitized link ID|0 if invalid
   */
  public function sanitize_link_id( $id ) {
    $id = intval( $id );

    if ( $id > 0 ) {
      return $id;
    }
    else {
      return 0;
    }
  }

  /**
   * Sanitize link text.
   *
   * @since    2.3.0
   * @param    string       $id        Unsanitized link text
   * @return   string                  Sanitized link text
   */
  public function sanitize_link_text( $text ) {
    $text = sanitize_text_field( $text );

    // Link Text is a required field.
    if ( $text === '' ) {
      add_settings_error(
        'book_review_links',
        'link-text-error',
        esc_html__( 'Link Text is a required field. Please ensure you enter text for each link.', $this->plugin_name )
      );
    }

    return $text;
  }

  /**
   * Sanitize link status.
   *
   * @since    2.3.0
   * @param    string       $status    Unsanitized link status
   * @return   int                     1
   */
  public function sanitize_link_status( $status ) {
    // If this function is called then it means the checkbox was checked.
    return 1;
  }

  /**
   * Save a link.
   *
   * @since    2.3.0
   * @param    int       $id      Sanitized link ID
   * @param    string    $text    Sanitized link text
   * @param    string    $url     Sanitized link URL
   * @param    int       $active  Sanitized link status
   */
  private function save_link( $id, $text, $url, $active ) {
    global $wpdb;

    if ( ( $id !== '' ) && ( $text !== '' ) ) {
      // Insert a new row. If $id is 0, it means a new link is being added.
      if ( $id === 0 ) {
        $wpdb->insert(
          $wpdb->book_review_custom_links,
          array(
            'text' => $text,
            'image_url' => $url,
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
            'image_url' => $url,
            'active' => $active
          ),
          array( 'custom_link_id' => $id ),
          array( '%s', '%s', '%d' ),
          array( '%d' )
        );
      }
    }
  }

  /**
   * Save Custom Fields tab.
   *
   * @since     2.2.0
   */
  public function save_custom_fields( $input = array() ) {
    $output = array();

    if ( isset( $input['fields'] ) ) {
      $show_error = false;

      foreach ( $input['fields'] as $field_id => $field_values ) {
        $label = $field_values['label'];
        $sanitized_field = apply_filters( 'sanitize_book_review_custom_field', $label );

        // Save if field name is not empty.
        if ( $sanitized_field !== '' ) {
          $output['fields'][$field_id]['label'] = $sanitized_field;
        }
        else {
          $show_error = true;
        }
      }

      if ( $show_error ) {
        add_settings_error(
          'book_review_fields',
          'custom-field-error',
          esc_html__( 'Please enter a valid custom field name.', $this->plugin_name )
        );
      }
    }

    return $output;
  }

  /**
   * Save Advanced tab.
   *
   * @since     2.1.6
   */
  public function save_advanced( $input = array() ) {
    $output = array();

    foreach ( $input as $key => $value ) {
      $output[$key] = apply_filters( 'sanitize_' . $key, $value );
    }

    return $output;
  }

  /**
   * Sanitize country.
   *
   * @since    2.3.0
   * @param    string       $country     Unsanitized country
   * @return   string                    Sanitized country
   */
  public function sanitize_country( $country ) {
    $allowed_countries = array_keys( $this->get_countries() );

    if ( in_array( $country, $allowed_countries ) ) {
      return $country;
    }

    return '';
  }

  /**
   * Add Rating column to Posts Admin screen.
   *
   * @since    1.9.0
   */
  public function column_heading( $columns ) {
    return array_merge( $columns, array( 'rating' => esc_html__( 'Rating', $this->plugin_name ) ) );
  }

  /**
   * Populate Rating column on Posts Admin screen.
   *
   * @since    1.9.0
   */
  public function column_content( $column, $post_id ) {
    if ( $column == 'rating' ) {
      $rating = $this->book_info->get_book_review_rating( $post_id );

      if ( !empty( $rating ) && ( $rating != '-1' ) ) {
        echo '<img src="' . esc_url( $this->book_info->get_book_review_rating_image( $post_id ) ) .
          '" class="book_review_column_rating">';
      }
    }
  }

  /**
   * Returns the post types to show on the Appearance tab.
   *
   * @since     2.2.0
   */
  private function get_post_types() {
    $args = array(
      'public' => true
    );

    $post_types = get_post_types( $args, 'objects' );

    // Exclude media.
    unset( $post_types['attachment'] );

    // Will include post, page and any custom post types.
    return $post_types;
  }

  /**
   * Returns the countries.
   *
   * @since     2.2.0
   */
  private function get_countries() {
    return array(
      ''   => '',
      'US' => esc_html__( 'United States' ),
      'CA' => esc_html__( 'Canada' ),
      'GB' => esc_html__( 'United Kingdom' ),
      'AF' => esc_html__( 'Afghanistan' ),
      'AX' => esc_html__( '&#197;land Islands' ),
      'AL' => esc_html__( 'Albania' ),
      'DZ' => esc_html__( 'Algeria' ),
      'AS' => esc_html__( 'American Samoa' ),
      'AD' => esc_html__( 'Andorra' ),
      'AO' => esc_html__( 'Angola' ),
      'AI' => esc_html__( 'Anguilla' ),
      'AQ' => esc_html__( 'Antarctica' ),
      'AG' => esc_html__( 'Antigua and Barbuda' ),
      'AR' => esc_html__( 'Argentina' ),
      'AM' => esc_html__( 'Armenia' ),
      'AW' => esc_html__( 'Aruba' ),
      'AU' => esc_html__( 'Australia' ),
      'AT' => esc_html__( 'Austria' ),
      'AZ' => esc_html__( 'Azerbaijan' ),
      'BS' => esc_html__( 'Bahamas' ),
      'BH' => esc_html__( 'Bahrain' ),
      'BD' => esc_html__( 'Bangladesh' ),
      'BB' => esc_html__( 'Barbados' ),
      'BY' => esc_html__( 'Belarus' ),
      'BE' => esc_html__( 'Belgium' ),
      'BZ' => esc_html__( 'Belize' ),
      'BJ' => esc_html__( 'Benin' ),
      'BM' => esc_html__( 'Bermuda' ),
      'BT' => esc_html__( 'Bhutan' ),
      'BO' => esc_html__( 'Bolivia' ),
      'BQ' => esc_html__( 'Bonaire, Saint Eustatius and Saba' ),
      'BA' => esc_html__( 'Bosnia and Herzegovina' ),
      'BW' => esc_html__( 'Botswana' ),
      'BV' => esc_html__( 'Bouvet Island' ),
      'BR' => esc_html__( 'Brazil' ),
      'IO' => esc_html__( 'British Indian Ocean Territory' ),
      'BN' => esc_html__( 'Brunei Darrussalam' ),
      'BG' => esc_html__( 'Bulgaria' ),
      'BF' => esc_html__( 'Burkina Faso' ),
      'BI' => esc_html__( 'Burundi' ),
      'KH' => esc_html__( 'Cambodia' ),
      'CM' => esc_html__( 'Cameroon' ),
      'CV' => esc_html__( 'Cape Verde' ),
      'KY' => esc_html__( 'Cayman Islands' ),
      'CF' => esc_html__( 'Central African Republic' ),
      'TD' => esc_html__( 'Chad' ),
      'CL' => esc_html__( 'Chile' ),
      'CN' => esc_html__( 'China' ),
      'CX' => esc_html__( 'Christmas Island' ),
      'CC' => esc_html__( 'Cocos Islands' ),
      'CO' => esc_html__( 'Colombia' ),
      'KM' => esc_html__( 'Comoros' ),
      'CD' => esc_html__( 'Congo, Democratic People\'s Republic' ),
      'CG' => esc_html__( 'Congo, Republic of' ),
      'CK' => esc_html__( 'Cook Islands' ),
      'CR' => esc_html__( 'Costa Rica' ),
      'CI' => esc_html__( 'Cote d\'Ivoire' ),
      'HR' => esc_html__( 'Croatia/Hrvatska' ),
      'CU' => esc_html__( 'Cuba' ),
      'CW' => esc_html__( 'Cura&Ccedil;ao' ),
      'CY' => esc_html__( 'Cyprus' ),
      'CZ' => esc_html__( 'Czech Republic' ),
      'DK' => esc_html__( 'Denmark' ),
      'DJ' => esc_html__( 'Djibouti' ),
      'DM' => esc_html__( 'Dominica' ),
      'DO' => esc_html__( 'Dominican Republic' ),
      'TP' => esc_html__( 'East Timor' ),
      'EC' => esc_html__( 'Ecuador' ),
      'EG' => esc_html__( 'Egypt' ),
      'GQ' => esc_html__( 'Equatorial Guinea' ),
      'SV' => esc_html__( 'El Salvador' ),
      'ER' => esc_html__( 'Eritrea' ),
      'EE' => esc_html__( 'Estonia' ),
      'ET' => esc_html__( 'Ethiopia' ),
      'FK' => esc_html__( 'Falkland Islands' ),
      'FO' => esc_html__( 'Faroe Islands' ),
      'FJ' => esc_html__( 'Fiji' ),
      'FI' => esc_html__( 'Finland' ),
      'FR' => esc_html__( 'France' ),
      'GF' => esc_html__( 'French Guiana' ),
      'PF' => esc_html__( 'French Polynesia' ),
      'TF' => esc_html__( 'French Southern Territories' ),
      'GA' => esc_html__( 'Gabon' ),
      'GM' => esc_html__( 'Gambia' ),
      'GE' => esc_html__( 'Georgia' ),
      'DE' => esc_html__( 'Germany' ),
      'GR' => esc_html__( 'Greece' ),
      'GH' => esc_html__( 'Ghana' ),
      'GI' => esc_html__( 'Gibraltar' ),
      'GL' => esc_html__( 'Greenland' ),
      'GD' => esc_html__( 'Grenada' ),
      'GP' => esc_html__( 'Guadeloupe' ),
      'GU' => esc_html__( 'Guam' ),
      'GT' => esc_html__( 'Guatemala' ),
      'GG' => esc_html__( 'Guernsey' ),
      'GN' => esc_html__( 'Guinea' ),
      'GW' => esc_html__( 'Guinea-Bissau' ),
      'GY' => esc_html__( 'Guyana' ),
      'HT' => esc_html__( 'Haiti' ),
      'HM' => esc_html__( 'Heard and McDonald Islands' ),
      'VA' => esc_html__( 'Holy See (City Vatican State)' ),
      'HN' => esc_html__( 'Honduras' ),
      'HK' => esc_html__( 'Hong Kong' ),
      'HU' => esc_html__( 'Hungary' ),
      'IS' => esc_html__( 'Iceland' ),
      'IN' => esc_html__( 'India' ),
      'ID' => esc_html__( 'Indonesia' ),
      'IR' => esc_html__( 'Iran' ),
      'IQ' => esc_html__( 'Iraq' ),
      'IE' => esc_html__( 'Ireland' ),
      'IM' => esc_html__( 'Isle of Man' ),
      'IL' => esc_html__( 'Israel' ),
      'IT' => esc_html__( 'Italy' ),
      'JM' => esc_html__( 'Jamaica' ),
      'JP' => esc_html__( 'Japan' ),
      'JE' => esc_html__( 'Jersey' ),
      'JO' => esc_html__( 'Jordan' ),
      'KZ' => esc_html__( 'Kazakhstan' ),
      'KE' => esc_html__( 'Kenya' ),
      'KI' => esc_html__( 'Kiribati' ),
      'KW' => esc_html__( 'Kuwait' ),
      'KG' => esc_html__( 'Kyrgyzstan' ),
      'LA' => esc_html__( 'Lao People\'s Democratic Republic' ),
      'LV' => esc_html__( 'Latvia' ),
      'LB' => esc_html__( 'Lebanon' ),
      'LS' => esc_html__( 'Lesotho' ),
      'LR' => esc_html__( 'Liberia' ),
      'LY' => esc_html__( 'Libyan Arab Jamahiriya' ),
      'LI' => esc_html__( 'Liechtenstein' ),
      'LT' => esc_html__( 'Lithuania' ),
      'LU' => esc_html__( 'Luxembourg' ),
      'MO' => esc_html__( 'Macau' ),
      'MK' => esc_html__( 'Macedonia' ),
      'MG' => esc_html__( 'Madagascar' ),
      'MW' => esc_html__( 'Malawi' ),
      'MY' => esc_html__( 'Malaysia' ),
      'MV' => esc_html__( 'Maldives' ),
      'ML' => esc_html__( 'Mali' ),
      'MT' => esc_html__( 'Malta' ),
      'MH' => esc_html__( 'Marshall Islands' ),
      'MQ' => esc_html__( 'Martinique' ),
      'MR' => esc_html__( 'Mauritania' ),
      'MU' => esc_html__( 'Mauritius' ),
      'YT' => esc_html__( 'Mayotte' ),
      'MX' => esc_html__( 'Mexico' ),
      'FM' => esc_html__( 'Micronesia' ),
      'MD' => esc_html__( 'Moldova, Republic of' ),
      'MC' => esc_html__( 'Monaco' ),
      'MN' => esc_html__( 'Mongolia' ),
      'ME' => esc_html__( 'Montenegro' ),
      'MS' => esc_html__( 'Montserrat' ),
      'MA' => esc_html__( 'Morocco' ),
      'MZ' => esc_html__( 'Mozambique' ),
      'MM' => esc_html__( 'Myanmar' ),
      'NA' => esc_html__( 'Namibia' ),
      'NR' => esc_html__( 'Nauru' ),
      'NP' => esc_html__( 'Nepal' ),
      'NL' => esc_html__( 'Netherlands' ),
      'AN' => esc_html__( 'Netherlands Antilles' ),
      'NC' => esc_html__( 'New Caledonia' ),
      'NZ' => esc_html__( 'New Zealand' ),
      'NI' => esc_html__( 'Nicaragua' ),
      'NE' => esc_html__( 'Niger' ),
      'NG' => esc_html__( 'Nigeria' ),
      'NU' => esc_html__( 'Niue' ),
      'NF' => esc_html__( 'Norfolk Island' ),
      'KR' => esc_html__( 'North Korea' ),
      'MP' => esc_html__( 'Northern Mariana Islands' ),
      'NO' => esc_html__( 'Norway' ),
      'OM' => esc_html__( 'Oman' ),
      'PK' => esc_html__( 'Pakistan' ),
      'PW' => esc_html__( 'Palau' ),
      'PS' => esc_html__( 'Palestinian Territories' ),
      'PA' => esc_html__( 'Panama' ),
      'PG' => esc_html__( 'Papua New Guinea' ),
      'PY' => esc_html__( 'Paraguay' ),
      'PE' => esc_html__( 'Peru' ),
      'PH' => esc_html__( 'Phillipines' ),
      'PN' => esc_html__( 'Pitcairn Island' ),
      'PL' => esc_html__( 'Poland' ),
      'PT' => esc_html__( 'Portugal' ),
      'PR' => esc_html__( 'Puerto Rico' ),
      'QA' => esc_html__( 'Qatar' ),
      'XK' => esc_html__( 'Republic of Kosovo' ),
      'RE' => esc_html__( 'Reunion Island' ),
      'RO' => esc_html__( 'Romania' ),
      'RU' => esc_html__( 'Russian Federation' ),
      'RW' => esc_html__( 'Rwanda' ),
      'BL' => esc_html__( 'Saint Barth&eacute;lemy' ),
      'SH' => esc_html__( 'Saint Helena' ),
      'KN' => esc_html__( 'Saint Kitts and Nevis' ),
      'LC' => esc_html__( 'Saint Lucia' ),
      'MF' => esc_html__( 'Saint Martin (French)' ),
      'SX' => esc_html__( 'Saint Martin (Dutch)' ),
      'PM' => esc_html__( 'Saint Pierre and Miquelon' ),
      'VC' => esc_html__( 'Saint Vincent and the Grenadines' ),
      'SM' => esc_html__( 'San Marino' ),
      'ST' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe' ),
      'SA' => esc_html__( 'Saudi Arabia' ),
      'SN' => esc_html__( 'Senegal' ),
      'RS' => esc_html__( 'Serbia' ),
      'SC' => esc_html__( 'Seychelles' ),
      'SL' => esc_html__( 'Sierra Leone' ),
      'SG' => esc_html__( 'Singapore' ),
      'SK' => esc_html__( 'Slovak Republic' ),
      'SI' => esc_html__( 'Slovenia' ),
      'SB' => esc_html__( 'Solomon Islands' ),
      'SO' => esc_html__( 'Somalia' ),
      'ZA' => esc_html__( 'South Africa' ),
      'GS' => esc_html__( 'South Georgia' ),
      'KP' => esc_html__( 'South Korea' ),
      'SS' => esc_html__( 'South Sudan' ),
      'ES' => esc_html__( 'Spain' ),
      'LK' => esc_html__( 'Sri Lanka' ),
      'SD' => esc_html__( 'Sudan' ),
      'SR' => esc_html__( 'Suriname' ),
      'SJ' => esc_html__( 'Svalbard and Jan Mayen Islands' ),
      'SZ' => esc_html__( 'Swaziland' ),
      'SE' => esc_html__( 'Sweden' ),
      'CH' => esc_html__( 'Switzerland' ),
      'SY' => esc_html__( 'Syrian Arab Republic' ),
      'TW' => esc_html__( 'Taiwan' ),
      'TJ' => esc_html__( 'Tajikistan' ),
      'TZ' => esc_html__( 'Tanzania' ),
      'TH' => esc_html__( 'Thailand' ),
      'TL' => esc_html__( 'Timor-Leste' ),
      'TG' => esc_html__( 'Togo' ),
      'TK' => esc_html__( 'Tokelau' ),
      'TO' => esc_html__( 'Tonga' ),
      'TT' => esc_html__( 'Trinidad and Tobago' ),
      'TN' => esc_html__( 'Tunisia' ),
      'TR' => esc_html__( 'Turkey' ),
      'TM' => esc_html__( 'Turkmenistan' ),
      'TC' => esc_html__( 'Turks and Caicos Islands' ),
      'TV' => esc_html__( 'Tuvalu' ),
      'UG' => esc_html__( 'Uganda' ),
      'UA' => esc_html__( 'Ukraine' ),
      'AE' => esc_html__( 'United Arab Emirates' ),
      'UY' => esc_html__( 'Uruguay' ),
      'UM' => esc_html__( 'US Minor Outlying Islands' ),
      'UZ' => esc_html__( 'Uzbekistan' ),
      'VU' => esc_html__( 'Vanuatu' ),
      'VE' => esc_html__( 'Venezuela' ),
      'VN' => esc_html__( 'Vietnam' ),
      'VG' => esc_html__( 'Virgin Islands (British)' ),
      'VI' => esc_html__( 'Virgin Islands (USA)' ),
      'WF' => esc_html__( 'Wallis and Futuna Islands' ),
      'EH' => esc_html__( 'Western Sahara' ),
      'WS' => esc_html__( 'Western Samoa' ),
      'YE' => esc_html__( 'Yemen' ),
      'ZM' => esc_html__( 'Zambia' ),
      'ZW' => esc_html__( 'Zimbabwe' )
    );
  }

  /**
   * Render options in the Country dropdown.
   *
   * @since     2.1.14
   */
  private function add_countries() {
    $countries = $this->get_countries();
    $advanced = get_option( 'book_review_advanced' );
    $selected_country = isset( $advanced['book_review_country'] ) ? $advanced['book_review_country'] : '';

    foreach ( $countries as $country_code => $country ) {
      echo '<option value="' . esc_attr( $country_code ) . '"' . selected( $country_code, $selected_country, false ) . '>'
        . $country
        . '</option>';
    }
  }
}
?>