<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://donnapeplinskie.com
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Book_Review
 * @subpackage Book_Review/public
 * @author     Donna Peplinskie <donnapep@gmail.com>
 */
class Book_Review_Public {
  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @var      string    $plugin_name       The name of the plugin.
   * @var      string    $version    The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    /**
     * An instance of this class should be passed to the run() function
     * defined in Plugin_Name_Public_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Plugin_Name_Public_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-public.css', array(), $this->version, 'all' );
  }

  /**
   * Inject book details into the post.
   *
   * NOTE:  Filters are points of execution in which WordPress modifies data
   *        before saving it or sending it to the browser.
   *
   *        Filters: http://codex.wordpress.org/Plugin_API#Filters
   *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
  *
   * @since    1.0.0
   *
   * @param    string    $content    Content of the post.
   *
   * @return   string   Revised content of the post.
   */
  public function inject_book_details( $content ) {
    global $wpdb;

    if ( is_home() || is_single() || is_feed() ) {
      $values = get_post_custom();

      // General
      $general_defaults = array(
        'book_review_box_position' => 'top',
        'book_review_bg_color' => '',
        'book_review_border_color' => '',
        'book_review_border_width' => '1',
        'book_review_date_format' => 'none',
      );
      $general_option = get_option( 'book_review_general' );
      $general_option = wp_parse_args( $general_option, $general_defaults );

      // Set the value for each key.
      foreach ( array( 'book_review_cover_url', 'book_review_title',
        'book_review_series', 'book_review_author', 'book_review_genre',
        'book_review_publisher', 'book_review_release_date',
        'book_review_format', 'book_review_pages', 'book_review_source',
        'book_review_rating', 'book_review_summary' ) as $var ) {
        $$var = isset( $values[$var][0] ) ? $values[$var][0] : '';
      }

      // Title must be specified.
      if ( !empty( $book_review_title ) ) {
        $plugin = Book_Review::get_instance();

        // Settings
        $box_position = $general_option['book_review_box_position'];
        $bg_color = $general_option['book_review_bg_color'];
        $border_color = $general_option['book_review_border_color'];
        $border_width = $general_option['book_review_border_width'];
        $review_box_style = '';

        // Don't apply inline CSS to an RSS feed.
        if ( !is_feed() ) {
          $review_box_style = 'style="border-style: solid;';

          if ( isset( $border_color ) && !empty( $border_color ) ) {
            $review_box_style .= ' border-color: ' . $border_color . ';';
          }

          if ( isset( $border_width ) && ( !empty( $border_width ) || ( $border_width == 0 ) ) ) {
            $review_box_style .= ' border-width: ' . $border_width . 'px;';
          }

          if ( isset( $bg_color ) && !empty( $bg_color ) ) {
            $review_box_style .= ' background-color: ' . $bg_color . ';"';
          }
          else {
            $review_box_style .= '"';
          }
        }

        // Rating
        $book_review_rating_url = $plugin->get_rating()->get_rating_image( $book_review_rating );

        // Link target
        $links_defaults = array(
          'book_review_target' => 0,
        );
        $links_option = get_option( 'book_review_links', $links_defaults );
        $links_option = wp_parse_args( $links_option, $links_defaults );

        if ( $links_option['book_review_target'] == '1' ) {
          $target = 'target=_blank';
        }
        else {
          $target = '';
        }

        // Custom links
        $links_table = $wpdb->prefix . "book_review_custom_links";
        $link_urls_table = $wpdb->prefix . 'book_review_custom_link_urls';
        $sql = "SELECT links.text, links.image_url, urls.url
          FROM $links_table AS links
          INNER JOIN $link_urls_table AS urls ON links.custom_link_id = urls.custom_link_id
            AND urls.post_id = " . get_the_ID() .
          " WHERE links.active = 1";

        $results = $wpdb->get_results( $sql );

        // Review Box Position
        ob_start();
        include( 'partials/book-review-public.php' );

        $content = '<div itemprop="reviewBody">' . $content . '</div>';

        if ( $box_position == 'top' ) {
          $content = ob_get_clean() . $content;
        }
        else {
          $content = $content . ob_get_clean();
        }
      }
    }

    return $content;
  }

