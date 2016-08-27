<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wpreviewplugins.com/
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
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
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
   * @param    string                 $plugin_name  Plugin name
   * @param    string                 $version      Plugin version
   * @param    Book_Review_Settings   $settings     Instance of Book_Review_Settings for
   *                                                  getting the settings.
   * @param    Book_Review_Book_Info  $book_info    Instance of Book_Review_Book_Info for
   *                                                  getting information about a book.
   */
  public function __construct( $plugin_name, $version, $settings, $book_info ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->settings = $settings;
    $this->book_info = $book_info;
  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-public.min.css',
      array(), $this->version, 'all' );
  }

  /**
   * Display the book info.
   *
   * @since    1.0.0
   *
   * @param    string    $content    Content of the post.
   *
   * @return   string   Revised content of the post.
   */
  public function display_book_info( $content ) {
    if ( in_the_loop() ) {
      $post_id = get_the_ID();
      $title = $this->book_info->get_book_review_title( $post_id );

      // Title is a required field.
      if ( !empty( $title ) ) {
        $show = false;
        $general_option = $this->settings->get_book_review_general_option();

        // Post Types
        $post_types = $general_option['book_review_post_types'];
        $current_post_type = get_post_type( $post_id );

        // To maintain backwards compatibility, show the book info for custom post types that have not
        // been saved in the settings yet.
        if ( !isset( $post_types[$current_post_type] ) ) {
          $show = true;
        }
        // Check if current post type has been selected in settings.
        else if ( $post_types[$current_post_type] == '1' ) {
          $show = true;
        }

        // Show the review box.
        if ( $show ) {
          ob_start();
          include( 'partials/book-review-public.php' );

          $content = '<div itemprop="reviewBody">' . $content . '</div>';

          // Review Box Position
          if ( $general_option['book_review_box_position'] === 'top' ) {
            $content = ob_get_clean() . $content;
          }
          else if ( $general_option['book_review_box_position'] === 'bottom' ) {
            $content = $content . ob_get_clean();
          }
        }
      }
    }

    return $content;
  }

  /**
   * Retrieve the style attribute for the review box.
   *
   * @since    2.3.0
   *
   * @return   string   Style attribute or empty string if none.
   */
  public function get_review_box_style() {
    $general_option = $this->settings->get_book_review_general_option();
    $border_color = $general_option['book_review_border_color'];
    $border_width = $general_option['book_review_border_width'];
    $bg_color = $general_option['book_review_bg_color'];

    // Don't apply inline CSS to an RSS feed.
    if ( !is_feed() ) {
      if ( !empty( $border_width ) && ( $border_width > 0 ) ) {
        $style = 'border-style: solid; border-width: ' . $border_width . 'px;';

        if ( !empty( $border_color ) ) {
          $style .= ' border-color: ' . $border_color . ';';
        }

        if ( !empty( $bg_color ) ) {
          $style .= ' background-color: ' . $bg_color . ';';
        }

        return 'style="' . esc_attr( $style ) . '"';
      }
    }

    return '';
  }

  /**
   * Add rating to the excerpt.
   *
   * @since    1.0.0
   *
   * @param    string    $content    Content of the excerpt.
   *
   * @return   string   Revised content of the excerpt.
   */
  public function add_rating( $content ) {
    if ( is_home() || is_archive() || is_search() ) {
      $rating = $this->book_info->get_book_review_rating( get_the_ID() );
      $ratings_option = $this->settings->get_book_review_ratings_option( 'book_review_ratings' );

      if ( !empty( $rating ) && ( $ratings_option['book_review_rating_home'] == '1' ) ) {
        $src = $this->book_info->get_book_review_rating_image( get_the_ID() );

        if ( !empty( $src) ) {
          $content = '<p class="book_review_rating_image"><img src="' . esc_url( $src ) . '">' .
            $content . '</p>';
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

    $atts = shortcode_atts( array(
      'type' => 'title',
      'show_cover' => 'false',
      'show_rating' => 'false',
    ), $atts );

    if ( $atts['type'] == 'title' ) {
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
          AND wp.post_status = 'publish'
        ORDER BY title";
    }
    else if ( $atts['type'] == 'genre' ) {
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

    foreach ( $results as $result ) {
      // Only include this post if it has been flagged to be shown in the
      // archive.
      if ( $result->archivePost == 1 ) {
        $thumb = wp_get_attachment_image( $result->thumb, 'thumbnail' );

        // No featured image. Use the cover URL with maximum dimensions set
        // to be the thumbnail size from the Media Settings.
        if ( $thumb == '' ) {
          // This is faster than adding to the main query. Consider using this
          // approach for other fields instead of adding them to the main query.
          $values = get_post_custom_values( 'book_review_cover_url', $result->post_id );
          $cover_url = $values[0];
          $thumb = '<a href="' . esc_url( get_permalink( $result->post_id ) ) .
            '"><img src="' . esc_url( $cover_url ) . '" style="max-width:' . esc_attr( $size[0] ) .
            'px; max-height:' . esc_attr( $size[1] ) . 'px;"></a>';
        }
        else {
          $url = wp_get_attachment_image_src( $result->thumb, 'thumbnail' );
          $thumb = '<a href="' . esc_url( get_permalink( $result->post_id ) ) . '">' . $thumb . '</a>';
        }

        if ( $atts['type'] == 'title' ) {
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

                if ( $atts['show_cover'] == 'true' ) {
                  $html[] = '<ul class="thumbs">';
                }
                else {
                  $html[] = '<ul>';
                }
              }
            }
            else {
              $html[] = '<h4 class="header">' . esc_html( $current ) . '</h4>';

              if ( $atts['show_cover'] == 'true' ) {
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
            $html[] = '<h4 class="header">' . esc_html( $current ) . '</h4>';

            if ( $atts['show_cover'] == 'true' ) {
              $html[] = '<ul class="thumbs">';
            }
            else {
              $html[] = '<ul>';
            }
          }
        }

        $html[] = '<li>';

        // Cover
        if ( $atts['show_cover'] == 'true' ) {
          if ( $thumb != '' ) {
            $html[] .= $thumb;
          }
        }

        // Title and author
        if ( $atts['show_cover'] == 'true' ) {
          $html[] = '<h5 class="title"><a href="'. esc_url( get_permalink( $result->post_id ) ) .
            '">' . esc_html( $result->title ) . '</a></h5>';

          if ( !empty( $result->author ) ) {
            $html[] = '<p>' . esc_html( $result->author ) . '</p>';
          }
        }
        else {
          $html[] = '<p><a href="'. esc_url( get_permalink( $result->post_id ) ) . '">' .
            esc_html( $result->title ) . '</a>';

          if ( !empty( $result->author ) ) {
            $html[] = esc_html( $result->author );
          }
        }

        // Rating
        if ( $atts['show_rating'] == 'true' ) {
          $rating_url = $this->book_info->get_book_review_rating_image( $result->post_id );

          if ( !empty ( $rating_url ) ) {
            if ( $atts['show_cover'] == 'true' ) {
              $html[] = '<p><img src="' . esc_url( $rating_url ) . '"></p>';
            }
            else {
              $html[] = '<img src="' . esc_url( $rating_url ) . '">';
            }
          }
        }

        if ( $atts['show_cover'] == 'false' ) {
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

    foreach ( get_intermediate_image_sizes() as $s ) {
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