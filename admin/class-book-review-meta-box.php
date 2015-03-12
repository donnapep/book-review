<?php

/**
 * Defines functionality for serializing the options saved in the meta box.
 *
 * @link       http://donnapeplinskie.com
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 */

/**
 * Defines functionality for serializing the options saved in the meta box.
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 * @author     Donna Peplinskie <donnapep@gmail.com>
 */
class Book_Review_Meta_Box {
  /**
   * The ID of this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Initialize the class and set its properties.
   *
   * @since    2.1.8
   * @var      string    $plugin_name       The name of the plugin.
   */
  public function __construct( $plugin_name ) {
    global $wpdb;

    $this->plugin_name = $plugin_name;
    $wpdb->book_review_custom_link_urls = "{$wpdb->prefix}book_review_custom_link_urls";
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
          __( 'Book Info', $this->plugin_name ),
          array( $this, 'render_meta_box' ),
          $post_type,
          'normal',
          'high'
        );
      }
    }
  }

  /**
   * Add CSS classes to the meta box container.
   *
   * @since    2.1.6
   */
  public function add_metabox_class( $classes ) {
    array_push( $classes, 'book-review-meta' );

    return $classes;
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

    $book_review_cover_url = $book_review_cover_url;
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
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/one-star.png';
      }
      else if ( $book_review_rating == '2' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/two-star.png';
      }
      else if ( $book_review_rating == '3' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/three-star.png';
      }
      else if ( $book_review_rating == '4' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/four-star.png';
      }
      else if ( $book_review_rating == '5' ) {
        $src = plugin_dir_url( dirname(__FILE__) ) . 'includes/images/five-star.png';
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

    include_once( 'partials/book-review-admin-meta-box.php' );
  }

  /**
   * Save meta box information.
   *
   * @since    1.0.0
   *
   * @param    object    $post_id    Object for the current post.
   */
  public function save_meta_box( $post_id ) {
    global $wpdb;

    if ( $this->user_can_save( $post_id, 'book-review-meta-box-nonce', 'save_meta_box_nonce' ) ) {
      $this->save_text_field( $post_id, 'book_review_isbn', $_POST['book_review_isbn']);
      $this->save_text_field( $post_id, 'book_review_title', $_POST['book_review_title']);
      $this->save_text_field( $post_id, 'book_review_series', $_POST['book_review_series']);
      $this->save_text_field( $post_id, 'book_review_author', $_POST['book_review_author']);
      $this->save_text_field( $post_id, 'book_review_genre', $_POST['book_review_genre']);
      $this->save_text_field( $post_id, 'book_review_publisher', $_POST['book_review_publisher']);
      $this->save_text_field( $post_id, 'book_review_release_date', $_POST['book_review_release_date']);
      $this->save_text_field( $post_id, 'book_review_format', $_POST['book_review_format']);
      $this->save_text_field( $post_id, 'book_review_pages', $_POST['book_review_pages']);
      $this->save_text_field( $post_id, 'book_review_source', $_POST['book_review_source']);
      $this->save_url_field( $post_id, 'book_review_cover_url', $_POST['book_review_cover_url']);
      $this->save_field( $post_id, 'book_review_summary', $_POST['book_review_summary']);
      $this->save_field( $post_id, 'book_review_rating', $_POST['book_review_rating']);
      $this->save_field( $post_id, 'book_review_archive_post', $_POST['book_review_archive_post']);

      // Save title used in archives.
      if ( isset( $_POST['book_review_title'] ) ) {
        update_post_meta( $post_id, 'book_review_archive_title', $this->get_archive_title() );
      }
      else {
        delete_post_meta( $post_id, 'book_review_archive_title' );
      }

      // For every entry in the custom_links table, save an entry to the custom_link_urls table.
      $sql = "SELECT custom_link_id FROM {$wpdb->book_review_custom_links} WHERE active = 1";
      $results = $wpdb->get_results( $sql );

      foreach( $results as $result ) {
        $link = $_POST['book_review_custom_link' . $result->custom_link_id];

        if ( isset( $link ) && strlen( trim( $link ) ) > 0 ) {
          $sql = "INSERT INTO {$wpdb->book_review_custom_link_urls} (post_id, custom_link_id, url)
            VALUES (%d, %d, %s) ON DUPLICATE KEY UPDATE url = %s";
          $sql = $wpdb->prepare($sql, $post_id, $result->custom_link_id, $link, $link);

          $wpdb->query($sql);
        }
        // Delete link from table if the field is empty.
        else {
          $wpdb->delete(
             $wpdb->book_review_custom_link_urls,
            array(
              'post_id' => $post_id,
              'custom_link_id '=> $result->custom_link_id,
            ),
            array( '%d', '%d' )
          );
        }
      }
    }
  }

  private function save_text_field( $post_id, $name, $value ) {
    if ( isset( $value ) ) {
      update_post_meta( $post_id, $name, sanitize_text_field( $value ) );
    }
    else {
      delete_post_meta( $post_id, $name );
    }
  }

  private function save_url_field( $post_id, $name, $value ) {
    if ( isset( $value ) ) {
      update_post_meta( $post_id, $name, esc_url_raw( $value ) );
    }
    else {
      delete_post_meta( $post_id, $name );
    }
  }

  private function save_field( $post_id, $name, $value ) {
    if ( isset( $value ) ) {
      update_post_meta( $post_id, $name, $value );
    }
    else {
      delete_post_meta( $post_id, $name );
    }
  }

  /**
   * Verifies that the user who is currently logged in has permission to save the data
   * from the meta box to the database.
   *
   * @since    2.1.8
   * @param    integer    $post_id    The current post being saved.
   * @param    string     $nonce      The nonce used once to identify the serialization value.
   * @param    string     $action     The source of the action of the nonce being used.
   * @return   boolean                true if the user can save the information.
   */
  private function user_can_save( $post_id, $nonce, $action ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( $_POST[ $nonce ], $action ) );

    return ! ( $is_autosave || $is_revision ) && $is_valid_nonce;
  }

  /**
   * Display link fields in meta box.
   *
   * @since    2.0.0
   */
  public function render_links( $post ) {
    global $wpdb;

    // Get the link text and link URLs.
    $sql = "SELECT links.custom_link_id, links.text, urls.url
      FROM {$wpdb->book_review_custom_links} AS links
      LEFT OUTER JOIN {$wpdb->book_review_custom_link_urls} AS urls ON links.custom_link_id = urls.custom_link_id
        AND urls.post_id = $post->ID
        WHERE links.active = 1";

    $results = $wpdb->get_results( $sql );

    // Render links outside of PHP code, otherwise they will be slightly misaligned.
    foreach( $results as $result ) { ?>
      <div class="row">
        <label for="<?php echo 'book_review_custom_link' . $result->custom_link_id; ?>">
          <?php echo esc_html( $result->text ) . ' '; _e( 'URL', $this->plugin_name ); ?>:
        </label>
        <input type="text" id="<?php echo 'book_review_custom_link' . $result->custom_link_id; ?>"
          name="<?php echo 'book_review_custom_link' . $result->custom_link_id; ?>"
          value="<?php echo esc_url( $result->url ); ?>" />
      </div>
    <?php
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
      '-1' => __( 'Select...', $this->plugin_name ),
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
    $stopwords = array( __( 'the', $this->plugin_name ), __( 'a', $this->plugin_name ),
      __( 'an', $this->plugin_name ) );
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
}