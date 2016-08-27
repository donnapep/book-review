<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wpreviewplugins.com/
 * @since      2.1.8
 *
 * @package    Book_Review
 * @subpackage Book_Review/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.1.8
 * @package    Book_Review
 * @subpackage Book_Review/includes
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
 */
class Book_Review {
  /**
   * Instance of this class.
   *
   * @since    2.1.8
   * @access   private
   * @var      object   $instance   A single instance of this class.
   */
  private static $instance = false;

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    2.1.8
   * @access   protected
   * @var      Book_Review_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    2.1.8
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    2.1.8
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   * @access    public
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {
    if ( self::$instance == null ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the Dashboard and
   * the public-facing side of the site.
   *
   * @since    2.1.8
   */
  private function __construct() {
    $this->plugin_name = 'book-review';
    $this->version = '2.3.8';

    if ( !defined( 'BOOK_REVIEW_PLUGIN_DIR' ) ) {
      define( 'BOOK_REVIEW_PLUGIN_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
    }

    if ( !defined( 'BOOK_REVIEW_PLUGIN_URL' ) ) {
      define( 'BOOK_REVIEW_PLUGIN_URL', plugin_dir_url( dirname( __FILE__ ) ) );
    }

    $this->load_dependencies();
    $this->set_locale();
    $this->define_activator_hooks();
    $this->define_admin_hooks();
    $this->define_meta_box_hooks();
    $this->define_public_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Book_Review_Activator. Defines plugin activation functionality.
   * - Book_Review_i18n. Defines internationalization functionality.
   * - Book_Review_Loader. Orchestrates the hooks of the plugin.
   * - Book_Review_Settings. Returns the settings.
   * - Book_Review_Book_Info. Returns information about a book.
   * - Book_Review_Admin. Defines all hooks for the dashboard.
   * - Book_Review_Meta_Box. Defines all hooks for the metabox.
   * - Book_Review_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    2.1.8
   * @access   private
   */
  private function load_dependencies() {
    /**
     * The class responsible for defining all actions that occur upon plugin activation.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php';

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-i18n.php';

    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-loader.php';

    /**
     * The class responsible for returning the settings.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-settings.php';

    /**
     * The class responsible for returning information about a book.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-book-info.php';

    /**
     * The class responsible for defining all actions that occur in the Dashboard.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'admin/class-book-review-admin.php';

    /**
     * The class responsible for defining all actions that relate to the meta box.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'admin/class-book-review-meta-box.php';

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'public/class-book-review-public.php';

    $this->loader = new Book_Review_Loader();
    $this->settings = new Book_Review_Settings();
    $this->book_info = new Book_Review_Book_Info( $this->get_plugin_name(), $this->get_settings() );
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Book_Review_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    2.1.8
   * @access   private
   */
  private function set_locale() {
    $plugin_i18n = new Book_Review_i18n();
    $plugin_i18n->set_domain( $this->get_plugin_name() );

    $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
  }

  private function define_activator_hooks() {
    global $wp_filter;

    $plugin_activator = new Book_Review_Activator();

    // Trigger an action whenever a new blog is created within a multisite network.
    $this->loader->add_action( 'wpmu_new_blog', $plugin_activator, 'activate_new_site' );
  }

  /**
   * Register all of the hooks related to the dashboard functionality
   * of the plugin.
   *
   * @since    2.1.8
   * @access   private
   */
  private function define_admin_hooks() {
    $plugin_admin = new Book_Review_Admin( $this->get_plugin_name(), $this->get_version(),
      $this->get_settings(), $this->get_book_info() );
    $plugin_basename = plugin_basename( plugin_dir_path( dirname(__FILE__) ) . $this->get_plugin_name() . '.php' );

    // Actions
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
    $this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'column_content', 10, 2 );

    // Filters
    // Sanitization
    $this->loader->add_filter( 'sanitize_book_review_box_position', $plugin_admin, 'sanitize_position' );
    $this->loader->add_filter( 'sanitize_book_review_bg_color', $plugin_admin, 'sanitize_color' );
    $this->loader->add_filter( 'sanitize_book_review_border_color', $plugin_admin, 'sanitize_color' );
    $this->loader->add_filter( 'sanitize_book_review_border_width', $plugin_admin, 'sanitize_border_width' );
    $this->loader->add_filter( 'sanitize_book_review_post_type', $plugin_admin, 'sanitize_post_type', 10, 2 );

    $this->loader->add_filter( 'sanitize_book_review_rating_home', $plugin_admin, 'sanitize_checkbox' );
    $this->loader->add_filter( 'sanitize_book_review_rating_default', $plugin_admin, 'sanitize_checkbox' );
    $this->loader->add_filter( 'sanitize_book_review_rating_image1', $plugin_admin, 'sanitize_rating_image', 10, 3 );
    $this->loader->add_filter( 'sanitize_book_review_rating_image2', $plugin_admin, 'sanitize_rating_image', 10, 3 );
    $this->loader->add_filter( 'sanitize_book_review_rating_image3', $plugin_admin, 'sanitize_rating_image', 10, 3 );
    $this->loader->add_filter( 'sanitize_book_review_rating_image4', $plugin_admin, 'sanitize_rating_image', 10, 3 );
    $this->loader->add_filter( 'sanitize_book_review_rating_image5', $plugin_admin, 'sanitize_rating_image', 10, 3 );

    // Site Links
    $this->loader->add_filter( 'sanitize_book_review_site_link_active', $plugin_admin, 'sanitize_checkbox' );
    $this->loader->add_filter( 'sanitize_book_review_site_link_type', $plugin_admin, 'sanitize_link_type' );
    $this->loader->add_filter( 'sanitize_book_review_site_link_text', $plugin_admin, 'sanitize_text' );
    $this->loader->add_filter( 'sanitize_book_review_site_link_url', $plugin_admin, 'sanitize_url' );

    // Custom Links
    $this->loader->add_filter( 'sanitize_book_review_link_id', $plugin_admin, 'sanitize_link_id' );
    $this->loader->add_filter( 'sanitize_book_review_link_text', $plugin_admin, 'sanitize_link_text' );
    $this->loader->add_filter( 'sanitize_book_review_link_url', $plugin_admin, 'sanitize_url' );
    $this->loader->add_filter( 'sanitize_book_review_link_status', $plugin_admin, 'sanitize_link_status' );

    // Links - General
    $this->loader->add_filter( 'sanitize_book_review_target', $plugin_admin, 'sanitize_checkbox' );

    // Custom Fields
    $this->loader->add_filter( 'sanitize_book_review_custom_field', $plugin_admin, 'sanitize_text' );

    $this->loader->add_filter( 'sanitize_book_review_api_key', $plugin_admin, 'sanitize_text' );
    $this->loader->add_filter( 'sanitize_book_review_country', $plugin_admin, 'sanitize_country' );

    // Add link to the list of links to display on the plugins page.
    $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
    $this->loader->add_filter( 'manage_posts_columns', $plugin_admin, 'column_heading' );
  }

  /**
   * Register all of the hooks related to the meta box functionality
   * of the plugin.
   *
   * @since    2.1.8
   * @access   private
   */
  private function define_meta_box_hooks() {
    $plugin_meta_box = new Book_Review_Meta_Box( $this->get_plugin_name(), $this->get_settings(), $this->get_book_info() );

    // Actions
    $this->loader->add_action( 'load-post.php', $plugin_meta_box, 'meta_box_setup' );
    $this->loader->add_action( 'load-post-new.php', $plugin_meta_box, 'meta_box_setup' );
    $this->loader->add_action( 'wp_ajax_get_book_info', $plugin_meta_box, 'get_book_info' );
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    2.1.8
   * @access   private
   */
  private function define_public_hooks() {
    $plugin_public = new Book_Review_Public( $this->get_plugin_name(), $this->get_version(),
      $this->get_settings(), $this->get_book_info() );

    // Actions
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

    // Filters
    $this->loader->add_filter( 'the_excerpt', $plugin_public, 'add_rating' );
    $this->loader->add_filter( 'the_content', $plugin_public, 'display_book_info' );

    // Shortcodes
    $this->loader->add_shortcode( 'book_review_archives', $plugin_public, 'handle_shortcode' );
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    2.1.8
   */
  public function run() {
    $this->loader->run();
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     2.1.8
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     2.1.8
   * @return    Book_Review_Loader    Orchestrates the plugin's hooks.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * The reference to the class that handles returning the settings.
   *
   * @since     2.3.0
   * @return    Book_Review_Settings    Returns the settings.
   */
  public function get_settings() {
    return $this->settings;
  }

  /**
   * The reference to the class that handles returning information about a book.
   *
   * @since     2.3.0
   * @return    Book_Review_Book_Info    Returns information about a book.
   */
  public function get_book_info() {
    return $this->book_info;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     2.1.8
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }
}