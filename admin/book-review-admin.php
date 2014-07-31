<?php
/**
 * Book Review
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <donnapep@gmail.com>
 * @license   GPL-2.0+
 * @link      http://donnapeplinskie.com
 * @copyright 2014 Donna Peplinskie
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `book-review.php`
 *
 * @package Book_Review_Admin
 * @author  Donna Peplinskie <donnapep@gmail.com>
 */

class Book_Review_Admin {
  /**
   * Instance of this class.
   *
   * @since    2.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Slug of the plugin screen.
   *
   * @since    2.0.0
   *
   * @var      string
   */
  protected $plugin_screen_hook_suffix = null;

  /**
   * Initialize the plugin by loading admin scripts & styles and adding a
   * settings page and menu.
   *
   * @since     1.0.0
   */
  private function __construct() {
    /*
     * Call $plugin_slug from public plugin class.
     */
    $plugin = Book_Review::get_instance();
    $this->plugin_slug = $plugin->get_plugin_slug();

    // Load admin style sheet and JavaScript.
    add_action( 'admin_enqueue_scripts', array( $this,
      'enqueue_admin_styles' ) );
    add_action( 'admin_enqueue_scripts', array( $this,
      'enqueue_admin_scripts' ) );

    // Add the options page and menu item.
    add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

    // Add an action link pointing to the options page.
    $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) .
      $this->plugin_slug . '.php' );
    add_filter( 'plugin_action_links_' . $plugin_basename,
      array( $this, 'add_action_links' ) );

    /*
     * Define custom functionality.
     *
     * Read more about actions and filters:
     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */
    add_action( 'admin_init', array( $this, 'init_menu' ) );
    add_action( 'load-post.php', array( $this, 'meta_box_setup' ) );
    add_action( 'load-post-new.php', array( $this, 'meta_box_setup' ) );
    add_action( 'manage_posts_custom_column', array( $this, 'column_content' ),
      10, 2 );
    add_action( 'wp_ajax_get_book_info', array( $this, 'get_book_info' ) );

    add_filter( 'manage_posts_columns', array( $this, 'column_heading' ) );
  }

  /**
   * Return an instance of this class.
   *
   * @since     2.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Register and enqueue admin-specific style sheet.
   *
   * @since     2.0.0
   *
   * @param    string    $hook_suffix    Page hook.
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_styles( $hook_suffix ) {
    if ( !isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    if ( $hook_suffix == 'edit.php' ) {
      $screen = get_current_screen();

      // Check that this is the posts admin page.
      if ( $screen->post_type == 'post' ) {
        wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url(
          'assets/css/posts-admin.css', __FILE__ ), array(),
          Book_Review::VERSION );
      }
    }
    else if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
      wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url(
        'assets/css/meta-box.css', __FILE__ ), array(), Book_Review::VERSION );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url(
        'assets/css/admin.css', __FILE__ ), array(), Book_Review::VERSION );
    }
  }

  /**
   * Register and enqueue admin-specific JavaScript.
   *
   * @since     2.0.0
   *
   * @param    string    $hook_suffix    Page hook.
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_scripts( $hook_suffix ) {
    if ( !isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
      wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url(
        'assets/js/meta-box.js', __FILE__ ), array( 'jquery' ),
        Book_Review::VERSION );
      wp_enqueue_script( 'jquery-ui-spinner' );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
      wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url(
        'assets/js/admin.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ),
        Book_Review::VERSION );
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
      __( 'Book Review Settings', $this->plugin_slug ),
      __( 'Book Review', $this->plugin_slug ),
      'manage_options',
      $this->plugin_slug,
      array( $this, 'display_plugin_admin_page' )
    );
  }

  /**
   * Render the settings page for this plugin.
   *
   * @since    2.0.0
   */
  public function display_plugin_admin_page() {
    // General
    $general_defaults = array(
      'book_review_box_position' => 'top',
      'book_review_date_format' => 'none',
    );
    $general = get_option( 'book_review_general' );
    $general = wp_parse_args( $general, $general_defaults );

    // Rating Images
    $ratings_defaults = array(
      'book_review_rating_default' => 1
    );
    $ratings = get_option( 'book_review_ratings' );
    $ratings = wp_parse_args( $ratings, $ratings_defaults );

    // Links
    $links = get_option( 'book_review_links' );

    // Advanced
    $advanced = get_option( 'book_review_advanced' );

    // Tooltip
    $tooltip = '<img src="' . plugins_url( 'assets/images/tooltip.gif',
      __FILE__ ) . '" />';

    include_once( 'views/admin.php' );
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
          $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) .
        '</a>'
      ), $links);
  }

  /**
   * Register settings so that they will be saved.
   *
   * @since    1.0.0
   */
  public function init_menu() {
    register_setting( 'book_review_options', 'book_review_general' );
    register_setting( 'book_review_options', 'book_review_ratings',
      array( $this, 'validate_rating_images' ) );
    register_setting( 'book_review_options', 'book_review_links',
      array( $this, 'validate_links' ) );
    register_setting( 'book_review_options', 'book_review_advanced' );
  }

  /**
   * Validate rating image URLs.
   *
   * @since     1.0.0
   */
  public function validate_rating_images( $input ) {
    $output = array();
    $image_error = false;

    $output['book_review_rating_home'] =
      isset( $input['book_review_rating_home'] )
      ? $input['book_review_rating_home'] : '';
    $output['book_review_rating_default'] =
      isset( $input['book_review_rating_default'] )
      ? $input['book_review_rating_default'] : '';

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
          rating images. Please ensure you enter a URL for each rating.',
        'error'
      );
    }

    return $output;
  }

  /**
   * Validate link image URLs.
   *
   * @since     1.0.0
   */
  public function validate_links( $input ) {
    $output = array();
    $link_error = false;

    $output['book_review_num_links'] = $input['book_review_num_links'];
    $output['book_review_link_target'] = $input['book_review_link_target'];

    for ( $i = 1; $i <= ( int )$input['book_review_num_links']; $i++ ) {
      $text = trim( $input['book_review_link_text' . $i] );
      $output['book_review_link_image' . $i] =
        esc_url_raw( trim( $input['book_review_link_image' . $i] ) );

      if ( empty( $text ) ) {
        $link_error = true;
      }
      else {
        $output['book_review_link_text' . $i] = sanitize_text_field( $text );
      }
    }

    if ( $link_error ) {
      add_settings_error(
        'book_review_links',
        'link-error',
        'Link Text is a required field. Please ensure you either enter text for
          each link or decrease the number of links you want to show.',
        'error'
      );
    }

    return $output;
  }

  /**
   * Render options in the Release Date Format dropdown.
   *
   * @since     2.0.0
   */
  public function render_date_format_field() {
    $formats = array(
      'none' => __( 'None', $this->plugin_slug ),
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
   * Render options in the Number of Links dropdown.
   *
   * @since     1.0.0
   */
  public function render_num_links_field() {
    $options = get_option( 'book_review_links' );
    $items = array(
      '0' => 'None',
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5'
    );

    foreach( $items as $type => $item ) {
      $selected = ( $options['book_review_num_links'] == $type ) ?
        'selected="selected"' : '';
      echo '<option value="' . $type . '" '. $selected . '>' . $item .
        '</option>';
    }
  }

  /**
   * Meta box setup function.
   *
   * NOTE:     Actions are points in the execution of a page or process
   *           lifecycle that WordPress fires.
   *
   *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
   *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
   *
   * @since    2.0.0
   */
  public function meta_box_setup() {
    add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    add_action( 'save_post', array( $this, 'save_meta_box' ) );
  }

  /**
   * Add the meta box container to posts and custom post types.
   *
   * @since    1.0.0
   */
  public function add_meta_box() {
    $post_types = get_post_types();

    foreach ( $post_types as $post_type ) {
      if ( ( $post_type != 'page' ) && ( $post_type != 'attachment' ) &&
        ( $post_type != 'revision' ) && ( $post_type != 'nav_menu_item' ) ) {
        add_meta_box(
          'book-review-meta-box',
          __( 'Book Info', $this->plugin_slug ),
          array( $this, 'render_meta_box' ),
          $post_type,
          'normal',
          'high'
        );
      }
    }
  }

  /**
   * Display meta box.
   *
   * @since    1.0.0
   *
   * @param    object    $post    Object for the current post.
   */
  public function render_meta_box( $post ) {
    // Get saved values from the database.
    $values = get_post_custom( $post->ID ) ;
    $ratings = get_option( 'book_review_ratings' );
    $advanced = get_option( 'book_review_advanced' );

    // Set the value for each key in $values.
    foreach ( array( 'book_review_isbn', 'book_review_title',
      'book_review_series', 'book_review_author', 'book_review_genre',
      'book_review_publisher', 'book_review_release_date', 'book_review_format',
      'book_review_pages', 'book_review_source', 'book_review_cover_url',
      'book_review_summary', 'book_review_rating', ) as $var ) {
      $$var = isset( $values[$var][0] ) ? $values[$var][0] : '';
    }

    $book_review_cover_url = esc_url ( $book_review_cover_url );
    $book_review_archive_post = isset( $values['book_review_archive_post'][0] )
      ? $values['book_review_archive_post'][0] : '1';

    $api_key = isset( $advanced['book_review_api_key'] ) ?
      $advanced['book_review_api_key'] : '';
    $args = array(
      'textarea_rows' => 15,
      'media_buttons' => false
    );

    // Show IBSN and Get Book Info button if Google API Key has been entered.
    if ( $api_key == '' ) {
      $show_isbn = 'display: none;';
    }
    else {
      $show_isbn = 'display: block;';
    }

    // Show an image preview if applicable.
    if ( $book_review_cover_url == '' ) {
      $show_cover = 'display: none;';
    }
    else {
      $show_cover = 'display: block;';
    }

    // Default rating image.
    if ( $ratings['book_review_rating_default'] == '1' ) {
      if ( $book_review_rating == '1' ) {
        $src = plugins_url( 'assets/one-star.png', dirname( __FILE__ ) );
      }
      else if ( $book_review_rating == '2' ) {
        $src = plugins_url( 'assets/two-star.png', dirname( __FILE__ ) );
      }
      else if ( $book_review_rating == '3' ) {
        $src = plugins_url( 'assets/three-star.png', dirname( __FILE__ ) );
      }
      else if ( $book_review_rating == '4' ) {
        $src = plugins_url( 'assets/four-star.png', dirname( __FILE__ ) );
      }
      else if ( $book_review_rating == '5' ) {
        $src = plugins_url( 'assets/five-star.png', dirname( __FILE__ ) );
      }
      else {
        $src = '';
      }
    }
    // Custom rating image.
    else {
      if ( $book_review_rating == '1' ) {
        $src = $ratings['book_review_rating_image1'];
      }
      else if ( $book_review_rating == '2' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else if ( $book_review_rating == '3' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else if ( $book_review_rating == '4' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else if ( $book_review_rating == '5' ) {
        $src = $ratings['book_review_rating_image2'];
      }
      else {
        $src = '';
      }
    }

    // Show the rating image.
    if ( empty( $src ) ) {
      $show_rating_image = 'display: none;';
    }
    else {
      $show_rating_image = 'display: block;';
    }

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'save_meta_box_nonce', 'book-review-meta-box-nonce' );

    include_once( 'views/meta-box.php' );
  }

  /**
   * Save meta box information.
   *
   * @since    1.0.0
   *
   * @param    object    $post_id    Object for the current post.
   */
  public function save_meta_box( $post_id ) {
    // Bail if we're doing an auto save.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    // If our nonce isn't there, or we can't verify it, bail.
    if ( !isset( $_POST['book-review-meta-box-nonce'] )
      || !wp_verify_nonce( $_POST['book-review-meta-box-nonce'],
        'save_meta_box_nonce' ) )
      return;

    // If our current user can't edit this post, bail.
    if ( !current_user_can( 'edit_post' ) )
      return;

    if ( isset( $_POST['book_review_isbn'] ) )
      update_post_meta( $post_id, 'book_review_isbn',
        sanitize_text_field( $_POST['book_review_isbn'] ) );

    // Get the posted data and sanitize it.
    if ( isset( $_POST['book_review_title'] ) ) {
      update_post_meta( $post_id, 'book_review_title',
        sanitize_text_field( $_POST['book_review_title'] ) );
      update_post_meta( $post_id, 'book_review_archive_title',
        $this->get_archive_title() );
    }

    if ( isset( $_POST['book_review_series'] ) )
      update_post_meta( $post_id, 'book_review_series',
        sanitize_text_field( $_POST['book_review_series'] ) );

    if ( isset( $_POST['book_review_author'] ) )
      update_post_meta( $post_id, 'book_review_author',
        sanitize_text_field( $_POST['book_review_author'] ) );

    if ( isset( $_POST['book_review_genre'] ) )
      update_post_meta( $post_id, 'book_review_genre',
        sanitize_text_field( $_POST['book_review_genre'] ) );

    if ( isset( $_POST['book_review_publisher'] ) )
      update_post_meta( $post_id, 'book_review_publisher',
        sanitize_text_field( $_POST['book_review_publisher'] ) );

    if ( isset( $_POST['book_review_release_date'] ) )
      update_post_meta( $post_id, 'book_review_release_date',
        sanitize_text_field( $_POST['book_review_release_date'] ) );

    if ( isset( $_POST['book_review_format'] ) )
      update_post_meta( $post_id, 'book_review_format',
        sanitize_text_field( $_POST['book_review_format'] ) );

    if ( isset( $_POST['book_review_pages'] ) )
      update_post_meta( $post_id, 'book_review_pages',
        sanitize_text_field( $_POST['book_review_pages'] ) );

    if ( isset( $_POST['book_review_source'] ) )
      update_post_meta( $post_id, 'book_review_source',
        sanitize_text_field( $_POST['book_review_source'] ) );

    if ( isset( $_POST['book_review_link1'] ) )
      update_post_meta( $post_id, 'book_review_link1',
        esc_url_raw( $_POST['book_review_link1'] ) );

    if ( isset( $_POST['book_review_link2'] ) )
      update_post_meta( $post_id, 'book_review_link2',
        esc_url_raw( $_POST['book_review_link2'] ) );

    if ( isset( $_POST['book_review_link3'] ) )
      update_post_meta( $post_id, 'book_review_link3',
        esc_url_raw( $_POST['book_review_link3'] ) );

    if ( isset( $_POST['book_review_link4'] ) )
      update_post_meta( $post_id, 'book_review_link4',
        esc_url_raw( $_POST['book_review_link4'] ) );

    if ( isset( $_POST['book_review_link5'] ) )
      update_post_meta( $post_id, 'book_review_link5',
        esc_url_raw( $_POST['book_review_link5'] ) );

    if ( isset( $_POST['book_review_cover_url'] ) )
      update_post_meta( $post_id, 'book_review_cover_url',
        esc_url_raw( $_POST['book_review_cover_url'] ) );

    if ( isset( $_POST['book_review_summary'] ) )
      update_post_meta( $post_id, 'book_review_summary',
        $_POST['book_review_summary'] );

    if ( isset( $_POST['book_review_rating'] ) )
      update_post_meta( $post_id, 'book_review_rating',
       $_POST['book_review_rating'] );

    update_post_meta( $post_id, 'book_review_archive_post',
      $_POST['book_review_archive_post'] );
  }

  /**
   * Display link fields in meta box.
   *
   * @since    2.0.0
   */
  public function render_links() {
    $values = get_post_custom( $post->ID );
    $links = get_option( 'book_review_links' );
    $num_links = $links['book_review_num_links'];

    // Generate the Link Image URLs.
    $link_urls = array();

    for ( $i = 1; $i <= 5; $i++ ) {
      $link_urls[$i] = isset( $values['book_review_link' . $i] ) ?
        esc_url( $values['book_review_link' . $i][0] ) : '';
    }

    // Render links outside of PHP code, otherwise they will be slightly
    // misaligned.
    for ( $i = 1; $i <= 5; $i++ ) {
      if ( isset( $links['book_review_link_text' . $i] )
        && ( $num_links >= $i ) ) { ?>
        <label for="<?php echo 'book_review_link' . $i; ?>">
          <?php echo $links['book_review_link_text' . $i]; ?> URL:
        </label>
        <input type="text" id="<?php echo 'book_review_link' . $i; ?>"
          name="<?php echo 'book_review_link' . $i; ?>"
          value="<?php echo $link_urls[$i]; ?>" />
        <br />
      <?php }
    }
  }

  /**
   * Display rating dropdown in meta box.
   *
   * @since    2.0.0
   */
  public function render_rating( $rating ) {
    // Show the Rating dropdown.
    $items = array(
      '-1' => __( 'Select...', 'book-review' ),
      '1' => '1',
      '2' => '2',
      '3' => '3',
      '4' => '4',
      '5' => '5'
    );

    foreach ( $items as $type => $item ) {
      $selected = ( $rating == $type ) ? 'selected="selected"' : '';
      echo '<option value="' . $type . '" ' . $selected . '>' . $item .
        '</option>';
    }
  }

  /**
   * Get book details from the Google Books API.
   *
   * @since    2.0.0
   */
  public function get_book_info() {
    if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax_isbn_nonce' ) ) {
      $options = get_option( 'book_review_general' );
      $advanced = get_option( 'book_review_advanced' );
      $api_key = $advanced['book_review_api_key'];

      if ( isset( $api_key ) && !empty( $api_key ) ) {
        $url = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' .
          $_POST['isbn'] . '&key=' . $api_key;
        $response = wp_remote_get( $url );

        try {
          if ( $response['response']['code'] == 200 ) {
            $date_format = $options['book_review_date_format'];
            $body = $response['body'];
            $result['status'] = 'success';
            $result['data'] = $body;
            $result['format'] = $date_format;
          }
          else {
            $result['status'] = 'error';
            $result['data'] = $response['response']['code'] . ' ' .
              $response['response']['message']
              . ' to ' . $url . ' ' . $api_key;
          }
        }
        catch ( Exception $ex ) {
          $result['status'] = 'error';
          $result['data'] = 'Exception: ' . $ex;
        }
      }
      else {
        $result['status'] = 'error';
        $result['data'] = 'No API key';
      }
    }
    else {
      $result['status'] = 'error';
      $result['data'] = 'Invalid nonce' ;
    }

    $result = json_encode( $result );

    echo $result;
    die();
  }

  /**
   * Move common stopwords to end of Title.
   *
   * @since    1.0.0
   */
  private function get_archive_title() {
    $title = trim( $_POST['book_review_title'] );
    $stopwords = array( __( 'the', 'book-review' ), __( 'a', 'book-review' ),
      __( 'an', 'book-review' ) );
    /* Translations may specify multiple stopwords for each English word.
       Separate them into a comma-delimited list in order to avoid a
       multi-dimensional array. */
    $stopwords = implode( ',', $stopwords );
    // Now put them back into a one-dimensional array.
    $stopwords = explode( ',', $stopwords );

    foreach ( $stopwords as $stopword ) {
      $stopword = trim( $stopword );

      /* Check if first characters of the title is a stop word. Add a space at
         the end of the stopword so that only full words are matched. */
      $substring = substr( $title, 0, strlen( $stopword ) + 1 );

      // Move stopword to the end if a match is found.
      if ( strtolower( $substring ) == ( $stopword . ' ' ) ) {
        return sanitize_text_field( substr( $title, strlen( $stopword ) + 1 ) .
          ', ' . $substring );
      }
    }

    return sanitize_text_field( $_POST['book_review_title'] );
  }

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
    return array_merge( $columns, array( 'rating' => __( 'Rating' ) ) );
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
          echo '<img src="' . $plugin->get_rating_image( $rating ) .
            '" class="book_review_column_rating" />';
        }
      }
    }
  }
}
?>