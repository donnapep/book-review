<?php

/**
 * Defines functionality for serializing the options saved in the meta box.
 *
 * @link       http://wpreviewplugins.com/
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
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
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
   * @param    Book_Review_Settings   $settings     Instance of Book_Review_Settings for
   *                                                  getting the settings.
   * @param    Book_Review_Book_Info  $book_info    Instance of Book_Review_Book_Info for
   *                                                  getting information about a book.
   */
  public function __construct( $plugin_name, $settings, $book_info ) {
    global $wpdb;

    $this->plugin_name = $plugin_name;
    $this->settings = $settings;
    $this->book_info = $book_info;

    $wpdb->book_review_custom_link_urls = "{$wpdb->prefix}book_review_custom_link_urls";
  }

  /**
   * Meta box setup function.
   *
   * @since    2.0.0
   */
  public function meta_box_setup() {
    add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
    add_action( 'save_post', array( $this, 'save_meta_box' ) );
  }

  /**
   * Add the meta box container.
   *
   * @since    1.0.0
   */
  public function add_meta_box( $post_type ) {
    $general_option = $this->settings->get_book_review_general_option();

    foreach ( $general_option['book_review_post_types'] as $key => $value ) {
      if ( ( $post_type == $key ) && ( $value == '1' ) ) {
        add_meta_box(
          'book-review-meta-box',
          esc_html__( 'Book Info', $this->plugin_name ),
          array( $this, 'display_meta_box' ),
          $post_type,
          'normal',
          'high'
        );

        do_action( 'book_review_meta_box', $post_type );

        break;
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
  public function display_meta_box( $post ) {
    wp_nonce_field( 'save_meta_box', 'book_review_meta_box_nonce' );
    include_once( 'partials/book-review-admin-meta-box.php' );
  }

  /**
   * Retrieve the CSS classes for the ISBN.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_isbn_class() {
    $class = 'row';
    $advanced_option = $this->settings->get_book_review_advanced_option();

    if ( empty( $advanced_option['book_review_api_key'] ) ) {
      $class .= ' hide';
    }
    else {
      $class .= ' show';
    }

    return $class;
  }

  /**
   * Retrieve the CSS classes for the cover.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_cover_url_class( $post_id ) {
    $class = 'cover-image';
    $url = $this->book_info->get_book_review_cover_url( $post_id );

    if ( empty( $url ) ) {
      $class .= ' hide';
    }
    else {
      $class .= ' show';
    }

    return $class;
  }

  /**
   * Retrieve the CSS classes for the rating image.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_rating_image_class( $post_id ) {
    $url = $this->book_info->get_book_review_rating_image( $post_id );
    $class = 'rating-image';

    if ( empty( $url ) ) {
      $class .= ' hide';
    }
    else {
      $class .= ' show';
    }

    return $class;
  }

  /**
   * Display rating dropdown.
   *
   * @since    2.0.0
   */
  public function display_rating( $post_id ) {
    $rating = $this->book_info->get_book_review_rating( $post_id );
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

      echo '<option value="' . esc_attr( $type ) . '" ' . $selected . '>' . esc_html( $item ) . '</option>';
    }
  }

  /**
   * Get book details from the Google Books API.
   *
   * @since    2.0.0
   */
  public function get_book_info() {
    if ( wp_verify_nonce( $_REQUEST['nonce'], 'ajax_isbn_nonce' ) ) {
      $advanced_option = $this->settings->get_book_review_advanced_option();
      $api_key = $advanced_option['book_review_api_key'];

      // Don't make a request to the API if there is no API key or ISBN.
      if ( !empty( $api_key ) && !empty( $_POST['isbn'] ) && ( strlen( trim( $_POST['isbn'] ) ) > 0 ) ) {
        $url = $this->get_api_url( $_POST['isbn'], $api_key, $advanced_option['book_review_country'] );
        $response = wp_remote_get( esc_url_raw( $url ) );

        try {
          if ( is_wp_error( $response ) ) {
            $result['status'] = 'error';
            $result['data'] = $response->get_error_message();
          }
          else if ( $response['response']['code'] == 200 ) {
            $json = json_decode( $response['body'] );

            // Format the Release Date.
            if ( isset( $json->items[0]->volumeInfo->publishedDate ) ) {
              $published_date = $json->items[0]->volumeInfo->publishedDate;
              $obj_date = DateTime::createFromFormat( 'Y-m-d', $published_date );

              // Check that date is in the expected Y-m-d format.
              if ( $obj_date != false ) {
                // Format the date as per the WordPress "Date Format" setting.
                $published_date = $obj_date->format( get_option( 'date_format' ) );
              }

              // Add the date to the result.
              $result['releaseDate'] = $published_date;
            }

            $body = $response['body'];
            $result['status'] = 'success';
            $result['data'] = $body;
          }
          else {
            $result['status'] = 'error';
            $result['data'] = $response['response']['code'] . ' ' . $response['response']['message']
              . ' to ' . esc_url( $url );
          }
        }
        catch ( Exception $ex ) {
          $result['status'] = 'error';
          $result['data'] = 'Exception: ' . $ex;
        }
      }
      else {
        $result['status'] = 'error';
        $result['data'] = 'No API key or empty ISBN';
      }
    }
    else {
      $result['status'] = 'error';
      $result['data'] = 'Invalid nonce';
    }

    $result = json_encode( $result );

    echo $result;
    wp_die();
  }

   /**
   * Get the Google Books API request URL.
   *
   * @since    2.1.14
   */
  private function get_api_url( $isbn, $api_key, $country ) {
    $url = 'https://www.googleapis.com/books/v1/volumes?q=isbn:' . sanitize_text_field( $isbn ) .
      '&key=' . sanitize_text_field( $api_key );

    if ( !empty( $country ) ) {
      $url = add_query_arg( 'country', $country, $url );
    }

    return $url;
  }

  /**
   * Save meta box information.
   *
   * @since    1.0.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function save_meta_box( $post_id ) {
    global $wpdb;

    if ( $this->user_can_save( $post_id, 'book_review_meta_box_nonce', 'save_meta_box' ) ) {
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
      $this->save_summary( $post_id, 'book_review_summary', $_POST['book_review_summary']);
      $this->save_rating( $post_id, 'book_review_rating', $_POST['book_review_rating']);

      // Include post in archives.
      if ( isset( $_POST['book_review_archive_post'] ) && ( $_POST['book_review_archive_post'] === '1' ) ) {
        update_post_meta( $post_id, 'book_review_archive_post', $_POST['book_review_archive_post'] );
      }
      else {
        update_post_meta( $post_id, 'book_review_archive_post', '' );
      }

      // Save title used in archives.
      if ( strlen( trim( $_POST['book_review_title'] ) ) > 0 ) {
        update_post_meta( $post_id, 'book_review_archive_title', $this->get_archive_title() );
      }
      else {
        delete_post_meta( $post_id, 'book_review_archive_title' );
      }

      // Save custom fields.
      if ( isset( $_POST['book_review_fields'] ) ) {
        foreach ( $_POST['book_review_fields'] as $key => $value ) {
          $this->save_text_field( $post_id, $key, $value );
        }
      }

      // Save site links.
      if ( isset( $_POST['book_review_sites'] ) ) {
        foreach ( $_POST['book_review_sites'] as $key => $value ) {
          $this->save_url_field( $post_id, $key, $value );
        }
      }

      // For every entry in the custom_links table, save an entry to the custom_link_urls table.
      $sql = "SELECT custom_link_id FROM {$wpdb->book_review_custom_links} WHERE active = 1";
      $results = $wpdb->get_results( $sql );

      foreach ( $results as $result ) {
        $link = $_POST['book_review_custom_link' . $result->custom_link_id];

        // Add link URL.
        if ( isset( $link ) && strlen( trim( $link ) ) > 0 ) {
          $link = esc_url_raw( $link );
          $sql = "INSERT INTO {$wpdb->book_review_custom_link_urls} (post_id, custom_link_id, url)
            VALUES (%d, %d, %s) ON DUPLICATE KEY UPDATE url = %s";
          $sql = $wpdb->prepare( $sql, $post_id, $result->custom_link_id, $link, $link );

          $wpdb->query( $sql );
        }
        // Delete link URL if the field is empty.
        else {
          $wpdb->delete(
            $wpdb->book_review_custom_link_urls,
            array(
              'post_id' => $post_id,
              'custom_link_id' => $result->custom_link_id,
            ),
            array( '%d', '%d' )
          );
        }
      }
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
   * Sanitize and save string post meta.
   *
   * @since    2.1.8
   * @param    integer      $post_id      ID of the post
   * @param    string       $meta_key     Key of the custom field
   * @param    string       $meta_value   Value of the custom field
   */
  private function save_text_field( $post_id, $meta_key, $meta_value ) {
    if ( isset( $meta_value ) && ( strlen( trim( $meta_value ) ) > 0 ) ) {
      update_post_meta( $post_id, $meta_key, sanitize_text_field( $meta_value ) );
    }
    else {
      delete_post_meta( $post_id, $meta_key );
    }
  }

  /**
   * Sanitize and save URL post meta.
   *
   * @since    2.1.8
   * @param    integer      $post_id      ID of the post
   * @param    string       $meta_key     Key of the custom field
   * @param    string       $meta_value   Value of the custom field
   */
  private function save_url_field( $post_id, $meta_key, $meta_value ) {
    if ( isset( $meta_value ) && ( strlen( trim( $meta_value ) ) > 0 ) ) {
      update_post_meta( $post_id, $meta_key, esc_url_raw( $meta_value ) );
    }
    else {
      delete_post_meta( $post_id, $meta_key );
    }
  }

  /**
   * Sanitize and save rating in post meta.
   *
   * @since    2.1.8
   * @param    integer      $post_id      ID of the post
   * @param    string       $meta_key     Key of the custom field
   * @param    string       $meta_value   Value of the custom field
   */
  private function save_rating( $post_id, $meta_key, $meta_value ) {
    $allowed_ratings = array( '-1', '1', '2', '3', '4', '5' );

    if ( isset( $meta_value ) && in_array( $meta_value, $allowed_ratings ) ) {
      update_post_meta( $post_id, $meta_key, $meta_value );
    }
    else {
      delete_post_meta( $post_id, $meta_key );
    }
  }

  /**
   * Sanitize and save summary in post meta.
   *
   * @since    2.1.8
   * @param    integer      $post_id      ID of the post
   * @param    string       $meta_key     Key of the custom field
   * @param    string       $meta_value   Value of the custom field
   */
  private function save_summary( $post_id, $meta_key, $meta_value ) {
    if ( isset( $meta_value ) && ( strlen( trim( $meta_value ) ) > 0 ) ) {
      update_post_meta( $post_id, $meta_key, $meta_value );
    }
    else {
      delete_post_meta( $post_id, $meta_key );
    }
  }

  /**
   * Move common stopwords to end of Title.
   *
   * @since    1.0.0
   */
  private function get_archive_title() {
    $stopwords = array(
      esc_html__( 'the', $this->plugin_name ),
      esc_html__( 'a', $this->plugin_name ),
      esc_html__( 'an', $this->plugin_name ),
    );
    $title = sanitize_text_field( $_POST['book_review_title'] );

    // Translations may specify multiple stopwords for each English word. Separate them into a
    // comma-delimited list in order to avoid a multi-dimensional array.
    $stopwords = implode( ',', $stopwords );

    // Now put them back into a one-dimensional array.
    $stopwords = explode( ',', $stopwords );

    foreach ( $stopwords as $stopword ) {
      $stopword = trim( $stopword );

      // Check if first characters of the title is a stop word. Add a space at the end of the
      // stopword so that only full words are matched.
      $substring = substr( $title, 0, strlen( $stopword ) + 1 );

      // Move stopword to the end if a match is found.
      if ( strtolower( $substring ) == ( $stopword . ' ' ) ) {
        return trim( substr( $title, strlen( $stopword ) + 1 ) . ', ' . $substring );
      }
    }

    return $title;
  }
}