<?php

/**
 * Register all actions, filters and shortcodes for the plugin.
 *
 * @link       http://wpreviewplugins.com/
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
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
 */
class Book_Review_Book_Info {
  /**
   * The unique identifier of this plugin.
   *
   * @since    1.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

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
   * Initialize the class and set its properties.
   *
   * @since     1.0.0
   *
   * @param     string    $plugin_name       The name of the plugin.
   */
  public function __construct( $plugin_name, $settings ) {
    $this->plugin_name = $plugin_name;
    $this->settings = $settings;
  }

  /**
   * Retrieve the ISBN.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_isbn( $post_id ) {
    $isbn = get_post_meta( $post_id, 'book_review_isbn', true );

    return apply_filters( 'book_review_isbn', $isbn, $post_id );
  }

  /**
   * Retrieve the title.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_title( $post_id ) {
    $title = get_post_meta( $post_id, 'book_review_title', true );

    return apply_filters( 'book_review_title', $title, $post_id );
  }

  /**
   * Retrieve the series.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_series( $post_id ) {
    $series = get_post_meta( $post_id, 'book_review_series', true );

    return apply_filters( 'book_review_series', $series, $post_id );
  }

  /**
   * Retrieve the author.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_author( $post_id ) {
    $author = get_post_meta( $post_id, 'book_review_author', true );

    return apply_filters( 'book_review_author', $author, $post_id );
  }

  /**
   * Retrieve the genre.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_genre( $post_id ) {
    $genre = get_post_meta( $post_id, 'book_review_genre', true );

    return apply_filters( 'book_review_genre', $genre, $post_id );
  }

  /**
   * Retrieve the publisher.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_publisher( $post_id ) {
    $publisher = get_post_meta( $post_id, 'book_review_publisher', true );

    return apply_filters( 'book_review_publisher', $publisher, $post_id );
  }

  /**
   * Retrieve the release date.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_release_date( $post_id ) {
    $release_date = get_post_meta( $post_id, 'book_review_release_date', true );

    return apply_filters( 'book_review_release_date', $release_date, $post_id );
  }

  /**
   * Retrieve the format.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_format( $post_id ) {
    $format = get_post_meta( $post_id, 'book_review_format', true );

    return apply_filters( 'book_review_format', $format, $post_id );
  }

  /**
   * Retrieve the pages.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_pages( $post_id ) {
    $pages = get_post_meta( $post_id, 'book_review_pages', true );

    return apply_filters( 'book_review_pages', $pages, $post_id );
  }

  /**
   * Retrieve the source.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_source( $post_id ) {
    $source = get_post_meta( $post_id, 'book_review_source', true );

    return apply_filters( 'book_review_source', $source, $post_id );
  }

  /**
   * Retrieve the custom field values.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post
   */
  public function get_book_review_field( $post_id, $field_id ) {
    $field = get_post_meta( $post_id, $field_id, true );

    return apply_filters( 'book_review_field', $field, $post_id, $field_id );
  }

  /**
   * Retrieve the site link.
   *
   * @since    2.3.4
   *
   * @param    string    $post_id    ID of the current post
   * @param    string    $site_id    ID of the current site
   */
  public function get_book_review_site_link( $post_id, $site_id ) {
    $site_link = get_post_meta( $post_id, $site_id, true );

    return apply_filters( 'book_review_site_link', $site_link, $post_id, $site_id );
  }

  /**
   * Retrieve the links.
   *
   * @since    2.3.4
   *
   * @param    string    $post_id    ID of the current post
   *
   * @return   array    Array containing the HTML for each site link
   */
  public function get_book_review_site_link_html( $post_id ) {
    $links_option = $this->settings->get_book_review_links_option();
    $html = array();

    // Iterate over all of the site links.
    foreach ( $links_option['sites'] as $site_id => $site_values ) {
      // Check if the site link is active.
      if ( $site_values['active'] === '1' ) {
        $link_url = $this->get_book_review_site_link( $post_id, $site_id );
        $type = $site_values['type'];
        $image_url = $site_values['url'];
        $target = $this->get_link_target();

        // Create the link.
        if ( !empty( $link_url ) ) {
          if ( $type === 'button' ) {
            array_push( $html, $this->get_button_site_link( $site_id, $site_values['text'], $link_url, $target ) );
          }
          else if ( $type === 'text' ) {
            array_push( $html, $this->get_text_site_link( $site_id, $site_values['text'], $link_url, $target ) );
          }
          else if ( $type == 'custom' ) {
            array_push( $html, $this->get_custom_site_link( $site_id, $site_values['text'], $link_url, $image_url, $target ) );
          }
        }
      }
    }

    return apply_filters( 'book_review_site_links', $html, $post_id, $links_option );
  }

