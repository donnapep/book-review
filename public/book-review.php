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
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `book-review-admin.php`
 *
 * @package Book_Review
 * @author  Donna Peplinskie <donnapep@gmail.com>
 */

class Book_Review {
  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   2.0.0
   *
   * @var     string
   */
  const VERSION = '2.1.6';

  /**
   * Unique identifier for your plugin.
   *
   *
   * The variable name is used as the text domain when internationalizing
   * strings of text. Its value should match the Text Domain file header in the
   * main plugin file.
   *
   * @since    2.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'book-review';

  /**
   * Instance of this class.
   *
   * @since    2.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct() {
    // Load plugin text domain.
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    // Activate plugin when new blog is added.
    add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

    // Load public-facing style sheet.
    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

    /* Define custom functionality. Refer to
     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */
    add_filter( 'the_excerpt', array( $this, 'inject_book_rating' ) );
    add_filter( 'the_content', array( $this, 'inject_book_details' ) );
    add_shortcode( 'book_review_archives', array( $this, 'handle_shortcode' ) );
  }

  /**
   * Return the plugin slug.
   *
   * @since    2.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_plugin_slug() {
    return $this->plugin_slug;
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

    $version = get_option( 'book_review_version' );

    // Database version does not exist before version 2.1.6.
    if ( empty( $version ) ) {
      // Add the option first since sometimes get_instance gets called again before
      // the tables are created and the data converted.
      add_option( 'book_review_version', self::VERSION );
      self::create_tables();
      self::convert_data();
    }
    else if ( $version != self::VERSION ) {
      update_option( 'book_review_version', self::VERSION );
    }

    return self::$instance;
  }

  /**
   * Fired when the plugin is activated.
   *
   * @since    2.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate( $network_wide ) {
    error_log('activate');
    error_log($network_wide);

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {
      if ( $network_wide ) {
        // Get all blog ids.
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {
          switch_to_blog( $blog_id );
          self::single_activate();
        }

        restore_current_blog();
      }
      else {
        self::single_activate();
      }
    }
    else {
      self::single_activate();
    }
  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    2.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public static function deactivate( $network_wide ) {
    if ( function_exists( 'is_multisite' ) && is_multisite() ) {
      if ( $network_wide ) {
        // Get all blog ids.
        $blog_ids = self::get_blog_ids();

        foreach ( $blog_ids as $blog_id ) {
          switch_to_blog( $blog_id );
          self::single_deactivate();
        }

        restore_current_blog();
      }
      else {
        self::single_deactivate();
      }
    }
    else {
      self::single_deactivate();
    }
  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    2.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  public function activate_new_site( $blog_id ) {
    error_log('activate_new_site');

    if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
      return;
    }

    switch_to_blog( $blog_id );
    self::single_activate();
    restore_current_blog();
  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    2.0.0
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids() {
    global $wpdb;

    // Get an array of blog ids.
    $sql = "SELECT blog_id FROM $wpdb->blogs
      WHERE archived = '0' AND spam = '0'
      AND deleted = '0'";

    return $wpdb->get_col( $sql );
  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    2.0.0
   */
  private static function single_activate() {
    error_log('single_activate');
    $version = get_option( 'book_review_version' );

    if ( empty( $version ) ) {
      add_option( 'book_review_version', self::VERSION );
      self::create_tables();
    }
    else if ( $version != self::VERSION ) {
      update_option( 'book_review_version', self::VERSION );
    }
  }

  /**
   * Creates the tables that the plugin uses.
   *
   * @since    2.1.6
   */
  private static function create_tables() {
    error_log('create_tables');
    global $wpdb;

    /*
     * We'll set the default character set and collation for this table.
     * If we don't do this, some characters could end up being converted
     * to just ?'s when saved in our table.
     */
    $charset_collate = '';

    if ( !empty( $wpdb->charset ) ) {
      $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if ( !empty( $wpdb->collate ) ) {
      $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    // Create table for custom links.
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}book_review_custom_links (
      custom_link_id int NOT NULL AUTO_INCREMENT,
      text varchar(100) NOT NULL,
      image_url varchar(200),
      active int(1) NOT NULL DEFAULT 1,
      UNIQUE KEY id (custom_link_id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // Create table for custom link URLs.
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}book_review_custom_link_urls (
      post_id bigint(20) NOT NULL,
      custom_link_id int NOT NULL,
      url varchar(200),
      UNIQUE KEY id (post_id, custom_link_id)
    ) $charset_collate;";

    dbDelta( $sql );
  }

  /**
   * Migrates custom links from native tables to custom tables.
   *
   * @since    2.1.6
   */
  private static function convert_data() {
    error_log('convert_data');
    global $wpdb;

    $links = array();
    $links_option = get_option( 'book_review_links' );
    $target = isset( $links_option['book_review_link_target'] ) ? $links_option['book_review_link_target'] : -1;
    $num_links = isset( $links_option['book_review_num_links'] ) ? ( int )$links_option['book_review_num_links'] : 0;

    // Iterate over all of the custom links in the options table.
    for ( $i = 1; $i <= $num_links; $i++ ) {
      $text = $links_option['book_review_link_text' . $i];
      $image_url = $links_option['book_review_link_image' . $i];
      $new_link = array(
        'text' => $text,
        'image_url' => $image_url
      );

      array_push( $links, $new_link );
    }

    // Add the link text and image to the book_review_custom_links table.
    foreach ( $links as $link ) {
      $success = $wpdb->insert(
        $wpdb->prefix . "book_review_custom_links",
        array(
          'text' => $link['text'],
          'image_url' => $link['image_url']
        ),
        array( '%s', '%s' )
      );

      // In case of failure, remove any rows that may already have been inserted.
      if ( $success != 1 ) {
        $sql = "DELETE FROM {$wpdb->prefix}book_review_custom_links";
        $wpdb->query($sql);
        $wpdb->print_error();
        return;
      }
    }

    // Delete the links option and then save the target back since it's the
    // only setting that needs to be preserved.
    if ( $target != -1 ) {
      delete_option( 'book_review_links' );
      add_option( 'book_review_links', array( 'book_review_target' => $target ) );
    }

    // Populate the book_review_custom_link_urls table.
    $sql = "INSERT INTO {$wpdb->prefix}book_review_custom_link_urls (post_id, custom_link_id, url)
      SELECT meta.post_id, links.custom_link_id, meta.meta_value
      FROM {$wpdb->prefix}postmeta AS meta
      INNER JOIN {$wpdb->prefix}book_review_custom_links AS links ON links.custom_link_id = SUBSTRING(meta.meta_key, -1)
      WHERE meta_key IN ('book_review_link1', 'book_review_link2', 'book_review_link3', 'book_review_link4', 'book_review_link5')
        AND meta_value <> ''";

    // Run query.
    $success = $wpdb->query($sql);

    // There was a problem executing the query.
    if ($success === false) {
      $wpdb->print_error();
      return;
    }
    // Delete links from the postmeta table.
    else {
      $sql = "DELETE FROM {$wpdb->prefix}postmeta
        WHERE meta_key IN ('book_review_link1', 'book_review_link2', 'book_review_link3', 'book_review_link4', 'book_review_link5')";
      $success = $wpdb->query($sql);

      if ($success === false) {
        $wpdb->print_error();
        return;
      }
    }
  }

  /**
   * Fired for each blog when the plugin is deactivated.
   *
   * @since    2.0.0
   */
  private static function single_deactivate() {
    // @TODO: Define deactivation functionality here
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {
    $domain = $this->plugin_slug;
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' .
      $domain . '-' . $locale . '.mo' );
    load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path(
      dirname( __FILE__ ) ) ) . '/languages/' );
  }

  /**
   * Register and enqueue public-facing style sheet.
   *
   * @since    2.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url(
      'assets/css/public.css', __FILE__ ), array(), self::VERSION );
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
        // Settings
        $box_position = $general_option['book_review_box_position'];
        $bg_color = $general_option['book_review_bg_color'];
        $border_color = $general_option['book_review_border_color'];
        $bg_style = '';

        // Don't apply inline CSS to an RSS feed.
        if ( !is_feed() ) {
          if ( isset( $bg_color ) && !empty( $bg_color ) ) {
            $bg_style = 'style="background-color: ' . $bg_color . ';';
          }

          if ( isset( $border_color ) && !empty( $border_color ) ) {
            if ( isset( $bg_style ) ) {
              $bg_style .= ' border: 1px solid ' . $border_color . ';"';
            }
            else {
              $bg_style = 'style="border: 1px solid ' . $border_color . ';"';
            }
          }
          else {
            if ( isset( $bg_style ) ) {
              $bg_style .= '"';
            }
          }
        }

        // Rating
        $book_review_rating_url = $this->get_rating_image( $book_review_rating );

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
        include( 'views/public.php' );

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
          $rating = $values['book_review_rating'][0];
          $src = $this->get_rating_image( $rating );

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

          $book_review_rating_url = $this->get_rating_image( $rating );

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

  /**
   * Return the URL of the rating image.
   *
   * @since    1.0.0
   *
   * @param    string    $rating    User rating of the book.
   *
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
          $src = plugins_url( 'assets/one-star.png', dirname( __FILE__ ) );
        }
        else if ( $rating == '2' ) {
          $src = plugins_url( 'assets/two-star.png', dirname( __FILE__ ) );
        }
        else if ( $rating == '3' ) {
          $src = plugins_url( 'assets/three-star.png', dirname( __FILE__ ) );
        }
        else if ( $rating == '4' ) {
          $src = plugins_url( 'assets/four-star.png', dirname( __FILE__ ) );
        }
        else if ( $rating == '5' ) {
          $src = plugins_url( 'assets/five-star.png', dirname( __FILE__ ) );
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
?>