  /**
   * Inject rating into the excerpt.
   *
   * @since    1.0.0
   *
   * @param    string    $content    Content of the excerpt.
   *
   * @return   string   Revised content of the excerpt.
   */
  public function inject_book_rating( $content ) {
    if ( is_home() || is_archive() || is_search() ) {
      $values = get_post_custom( get_the_ID() );
      $ratings_option = get_option( 'book_review_ratings' );

      if ( ( isset( $ratings_option['book_review_rating_home'] ) != null )
        && ( isset( $values['book_review_rating'] ) != null ) ) {
        if ( $ratings_option['book_review_rating_home'] == '1' ) {
          $plugin = Book_Review::get_instance();
          $rating = $values['book_review_rating'][0];
          $src = $plugin->get_rating()->get_rating_image( $rating );

          if ( !empty( $src) ) {
            $content = '<p class="book_review_rating_image"><img src="' .
              $src . '"/>' . $content . '</p>';
          }
        }
      }
    }

    return $content;
  }

  /**
   * Build the archives.
   *
   * @since    1.3.0
   *
   * @param    array    $atts    Shortcode attributes or an empty string.
   *
   * @return   string   Content to insert into the post.
   */
  public function handle_shortcode( $atts ) {
    global $wpdb;

    $plugin = Book_Review::get_instance();
    $prefix = $wpdb->prefix;

    extract( shortcode_atts( array(
      'type' => 'title',
      'show_cover' => 'false',
      'show_rating' => 'false',
    ), $atts ) );

    if ( $type == 'title' ) {
      $query = "
        SELECT DISTINCT title.post_id, thumb.meta_value AS thumb,
          IFNULL(archive.meta_value, 1) AS archivePost,
          IFNULL(archiveTitle.meta_value, title.meta_value) AS title,
          author.meta_value AS author
        FROM {$prefix}posts wp
        INNER JOIN {$prefix}postmeta title ON wp.ID = title.post_id
        LEFT OUTER JOIN {$prefix}postmeta thumb ON wp.ID = thumb.post_id
          AND thumb.meta_key = '_thumbnail_id'
        LEFT OUTER JOIN {$prefix}postmeta archiveTitle
          ON title.post_id = archiveTitle.post_id
          AND archiveTitle.meta_key = 'book_review_archive_title'
        LEFT OUTER JOIN {$prefix}postmeta author
          ON title.post_id = author.post_id
          AND author.meta_key = 'book_review_author'
        LEFT OUTER JOIN {$prefix}postmeta archive
          ON title.post_id = archive.post_id
          AND archive.meta_key = 'book_review_archive_post'
        WHERE title.meta_key = 'book_review_title' AND title.meta_value <> ''
          AND wp.post_status =  'publish'
        ORDER BY title";
    }
    else if ( $type == 'genre' ) {
      $query = "
        SELECT DISTINCT genre.post_id, thumb.meta_value AS thumb,
          IFNULL(archive.meta_value, 1) AS archivePost,
          IFNULL(archiveTitle.meta_value, title.meta_value) AS title,
          author.meta_value AS author, genre.meta_value AS genre
        FROM {$prefix}posts wp
        INNER JOIN {$prefix}postmeta genre ON wp.ID = genre.post_id
        LEFT OUTER JOIN {$prefix}postmeta thumb
          ON wp.ID = thumb.post_id
          AND thumb.meta_key = '_thumbnail_id'
        LEFT OUTER JOIN {$prefix}postmeta title
          ON genre.post_id = title.post_id
          AND title.meta_key = 'book_review_title'
        LEFT OUTER JOIN {$prefix}postmeta archiveTitle
          ON genre.post_id = archiveTitle.post_id
          AND archiveTitle.meta_key = 'book_review_archive_title'
        LEFT OUTER JOIN {$prefix}postmeta author
          ON genre.post_id = author.post_id
          AND author.meta_key = 'book_review_author'
        LEFT OUTER JOIN {$prefix}postmeta archive
          ON genre.post_id = archive.post_id
          AND archive.meta_key = 'book_review_archive_post'
        WHERE genre.meta_key = 'book_review_genre' AND genre.meta_value <> ''
          AND wp.post_status =  'publish'
        ORDER BY genre, title";
    }

    $results = $wpdb->get_results( $query );
    $html[] = '<div class="book-review-archives">';
    $size = $this->get_thumbnail_size();

    foreach( $results as $result ) {
      // Only include this post if it has been flagged to be shown in the
      // archive.
      if ( $result->archivePost == 1 ) {
        $thumb = wp_get_attachment_image( $result->thumb, 'thumbnail' );

        // No featured image. Use the cover URL with maximum dimensions set
        // to be the thumbnail size from the Media Settings.
        if ( $thumb == '' ) {
          // This is faster than adding to the main query. Consider using this
          // approach for other fields instead of adding them to the main query.
          $values = get_post_custom_values( 'book_review_cover_url',
            $result->post_id );
          $cover_url = $values[0];
          $thumb = '<a href="' . get_permalink( $result->post_id ) .
            '"><img src="' . $cover_url . '" style="max-width:' . $size[0] .
            'px; max-height:' . $size[1] . 'px;" /></a>';
        }
        else {
          $url = wp_get_attachment_image_src( $result->thumb, 'thumbnail' );
          $thumb = '<a href="' . get_permalink( $result->post_id ) . '">' .
            $thumb . '</a>';
        }


        if ( $type == 'title' ) {
          // Get first letter of title.
          $current = strtoupper( substr( $result->title, 0, 1 ) );

          if ( isset( $previous ) && ( $current != $previous ) ) {
            // Check if both titles start with a number. In that case,
            // don't end the list.
            if ( is_numeric( $current ) && is_numeric( $previous ) ) {
              // Do nothing.
            }
            else {
              $html[] = '</ul>';
            }
          }

          if ( $current != $previous ) {
            // Check if both titles start with a number. In that case, don't
            // create a new heading.
            if ( is_numeric( $current ) ) {
              if ( is_numeric( $previous ) ) {
                // Do nothing.
              }
              else {
                $html[] = '<h4 class="header">#</h4>';

                if ( $show_cover == 'true' ) {
                  $html[] = '<ul class="thumbs">';
                }
                else {
                  $html[] = '<ul>';
                }
              }
            }
            else {
              $html[] = '<h4 class="header">' . $current . '</h4>';

              if ( $show_cover == 'true' ) {
                $html[] = '<ul class="thumbs">';
              }
              else {
                $html[] = '<ul>';
              }
            }
          }
        }
        else {
          $current = $result->genre;

          if ( isset( $previous ) && ( $current != $previous ) ) {
            $html[] = '</ul>';
          }

          if ( $current != $previous ) {
            $html[] = '<h4 class="header">' . $current . '</h4>';

            if ( $show_cover == 'true' ) {
              $html[] = '<ul class="thumbs">';
            }
            else {
              $html[] = '<ul>';
            }
          }
        }

        $html[] = '<li>';

        // Cover
        if ( $show_cover == 'true' ) {
          if ( $thumb != '' ) {
            $html[] .= $thumb;
          }
        }

        // Title and author
        if ( $show_cover == 'true' ) {
          $html[] = '<h5 class="title"><a href="'. get_permalink(
            $result->post_id ) . '">' . $result->title . '</a></h5>';

          if ( !empty( $result->author ) ) {
            $html[] = '<p>by ' . $result->author . '</p>';
          }
        }
        else {
          $html[] = '<p><a href="'. get_permalink( $result->post_id ) . '">' .
            $result->title . '</a>';

          if ( !empty( $result->author ) ) {
            $html[] = 'by ' . $result->author;
          }
        }

        // Rating
        if ( $show_rating == 'true' ) {
          $values = get_post_custom_values( 'book_review_rating',
            $result->post_id );
          $ratings_option = get_option( 'book_review_ratings' );
          $rating = $values[0];

          $book_review_rating_url = $plugin->get_rating()->get_rating_image( $rating );

          if ( !empty ( $book_review_rating_url ) ) {
            if ( $show_cover == 'true' ) {
              $html[] = '<p><img src="' . $book_review_rating_url . '" /></p>';
            }
            else {
              $html[] = '<img src="' . $book_review_rating_url . '" />';
            }
          }
        }

        if ( $show_cover == 'false' ) {
          $html[] = '</p>';
        }

        $html[] = '</li>';

        $previous = $current;
      }
    }

    $html[] = '</div>';

    return implode( "\n", $html );
  }

  /**
   * Return the size of a book cover's thumbnail.
   *
   * @since    1.3.0
   *
   * @return   array   Width and height of the thumbnail.
   */
  private function get_thumbnail_size() {
    $dimensions = array();

    foreach( get_intermediate_image_sizes() as $s ) {
      if ( in_array( $s, array( 'thumbnail' ) ) ) {
        $dimensions[0] = get_option( $s . '_size_w' );
        $dimensions[1] = get_option( $s . '_size_h' );
        break;
      }
    }

    return $dimensions;
  }
}
?>