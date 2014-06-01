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
	 * @since   2.0
	 *
	 * @var     string
	 */
	const VERSION = '2.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    2.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'book-review';

	/**
	 * Instance of this class.
	 *
	 * @since    2.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0
	 */
	private function __construct() {
		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added.
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		/* Define custom functionality.
		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( 'admin_init', array( $this, 'init_menu' ) );
		add_filter( 'the_content', array( $this, 'add_book_review_info' ) );
		add_filter( 'the_excerpt', array( $this, 'add_rating_to_home' ) );

		add_shortcode( 'book_review_archives', array( $this, 'handle_shortcode' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    2.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     2.0
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
	 * Fired when the plugin is activated.
	 *
	 * @since    2.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {
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
	 * @since    2.0
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
	 * @since    2.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
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
	 * @since    2.0
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
	 * @since    2.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    2.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_plugin_textdomain() {
		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    2.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Render the book review info for a post.
	 *
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0
	 */
	public function add_book_review_info( $content ) {
		if (is_home() || is_single() || is_feed()) {
			$values = get_post_custom();

			//General
			$general = get_option('book_review_general');
			$general_defaults = array(
				'book_review_box_position' => 'top'
			);
			$general = wp_parse_args($general, $general_defaults);

			//Rating Images
			$ratings = get_option('book_review_ratings');
			$ratings_defaults = array(
				'book_review_rating_default' => 1
			);
			$ratings = wp_parse_args($ratings, $ratings_defaults);

			$options = get_option('book_review_links');
			$num_links = $options['book_review_num_links'];
			$link_target = $options['book_review_link_target'];

			//Title must be specified.
			if ($values["book_review_title"][0] == null) {
				//Do nothing.
			}
			else {
				$title = $values["book_review_title"][0];
				$series = $values["book_review_series"][0];
				$author = $values["book_review_author"][0];
				$genre = $values["book_review_genre"][0];
				$publisher = $values["book_review_publisher"][0];
				$release_date = $values["book_review_release_date"][0];
				$format = $values["book_review_format"][0];
				$pages = $values["book_review_pages"][0];
				$source = $values["book_review_source"][0];
				$link1 = esc_url($values["book_review_link1"][0]);
				$link2 = esc_url($values["book_review_link2"][0]);
				$link3 = esc_url($values["book_review_link3"][0]);
				$link4 = esc_url($values["book_review_link4"][0]);
				$link5 = esc_url($values["book_review_link5"][0]);
				$cover_url = esc_url($values["book_review_cover_url"][0]);
				$summary = $values["book_review_summary"][0];
				$rating = $values["book_review_rating"][0];
				$box_position = $general["book_review_box_position"];
				$bg_color = $general["book_review_bg_color"];
				$border_color = $general["book_review_border_color"];

				//Don't apply inline CSS to an RSS feed.
				if (is_feed()) {
					$bg_style = '';
				}
				else {
					if (isset($bg_color) && !empty($bg_color)) {
						$bg_style = 'style="background-color: ' . $bg_color . ';';
					}

					if (isset($border_color) && !empty($border_color)) {
						if (isset($bg_style)) {
							$bg_style .= ' border: 1px solid ' . $border_color . ';"';
						}
						else {
							$bg_style = 'style="border: 1px solid ' . $border_color . ';"';
						}
					}
					else {
						if (isset($bg_style)) {
							$bg_style .= '"';
						}
					}
				}

				$book_info = '<div itemscope itemtype="http://schema.org/Book" id="book-review"' . $bg_style . '>';

				if (!empty($cover_url)) {
					$book_info .= '<img itemprop="image" id="book_review_cover_image" src="' .
						$cover_url . '" alt="' . $title . ' Book Cover ' . '" />';
				}

				if (!empty($title)) {
					$book_info .= '<label for="book_review_title">' . __('Title', 'book-review') . ':</label>' .
						'<span itemprop="name" id="book_review_title">' . $title . '</span><br />';
				}

				if (!empty($series)) {
					$book_info .= '<label for="book_review_series">' . __('Series', 'book-review') . ':</label>' .
						'<span id="book_review_series">' . $series . '</span><br />';
				}

				if (!empty($author)) {
					$book_info .= '<label for="book_review_author">' . __('Author', 'book-review') . ':</label>' .
						'<span itemprop="author" itemscope="" itemtype="http://schema.org/Person" id="book_review_author">' .
						'<span itemprop="name">' . $author . '</span></span><br />';
				}

				if (!empty($genre)) {
					$book_info .= '<label for="book_review_genre">' . __('Genre', 'book-review') . ':</label>' .
						'<span itemprop="genre" id="book_review_genre">' . $genre . '</span><br />';
				}

				if (!empty($publisher)) {
					$book_info .= '<label for="book_review_publisher">' . __('Publisher', 'book-review') . ':</label>' .
						'<span itemprop="publisher" id="book_review_publisher">' . $publisher . '</span><br />';
				}

				if (!empty($release_date)) {
					$book_info .= '<label for="book_review_release_date">' . __('Release Date', 'book-review') . ':</label>' .
						'<meta itemprop="datePublished" content="' . date( "Y-m-d", strtotime( $release_date ) ) . '">' .
						'<span id="book_review_release_date">' . $release_date . '</span><br />';
				}

				if (!empty($format)) {
					$book_info .= '<label for="book_review_format">' . __('Format', 'book-review') . ':</label>' .
						'<span itemprop="bookFormatType" id="book_review_format">' . $format . '</span><br />';
				}

				if (!empty($pages)) {
					$book_info .= '<label for="book_review_pages">' . __('Pages', 'book-review') . ':</label>' .
						'<span itemprop="numberOfPages" id="book_review_pages">' . $pages . '</span><br />';
				}

				if (!empty($source)) {
					$book_info .= '<label for="book_review_source">' . __('Source', 'book-review') . ':</label>' .
						'<span id="book_review_source">' . $source . '</span><br />';
				}

				if (!empty($rating) && ($rating != -1)) {
					if ($ratings['book_review_rating_default'] == "1") {
						if ($rating == "1") {
							$src = plugins_url('assets/one-star.png', dirname(__FILE__) );
						}
						else if ($rating == "2") {
							$src = plugins_url('assets/two-star.png', dirname(__FILE__) );
						}
						else if ($rating == "3") {
							$src = plugins_url('assets/three-star.png', dirname(__FILE__) );
						}
						else if ($rating == "4") {
							$src = plugins_url('assets/four-star.png', dirname(__FILE__) );
						}
						else if ($rating == "5") {
							$src = plugins_url('assets/five-star.png', dirname(__FILE__) );
						}
					}
					else {
						$src = $ratings['book_review_rating_image' . $rating];
					}

					$book_info .= '<div itemprop="review" itemscope itemtype="http://schema.org/Review">' .
						'<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">' .
						'<meta itemprop="ratingValue" content="' . $rating . '">' .
						'<img id="book_review_rating_image" src="' . $src . '" /><br />' .
						'</div></div>';
				}

				if (!empty($summary)) {
					$book_info .= '<br />' .
						'<label for="book_review_summary">' . __('Synopsis', 'book-review') . ':</label>' .
						'<div id="book_review_summary">' . wpautop($summary, true) . '</div>';
				}

				$book_info .= '<ul id="book-review-links">';

				if ($link_target == 1) {
					$target = 'target=_blank';
				}
				else {
					$target = '';
				}

				if (!empty($link1) && ($num_links >= 1)) {
					$book_info .= '<li>';

					if ($options['book_review_link_image1']) {
						$book_info .= '<a class="custom-link" href="' . $link1 . '" ' . $target . '>' .
							'<img src="' . $options['book_review_link_image1'] . '" alt="' . $options['book_review_link_text1'] . '" />' . '</a>';
					}
					else {
						$book_info .= '<a class="custom-link" href="' . $link1 . '" ' . $target . '>' . $options['book_review_link_text1'] . '</a>';
					}

					$book_info .= '</li>';
				}

				if (!empty($link2) && ($num_links >= 2)) {
					$book_info .= '<li>';

					if ($options['book_review_link_image2']) {
						$book_info .= '<a class="custom-link" href="' . $link2 . '" ' . $target . '>' .
							'<img src="' . $options['book_review_link_image2'] . '" alt="' . $options['book_review_link_text2'] . '" />' . '</a>';
					}
					else {
						$book_info .= '<a class="custom-link" href="' . $link2 . '" ' . $target . '>' . $options['book_review_link_text2'] . '</a>';
					}

					$book_info .= '</li>';
				}

				if (!empty($link3) && ($num_links >= 3)) {
					$book_info .= '<li>';

					if ($options['book_review_link_image3']) {
						$book_info .= '<a class="custom-link" href="' . $link3 . '" ' . $target . '>' .
							'<img src="' . $options['book_review_link_image3'] . '" alt="' . $options['book_review_link_text3'] . '" />' . '</a>';
					}
					else {
						$book_info .= '<a class="custom-link" href="' . $link3 . '" ' . $target . '>' . $options['book_review_link_text3'] . '</a>';
					}

					$book_info .= '</li>';
				}

				if (!empty($link4) && ($num_links >= 4)) {
					$book_info .= '<li>';

					if ($options['book_review_link_image4']) {
						$book_info .= '<a class="custom-link" href="' . $link4 . '" ' . $target . '>' .
							'<img src="' . $options['book_review_link_image4'] . '" alt="' . $options['book_review_link_text4'] . '" />' . '</a>';
					}
					else {
						$book_info .= '<a class="custom-link" href="' . $link4 . '" ' . $target . '>' . $options['book_review_link_text4'] . '</a>';
					}

					$book_info .= '</li>';
				}

				if (!empty($link5) && ($num_links >= 5)) {
					$book_info .= '<li>';

					if ($options['book_review_link_image5']) {
						$book_info .= '<a class="custom-link" href="' . $link5 . '" ' . $target . '>' .
							'<img src="' . $options['book_review_link_image5'] . '" alt="' . $options['book_review_link_text5'] . '" />' . '</a>';
					}
					else {
						$book_info .= '<a class="custom-link" href="' . $link5 . '" ' . $target . '>' . $options['book_review_link_text5'] . '</a>';
					}

					$book_info .= '</li>';
				}

				$book_info .= '</ul></div>';

				if ($box_position == "top") {
					$content = $book_info . $content;
				}
				else {	//Bottom
					$content = $content . $book_info;
				}
			}
		}

		return $content;
	}

	public function add_rating_to_home($content) {
		if (is_home() || is_archive() || is_search()) {
			$values = get_post_custom(get_the_ID());
			$ratings = get_option('book_review_ratings');

			if ((isset($ratings['book_review_rating_home']) != null) && (isset($values["book_review_rating"]) != null)) {
				$rating = $values["book_review_rating"][0];

				if ($ratings['book_review_rating_home'] == "1") {
					if (!empty($rating) && ($rating != "-1")) {
						$content = '<p class="book_review_rating_image"><img src="' . $this->get_rating_image($rating) . '"/>' . $content . '</p>';
					}
				}
			}
		}

		return $content;
	}

	/**
	 * Build the archives.
	 *
	 * @since    1.3
	 */
	public function handle_shortcode($atts) {
		global $wpdb;

		$prefix = $wpdb->prefix;

		extract(shortcode_atts(array(
			'type' => 'title',
			'show_cover' => 'false',
			'show_rating' => 'false',
		), $atts));

		if ($type == 'title') {
			$query = "
				SELECT DISTINCT title.post_id, thumb.meta_value AS thumb, IFNULL(archive.meta_value, 1) AS archivePost, IFNULL(archiveTitle.meta_value, title.meta_value) AS title, author.meta_value AS author
				FROM {$prefix}posts wp
				INNER JOIN {$prefix}postmeta title ON wp.ID = title.post_id
				LEFT OUTER JOIN {$prefix}postmeta thumb ON wp.ID = thumb.post_id AND thumb.meta_key = '_thumbnail_id'
				LEFT OUTER JOIN {$prefix}postmeta archiveTitle ON title.post_id = archiveTitle.post_id AND archiveTitle.meta_key = 'book_review_archive_title'
				LEFT OUTER JOIN {$prefix}postmeta author ON title.post_id = author.post_id AND author.meta_key = 'book_review_author'
				LEFT OUTER JOIN {$prefix}postmeta archive ON title.post_id = archive.post_id AND archive.meta_key = 'book_review_archive_post'
				WHERE title.meta_key = 'book_review_title' AND title.meta_value <> '' AND wp.post_status =  'publish'
				ORDER BY title";
		}
		else if ($type == 'genre') {
			$query = "
				SELECT DISTINCT genre.post_id, thumb.meta_value AS thumb, IFNULL(archive.meta_value, 1) AS archivePost, IFNULL(archiveTitle.meta_value, title.meta_value) AS title, author.meta_value AS author, genre.meta_value AS genre
				FROM {$prefix}posts wp
				INNER JOIN {$prefix}postmeta genre ON wp.ID = genre.post_id
				LEFT OUTER JOIN {$prefix}postmeta thumb ON wp.ID = thumb.post_id AND thumb.meta_key = '_thumbnail_id'
				LEFT OUTER JOIN {$prefix}postmeta title ON genre.post_id = title.post_id AND title.meta_key = 'book_review_title'
				LEFT OUTER JOIN {$prefix}postmeta archiveTitle ON genre.post_id = archiveTitle.post_id AND archiveTitle.meta_key = 'book_review_archive_title'
				LEFT OUTER JOIN {$prefix}postmeta author ON genre.post_id = author.post_id AND author.meta_key = 'book_review_author'
				LEFT OUTER JOIN {$prefix}postmeta archive ON genre.post_id = archive.post_id AND archive.meta_key = 'book_review_archive_post'
				WHERE genre.meta_key = 'book_review_genre' AND genre.meta_value <> '' AND wp.post_status =  'publish'
				ORDER BY genre, title";
		}

		$results = $wpdb->get_results($query);
		$html[] = '<div class="book-review-archives">';
		$size = $this->get_thumbnail_size();

		foreach($results as $result) {
			//Only include this post if it has been flagged to be shown in the archive.
			if ($result->archivePost == 1) {
				$thumb = wp_get_attachment_image($result->thumb, 'thumbnail');

				//No featured image. Use the cover URL with maximum dimensions set to be the thumbnail size from the Media Settings.
				if ($thumb == '') {
					//This is faster than adding to the main query. Consider using this approach for other fields instead of adding them to the main query.
					$values = get_post_custom_values('book_review_cover_url', $result->post_id);
					$cover_url = $values[0];
					$thumb = '<a href="' . get_permalink($result->post_id) . '"><img src="' . $cover_url . '" style="max-width:' . $size[0] . 'px; max-height:' . $size[1] . 'px;" /></a>';
				}
				else {
					$url = wp_get_attachment_image_src($result->thumb, 'thumbnail');
					$thumb = '<a href="' . get_permalink($result->post_id) . '">' . $thumb . '</a>';
				}


				if ($type == 'title') {
					//Get first letter of title.
					$current = strtoupper(substr($result->title, 0, 1));

					if (isset($previous) && ($current != $previous)) {
						//Check if both titles start with a number. In that case, don't end the list.
						if (is_numeric($current) && is_numeric($previous)) {
							//Do nothing.
						}
						else {
							$html[] = '</ul>';
						}
					}

					if ($current != $previous) {
						//Check if both titles start with a number. In that case, don't create a new heading.
						if (is_numeric($current)) {
							if (is_numeric($previous)) {
								//Do nothing.
							}
							else {
								$html[] = '<h4 class="header">#</h4>';

								if ($show_cover == 'true') {
									$html[] = '<ul class="thumbs">';
								}
								else {
									$html[] = '<ul>';
								}
							}
						}
						else {
							$html[] = '<h4 class="header">' . $current . '</h4>';

							if ($show_cover == 'true') {
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

					if (isset($previous) && ($current != $previous)) {
						$html[] = '</ul>';
					}

					if ($current != $previous) {
						$html[] = '<h4 class="header">' . $current . '</h4>';

						if ($show_cover == 'true') {
							$html[] = '<ul class="thumbs">';
						}
						else {
							$html[] = '<ul>';
						}
					}
				}

				$html[] = '<li>';

				//Cover
				if ($show_cover == 'true') {
					if ($thumb != '') {
						$html[] .= $thumb;
					}
				}

				//Title and author
				if ($show_cover == 'true') {
					$html[] = '<h5 class="title"><a href="'. get_permalink($result->post_id) . '">' . $result->title . '</a></h5>';

					if (!empty($result->author)) {
						$html[] = '<p>by ' . $result->author . '</p>';
					}
				}
				else {
					$html[] = '<p><a href="'. get_permalink($result->post_id) . '">' . $result->title . '</a>';

					if (!empty($result->author)) {
						$html[] = 'by ' . $result->author;
					}
				}

				//Rating
				if ($show_rating == 'true') {
					$values = get_post_custom_values('book_review_rating', $result->post_id);
					$ratings = get_option('book_review_ratings');
					$rating = $values[0];

					if (!empty($rating) && ($rating != -1)) {
						if ($ratings['book_review_rating_default'] == "1") {
							if ($rating == "1") {
								$src = plugins_url('assets/one-star.png', dirname(__FILE__) );
							}
							else if ($rating == "2") {
								$src = plugins_url('assets/two-star.png', dirname(__FILE__) );
							}
							else if ($rating == "3") {
								$src = plugins_url('assets/three-star.png', dirname(__FILE__) );
							}
							else if ($rating == "4") {
								$src = plugins_url('assets/four-star.png', dirname(__FILE__) );
							}
							else if ($rating == "5") {
								$src = plugins_url('assets/five-star.png', dirname(__FILE__) );
							}
						}
						else {
							$src = $ratings['book_review_rating_image' . $rating];
						}

						if ($show_cover == 'true') {
							$html[] = '<p><img src="' . $src . '" /></p>';
						}
						else {
							$html[] = '<img src="' . $src . '" />';
						}
					}
				}

				if ($show_cover == 'false') {
					$html[] = '</p>';
				}

				$html[] = '</li>';

				$previous = $current;
			}
		}

		$html[] = '</div>';

		return implode("\n", $html);
	}

	/**
	 * Get the size of a book cover's thumbnail.
	 *
	 * @since    1.3
	 */
	private function get_thumbnail_size() {
		$dimensions = array();

		foreach(get_intermediate_image_sizes() as $s) {
			if (in_array($s, array('thumbnail'))) {
				$dimensions[0] = get_option($s . '_size_w');
				$dimensions[1] = get_option($s . '_size_h');
				break;
			}
		}

		return $dimensions;
	}

	/**
	 * Construct the URL of the rating image.
	 *
	 * @since    1.9
	 */
	public function get_rating_image($rating) {
		$ratings = get_option('book_review_ratings');

		if ($ratings['book_review_rating_default'] == "1") {
			if ($rating == "1") {
				$src = plugins_url('assets/one-star.png', dirname(__FILE__));
			}
			else if ($rating == "2") {
				$src = plugins_url('assets/two-star.png', dirname(__FILE__));
			}
			else if ($rating == "3") {
				$src = plugins_url('assets/three-star.png', dirname(__FILE__));
			}
			else if ($rating == "4") {
				$src = plugins_url('assets/four-star.png', dirname(__FILE__));
			}
			else if ($rating == "5") {
				$src = plugins_url('assets/five-star.png', dirname(__FILE__));
			}
		}
		else {
			$src = $ratings['book_review_rating_image' . $rating];
		}

		return $src;
	}
}
?>