  /**
   * Get the HTML for a site link button.
   *
   * @since    2.3.4
   *
   * @param    string    $site_id    ID of the current site
   * @param    string    $text       Site link text
   * @param    string    $url        Site link URL
   * @param    string    $target     Target attribute of the site link
   *
   * @return   string   HTML for the site link button
   */
  private function get_button_site_link( $site_id, $text, $url, $target ) {
    $anchor = '<a class="custom-link" href="'
              . esc_url( $url )
              . '"'
              . $target
              . '>';

    $img = '<img src="'
          . esc_url( $this->get_button_site_link_url ( $site_id ) )
          . '" alt="'
          . esc_attr( $text )
          . '">';

    return $anchor . $img . '</a>';
  }

  /**
   * Get the URL for a site link button.
   *
   * @since    2.3.4
   *
   * @param    string    $site_id    'book_review_goodreads'|'book_review_barnes_noble'
   *
   * @return   string   URL for the site link button
   */
  private function get_button_site_link_url( $site_id ) {
    $base_url = plugin_dir_url( __DIR__ ) . 'includes/images/';

    if ( $site_id === 'book_review_goodreads' ) {
      return $base_url . 'goodreads.png';
    }
    else if ( $site_id === 'book_review_barnes_noble' ) {
      return $base_url . 'barnes-noble.png';
    }
  }

  /**
   * Get the HTML for a plain text site link.
   *
   * @since    2.3.4
   *
   * @param    string    $site_id    'book_review_goodreads'|'book_review_barnes_noble'
   * @param    string    $text       Site link text
   * @param    string    $url        Site link URL
   * @param    string    $target     Target attribute of the site link
   *
   * @return   string   HTML for the plain text site link
   */
  private function get_text_site_link( $site_id, $text, $url, $target ) {
    $anchor = '<a class="custom-link" href="'
              . esc_url( $url )
              . '"'
              . $target
              . '>';

    return $anchor . esc_html( $text ) . '</a>';
  }

  /**
   * Get the HTML for a custom image site link.
   *
   * @since    2.3.4
   *
   * @param    string    $site_id    'book_review_goodreads'|'book_review_barnes_noble'
   * @param    string    $text       Site link text
   * @param    string    $link_url   Site link URL
   * @param    string    $link_url   Custom image URL
   * @param    string    $target     Target attribute of the site link
   *
   * @return   string   HTML for the custom image site link
   */
  private function get_custom_site_link( $site_id, $text, $link_url, $image_url, $target ) {
    $anchor = '<a class="custom-link" href="'
              . esc_url( $link_url )
              . '"'
              . $target
              . '>';

    $img = '<img src="'
          . esc_url( $image_url )
          . '" alt="'
          . esc_attr( $text )
          . '">';

    return $anchor . $img . '</a>';
  }

  /**
   * Retrieve the links meta that displays in the Book Info meta box.
   *
   * @since    2.3.1
   *
   * @param    string    $post_id    ID of the current post
   */
  public function get_book_review_links_meta( $post_id ) {
    global $wpdb;

    $sql = "SELECT links.custom_link_id, links.text, links.image_url, urls.url
      FROM {$wpdb->book_review_custom_links} AS links
      LEFT OUTER JOIN {$wpdb->book_review_custom_link_urls} AS urls ON links.custom_link_id = urls.custom_link_id
        AND urls.post_id = $post_id
      WHERE links.active = 1";

    $links_meta = $wpdb->get_results( $sql );

    return apply_filters( 'book_review_links_meta', $links_meta, $post_id );
  }

