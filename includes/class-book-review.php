<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://donnapeplinskie.com
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
 * @author     Donna Peplinskie <donnapep@gmail.com>
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
    if ( null == self::$instance ) {
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
    $this->version = '2.1.10';

    if ( !defined( 'BOOK_REVIEW_PLUGIN_DIR' ) ) {
      define( 'BOOK_REVIEW_PLUGIN_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
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
   * - Book_Review_Rating. Defines functionality for the ratings tab.
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
     * The class responsible for defining all actions that relate to ratings.
     */
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-rating.php';

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

    // TODO: Or make these public properties instead?
    $this->loader = new Book_Review_Loader();
    $this->rating = new Book_Review_Rating( $this->get_plugin_name() );
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
    $plugin_admin = new Book_Review_Admin( $this->get_plugin_name(), $this->get_version() );

    // Actions
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
    $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
    $this->loader->add_action( 'admin_init', $plugin_admin, 'init_menu' );
    $this->loader->add_action( 'manage_posts_custom_column', $plugin_admin, 'column_content', 10, 2 );
    //$this->loader->add_action( 'wp_ajax_delete_link', $plugin_admin, 'delete_link' );

    // Filters
    $plugin_basename = plugin_basename( plugin_dir_path( dirname(__FILE__) ) . $this->get_plugin_name() . '.php' );
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
    $plugin_meta_box = new Book_Review_Meta_Box( $this->get_plugin_name() );

    // Actions
    $this->loader->add_action( 'load-post.php', $plugin_meta_box, 'meta_box_setup' );
    $this->loader->add_action( 'load-post-new.php', $plugin_meta_box, 'meta_box_setup' );
    $this->loader->add_action( 'wp_ajax_get_book_info', $plugin_meta_box, 'get_book_info' );

    // Filters
    $this->loader->add_filter( 'postbox_classes_post_book-review-meta-box', $plugin_meta_box, 'add_metabox_class' );
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    2.1.8
   * @access   private
   */
  private function define_public_hooks() {
    $plugin_public = new Book_Review_Public( $this->get_plugin_name(), $this->get_version() );

    // Actions
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );

    // Filters
    $this->loader->add_filter( 'the_excerpt', $plugin_public, 'inject_book_rating' );
    $this->loader->add_filter( 'the_content', $plugin_public, 'inject_book_details' );

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
   * @return    Book_Review_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader() {
    return $this->loader;
  }

  /**
   * The reference to the class that handles rating images.
   *
   * @since     2.1.8
   * @return    Book_Review_Loader    Handles rating images.
   */
  public function get_rating() {
    return $this->rating;
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