  /**
   * Retrieve the links.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_links_html( $post_id ) {
    global $wpdb;

    $html = array();
    $links_option = $this->settings->get_book_review_links_option();
    $links = $this->get_book_review_links_meta( $post_id );

    // Construct the HTML for each link.
    foreach ( $links as $link ) {
      if ( !empty( $link->url ) ) {
        if ( !empty( $link->image_url ) ) {
          array_push( $html, '<li><a class="custom-link" href="' . esc_url( $link->url ) . '"' .
            $this->get_link_target() . '>' . '<img src="' . esc_url( $link->image_url ) .'" alt="' .
            esc_attr( $link->text ) . '">' . '</a></li>' );
        }
        elseif ( !empty( $link->text ) ) {
          array_push( $html, '<li><a class="custom-link" href="' . esc_url( $link->url ) . '"' .
            $this->get_link_target() . '>' . esc_html( $link->text ) . '</a></li>' );
        }
      }
    }

    return apply_filters( 'book_review_links', $html, $post_id, $links_option );
  }

  /**
   * Retrieve the target attribute of a link.
   *
   * @since    2.3.1
   *
   * @return   string   Target attribute or empty string if none.
   */
  private function get_link_target() {
    $links_option = $this->settings->get_book_review_links_option();

    if ( $links_option['book_review_target'] === '1' ) {
      return ' target="_blank"';
    }

    return '';
  }

  /**
   * Retrieve the cover URL.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_cover_url( $post_id ) {
    $cover_url = get_post_meta( $post_id, 'book_review_cover_url', true );

    return apply_filters( 'book_review_cover_url', $cover_url, $post_id );
  }

  /**
   * Retrieve the summary.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_summary( $post_id ) {
    $summary = get_post_meta( $post_id, 'book_review_summary', true );

    return apply_filters( 'book_review_summary', $summary, $post_id );
  }

  /**
   * Retrieve the rating.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_rating( $post_id ) {
    $rating = get_post_meta( $post_id, 'book_review_rating', true );

    return apply_filters( 'book_review_rating', $rating, $post_id );
  }

  /**
   * Retrieve the rating image.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_rating_image( $post_id ) {
    $rating = $this->get_book_review_rating( $post_id );
    $ratings_option = $this->settings->get_book_review_ratings_option();

    // Default rating image.
    if ( $ratings_option['book_review_rating_default'] == '1' ) {
      $url = plugin_dir_url( dirname( __FILE__ ) );

      switch ( $rating ) {
        case '1':
          $url .= 'includes/images/one-star.png';
          break;
        case '2':
          $url .= 'includes/images/two-star.png';
          break;
        case '3':
          $url .= 'includes/images/three-star.png';
          break;
        case '4':
          $url .= 'includes/images/four-star.png';
          break;
        case '5':
          $url .= 'includes/images/five-star.png';
          break;
        default:
          $url = '';
          break;
      }
    }
    // Custom rating image.
    else {
      if ( empty( $rating ) || ( $rating === '-1' ) ) {
        $url = '';
      }
      else {
        $url = $ratings_option['book_review_rating_image' . $rating];
      }
    }

    return apply_filters( 'book_review_rating_image', $url, $post_id );
  }

  /**
   * Retrieve whether or not to include the post in the archives.
   *
   * @since    2.3.0
   *
   * @param    string    $post_id    ID of the current post.
   */
  public function get_book_review_archive_post( $post_id ) {
    $keys = get_post_custom_keys( $post_id );

    // An empty string means that the checkbox should not be checked.
    // Missing post meta means that the checkbox should be checked by default.
    // Reference - https://developer.wordpress.org/reference/functions/get_post_meta/ (comments)
    if ( !is_null( $keys ) ) {
      if ( in_array( 'book_review_archive_post', $keys ) ) {
        // Returns '1' (checked) or empty string (not checked).
        $archive_post = get_post_meta( $post_id, 'book_review_archive_post', true );
      }
      // No entry found for book_review_archive_post. Archive by default.
      else {
        $archive_post = '1';
      }
    }
    // No post meta. Archive by default.
    else {
      $archive_post = '1';
    }

    return apply_filters( 'book_review_archive_post', $archive_post, $post_id );
  }
}