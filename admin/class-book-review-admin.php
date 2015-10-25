<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wpreviewplugins.com/
 * @since      1.0.0
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Book_Review
 * @subpackage Book_Review/admin
 * @author     Donna Peplinskie <support@wpreviewplugins.com>
 */

class Book_Review_Admin {
  /**
   * The ID of this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    2.1.8
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    2.1.8
   * @var      string    $plugin_name       The name of this plugin.
   * @var      string    $version           The version of this plugin.
   */
  public function __construct( $plugin_name, $version ) {
    global $wpdb;

    $this->plugin_name = $plugin_name;
    $this->version = $version;
    $wpdb->book_review_custom_links = "{$wpdb->prefix}book_review_custom_links";
  }

  /**
   * Register the stylesheets for the Dashboard.
   *
   * @since    2.1.8
   *
   * @param    string    $hook_suffix    Page hook.
   */
  public function enqueue_styles( $hook_suffix ) {
    /**
     * An instance of this class should be passed to the run() function
     * defined in Book_Review_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Book_Review_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    if ( !isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    if ( $hook_suffix == 'edit.php' ) {
      $screen = get_current_screen();

      // Check that this is the Posts admin page.
      if ( $screen->post_type == 'post' ) {
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-posts-admin.css', array(), $this->version, 'all' );
      }
    }
    else if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-meta-box.css', array(), $this->version, 'all' );
    }
    else if ( $hook_suffix == 'plugins.php' ) {
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-admin-notice.css', array(), $this->version, 'all' );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/book-review-admin.css', array(), $this->version, 'all' );
    }
  }

  /**
   * Register the JavaScript for the dashboard.
   *
   * @since    2.1.8
   *
   * @param    string    $hook_suffix    Page hook.
   */
  public function enqueue_scripts( $hook_suffix ) {
    /**
     * An instance of this class should be passed to the run() function
     * defined in Book_Review_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Book_Review_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */
    if ( !isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    if ( $hook_suffix == 'post-new.php' || $hook_suffix == 'post.php' ) {
      wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-review-admin-meta-box.js', array( 'jquery' ), $this->version, false );
      wp_enqueue_script( 'jquery-ui-spinner' );

      $translation_array = array(
        'no_isbn' => esc_html__( 'Please enter an ISBN.', $this->plugin_name ),
        'not_found' => esc_html__( 'A book with this ISBN was not found in the Google Books database.', $this->plugin_name ),
        'unknown_error' => sprintf( __( '<p>Sorry, but something went wrong. Please check to ensure that you have entered your Google API Key correctly on the <em>Advanced</em> tab of the <a href="%s">Book Review Settings</a>, and that you have selected a <em>Country</em> from the dropdown.</p><p>Please also check to ensure that the correct IP address of your server has been entered into the <a href="%s" target="_blank">Google Developers Console</a>. See the <a href="%s" target="_blank">documentation</a> for more information.</p><p>If you are still having trouble, please leave a message in the <a href="%s" target="_blank">General Support forum</a>. Be sure to include the URL of your web site in your post. Thanks!', $this->plugin_name ), esc_url( admin_url( 'options-general.php?page=' . $this->plugin_name ) . '&tab=advanced' ), esc_url( 'https://code.google.com/apis/console' ), esc_url( 'http://wpreviewplugins.com/documentation/settings-advanced/' ), esc_url( 'http://wpreviewplugins.com/support/forum/general-support/' ) ) );
      wp_localize_script( $this->plugin_name, 'book_review_google_api', $translation_array );
    }
    else if ( $hook_suffix == $this->plugin_screen_hook_suffix ) {
       wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/book-review-admin.js',
        array( 'jquery', 'wp-color-picker' ), $this->version, false );
    }
  }

  /**
   * Register the administration menu for this plugin into the WordPress
   * Dashboard menu.
   *
   * @since    2.0.0
   */
  public function add_plugin_admin_menu() {
    $this->plugin_screen_hook_suffix = add_options_page(
      esc_html__( 'Book Review Settings', $this->plugin_name ),
      esc_html__( 'Book Review', $this->plugin_name ),
      'manage_options',
      $this->plugin_name,
      array( $this, 'display_plugin_admin_page' )
    );
  }

  /**
   * Render the settings page for this plugin.
   *
   * @since    2.0.0
   */
  public function display_plugin_admin_page() {
    include_once( 'partials/book-review-admin-tabs.php' );
  }

  /**
   * Add tabbed navigation.
   *
   * @since    2.1.6
   */
  public function render_tabs( ) {
    $tabs = apply_filters( 'book_review_tabs', array(
      'appearance' => __( 'Appearance', $this->plugin_name ),
      'images' => __( 'Rating Images', $this->plugin_name ),
      'links' => __( 'Links', $this->plugin_name ),
      'advanced' => __( 'Advanced', $this->plugin_name )
    ) );

    if ( isset ( $_GET['tab'] ) ) {
      $active_tab = $_GET['tab'];
    }
    else {
      $active_tab = 'appearance';
    }

    foreach( $tabs as $tab => $name ) {
      $class = ( $tab == $active_tab ) ? ' nav-tab-active' : '';

      echo '<a class="' . esc_attr( 'nav-tab' . $class ) . '" href="' . esc_url( '?page=book-review&tab=' . $tab )
        . '">' . esc_html( $name ) . '</a>';
    }
  }

  /**
   * Display the content for a particular tab.
   *
   * @since    2.1.4
   */
  public function render_tabbed_content() {
    if ( isset ( $_GET['tab'] ) ) {
      $active_tab = $_GET['tab'];
    }
    else {
      $active_tab = 'appearance';
    }

    do_action( 'book_review_before_tabs' );

    if ( $active_tab == 'appearance' ) {
      $general_defaults = array(
        'book_review_box_position' => 'top',
        'book_review_bg_color' => '',
        'book_review_border_color' => '',
        'book_review_border_width' => '1',
        'book_review_post_types' => array(
          'post' => '1'
        )
      );

      $general = get_option( 'book_review_general' );
      $general = wp_parse_args( $general, $general_defaults );

      // Post Types
      if ( isset( $general['book_review_post_types'] ) ) {
        $general['book_review_post_types'] = wp_parse_args( $general['book_review_post_types'], $general_defaults['book_review_post_types'] );
      }
      else {
        $general['book_review_post_types'] = $general_defaults['book_review_post_types'];
      }

      $post_types = $this->get_post_types();
      $keys = array_keys( $post_types );

      include_once( 'partials/book-review-admin-appearance.php' );
    }
    else if ( $active_tab == 'images' ) {
      $ratings_defaults = array(
        'book_review_rating_home' => 0,
        'book_review_rating_default' => 1
      );
      $ratings = get_option( 'book_review_ratings' );
      $ratings = wp_parse_args( $ratings, $ratings_defaults );

      include_once( 'partials/book-review-admin-images.php' );
    }
    else if ( $active_tab == 'links' ) {
      $links_defaults = array(
        'book_review_target' => 0,
      );
      $links_option = get_option( 'book_review_links', $links_defaults );
      $links_option = wp_parse_args( $links_option, $links_defaults );

      // Get custom links.
      global $wpdb;

      $results = $wpdb->get_results( "SELECT * FROM {$wpdb->book_review_custom_links}" );

      include_once( 'partials/book-review-admin-links.php' );
    }
    else if ( $active_tab == 'advanced' ) {
      $advanced = get_option( 'book_review_advanced' );

      include_once( 'partials/book-review-admin-advanced.php' );
    }

    do_action( 'book_review_after_tabs' );
  }

  /**
   * Returns the post types to show on the Appearance tab.
   *
   * @since     2.2.0
   */
  private function get_post_types() {
    $args = array(
      'public' => true
    );

    $post_types = get_post_types( $args, 'objects' );

    // Exclude media.
    unset( $post_types['attachment'] );

    return $post_types;
  }

  /**
   * Returns the countries.
   *
   * @since     2.2.0
   */
  private function get_countries() {
    return array(
      ''   => '',
      'US' => esc_html__( 'United States' ),
      'CA' => esc_html__( 'Canada' ),
      'GB' => esc_html__( 'United Kingdom' ),
      'AF' => esc_html__( 'Afghanistan' ),
      'AX' => esc_html__( '&#197;land Islands' ),
      'AL' => esc_html__( 'Albania' ),
      'DZ' => esc_html__( 'Algeria' ),
      'AS' => esc_html__( 'American Samoa' ),
      'AD' => esc_html__( 'Andorra' ),
      'AO' => esc_html__( 'Angola' ),
      'AI' => esc_html__( 'Anguilla' ),
      'AQ' => esc_html__( 'Antarctica' ),
      'AG' => esc_html__( 'Antigua and Barbuda' ),
      'AR' => esc_html__( 'Argentina' ),
      'AM' => esc_html__( 'Armenia' ),
      'AW' => esc_html__( 'Aruba' ),
      'AU' => esc_html__( 'Australia' ),
      'AT' => esc_html__( 'Austria' ),
      'AZ' => esc_html__( 'Azerbaijan' ),
      'BS' => esc_html__( 'Bahamas' ),
      'BH' => esc_html__( 'Bahrain' ),
      'BD' => esc_html__( 'Bangladesh' ),
      'BB' => esc_html__( 'Barbados' ),
      'BY' => esc_html__( 'Belarus' ),
      'BE' => esc_html__( 'Belgium' ),
      'BZ' => esc_html__( 'Belize' ),
      'BJ' => esc_html__( 'Benin' ),
      'BM' => esc_html__( 'Bermuda' ),
      'BT' => esc_html__( 'Bhutan' ),
      'BO' => esc_html__( 'Bolivia' ),
      'BQ' => esc_html__( 'Bonaire, Saint Eustatius and Saba' ),
      'BA' => esc_html__( 'Bosnia and Herzegovina' ),
      'BW' => esc_html__( 'Botswana' ),
      'BV' => esc_html__( 'Bouvet Island' ),
      'BR' => esc_html__( 'Brazil' ),
      'IO' => esc_html__( 'British Indian Ocean Territory' ),
      'BN' => esc_html__( 'Brunei Darrussalam' ),
      'BG' => esc_html__( 'Bulgaria' ),
      'BF' => esc_html__( 'Burkina Faso' ),
      'BI' => esc_html__( 'Burundi' ),
      'KH' => esc_html__( 'Cambodia' ),
      'CM' => esc_html__( 'Cameroon' ),
      'CV' => esc_html__( 'Cape Verde' ),
      'KY' => esc_html__( 'Cayman Islands' ),
      'CF' => esc_html__( 'Central African Republic' ),
      'TD' => esc_html__( 'Chad' ),
      'CL' => esc_html__( 'Chile' ),
      'CN' => esc_html__( 'China' ),
      'CX' => esc_html__( 'Christmas Island' ),
      'CC' => esc_html__( 'Cocos Islands' ),
      'CO' => esc_html__( 'Colombia' ),
      'KM' => esc_html__( 'Comoros' ),
      'CD' => esc_html__( 'Congo, Democratic People\'s Republic' ),
      'CG' => esc_html__( 'Congo, Republic of' ),
      'CK' => esc_html__( 'Cook Islands' ),
      'CR' => esc_html__( 'Costa Rica' ),
      'CI' => esc_html__( 'Cote d\'Ivoire' ),
      'HR' => esc_html__( 'Croatia/Hrvatska' ),
      'CU' => esc_html__( 'Cuba' ),
      'CW' => esc_html__( 'Cura&Ccedil;ao' ),
      'CY' => esc_html__( 'Cyprus' ),
      'CZ' => esc_html__( 'Czech Republic' ),
      'DK' => esc_html__( 'Denmark' ),
      'DJ' => esc_html__( 'Djibouti' ),
      'DM' => esc_html__( 'Dominica' ),
      'DO' => esc_html__( 'Dominican Republic' ),
      'TP' => esc_html__( 'East Timor' ),
      'EC' => esc_html__( 'Ecuador' ),
      'EG' => esc_html__( 'Egypt' ),
      'GQ' => esc_html__( 'Equatorial Guinea' ),
      'SV' => esc_html__( 'El Salvador' ),
      'ER' => esc_html__( 'Eritrea' ),
      'EE' => esc_html__( 'Estonia' ),
      'ET' => esc_html__( 'Ethiopia' ),
      'FK' => esc_html__( 'Falkland Islands' ),
      'FO' => esc_html__( 'Faroe Islands' ),
      'FJ' => esc_html__( 'Fiji' ),
      'FI' => esc_html__( 'Finland' ),
      'FR' => esc_html__( 'France' ),
      'GF' => esc_html__( 'French Guiana' ),
      'PF' => esc_html__( 'French Polynesia' ),
      'TF' => esc_html__( 'French Southern Territories' ),
      'GA' => esc_html__( 'Gabon' ),
      'GM' => esc_html__( 'Gambia' ),
      'GE' => esc_html__( 'Georgia' ),
      'DE' => esc_html__( 'Germany' ),
      'GR' => esc_html__( 'Greece' ),
      'GH' => esc_html__( 'Ghana' ),
      'GI' => esc_html__( 'Gibraltar' ),
      'GL' => esc_html__( 'Greenland' ),
      'GD' => esc_html__( 'Grenada' ),
      'GP' => esc_html__( 'Guadeloupe' ),
      'GU' => esc_html__( 'Guam' ),
      'GT' => esc_html__( 'Guatemala' ),
      'GG' => esc_html__( 'Guernsey' ),
      'GN' => esc_html__( 'Guinea' ),
      'GW' => esc_html__( 'Guinea-Bissau' ),
      'GY' => esc_html__( 'Guyana' ),
      'HT' => esc_html__( 'Haiti' ),
      'HM' => esc_html__( 'Heard and McDonald Islands' ),
      'VA' => esc_html__( 'Holy See (City Vatican State)' ),
      'HN' => esc_html__( 'Honduras' ),
      'HK' => esc_html__( 'Hong Kong' ),
      'HU' => esc_html__( 'Hungary' ),
      'IS' => esc_html__( 'Iceland' ),
      'IN' => esc_html__( 'India' ),
      'ID' => esc_html__( 'Indonesia' ),
      'IR' => esc_html__( 'Iran' ),
      'IQ' => esc_html__( 'Iraq' ),
      'IE' => esc_html__( 'Ireland' ),
      'IM' => esc_html__( 'Isle of Man' ),
      'IL' => esc_html__( 'Israel' ),
      'IT' => esc_html__( 'Italy' ),
      'JM' => esc_html__( 'Jamaica' ),
      'JP' => esc_html__( 'Japan' ),
      'JE' => esc_html__( 'Jersey' ),
      'JO' => esc_html__( 'Jordan' ),
      'KZ' => esc_html__( 'Kazakhstan' ),
      'KE' => esc_html__( 'Kenya' ),
      'KI' => esc_html__( 'Kiribati' ),
      'KW' => esc_html__( 'Kuwait' ),
      'KG' => esc_html__( 'Kyrgyzstan' ),
      'LA' => esc_html__( 'Lao People\'s Democratic Republic' ),
      'LV' => esc_html__( 'Latvia' ),
      'LB' => esc_html__( 'Lebanon' ),
      'LS' => esc_html__( 'Lesotho' ),
      'LR' => esc_html__( 'Liberia' ),
      'LY' => esc_html__( 'Libyan Arab Jamahiriya' ),
      'LI' => esc_html__( 'Liechtenstein' ),
      'LT' => esc_html__( 'Lithuania' ),
      'LU' => esc_html__( 'Luxembourg' ),
      'MO' => esc_html__( 'Macau' ),
      'MK' => esc_html__( 'Macedonia' ),
      'MG' => esc_html__( 'Madagascar' ),
      'MW' => esc_html__( 'Malawi' ),
      'MY' => esc_html__( 'Malaysia' ),
      'MV' => esc_html__( 'Maldives' ),
      'ML' => esc_html__( 'Mali' ),
      'MT' => esc_html__( 'Malta' ),
      'MH' => esc_html__( 'Marshall Islands' ),
      'MQ' => esc_html__( 'Martinique' ),
      'MR' => esc_html__( 'Mauritania' ),
      'MU' => esc_html__( 'Mauritius' ),
      'YT' => esc_html__( 'Mayotte' ),
      'MX' => esc_html__( 'Mexico' ),
      'FM' => esc_html__( 'Micronesia' ),
      'MD' => esc_html__( 'Moldova, Republic of' ),
      'MC' => esc_html__( 'Monaco' ),
      'MN' => esc_html__( 'Mongolia' ),
      'ME' => esc_html__( 'Montenegro' ),
      'MS' => esc_html__( 'Montserrat' ),
      'MA' => esc_html__( 'Morocco' ),
      'MZ' => esc_html__( 'Mozambique' ),
      'MM' => esc_html__( 'Myanmar' ),
      'NA' => esc_html__( 'Namibia' ),
      'NR' => esc_html__( 'Nauru' ),
      'NP' => esc_html__( 'Nepal' ),
      'NL' => esc_html__( 'Netherlands' ),
      'AN' => esc_html__( 'Netherlands Antilles' ),
      'NC' => esc_html__( 'New Caledonia' ),
      'NZ' => esc_html__( 'New Zealand' ),
      'NI' => esc_html__( 'Nicaragua' ),
      'NE' => esc_html__( 'Niger' ),
      'NG' => esc_html__( 'Nigeria' ),
      'NU' => esc_html__( 'Niue' ),
      'NF' => esc_html__( 'Norfolk Island' ),
      'KR' => esc_html__( 'North Korea' ),
      'MP' => esc_html__( 'Northern Mariana Islands' ),
      'NO' => esc_html__( 'Norway' ),
      'OM' => esc_html__( 'Oman' ),
      'PK' => esc_html__( 'Pakistan' ),
      'PW' => esc_html__( 'Palau' ),
      'PS' => esc_html__( 'Palestinian Territories' ),
      'PA' => esc_html__( 'Panama' ),
      'PG' => esc_html__( 'Papua New Guinea' ),
      'PY' => esc_html__( 'Paraguay' ),
      'PE' => esc_html__( 'Peru' ),
      'PH' => esc_html__( 'Phillipines' ),
      'PN' => esc_html__( 'Pitcairn Island' ),
      'PL' => esc_html__( 'Poland' ),
      'PT' => esc_html__( 'Portugal' ),
      'PR' => esc_html__( 'Puerto Rico' ),
      'QA' => esc_html__( 'Qatar' ),
      'XK' => esc_html__( 'Republic of Kosovo' ),
      'RE' => esc_html__( 'Reunion Island' ),
      'RO' => esc_html__( 'Romania' ),
      'RU' => esc_html__( 'Russian Federation' ),
      'RW' => esc_html__( 'Rwanda' ),
      'BL' => esc_html__( 'Saint Barth&eacute;lemy' ),
      'SH' => esc_html__( 'Saint Helena' ),
      'KN' => esc_html__( 'Saint Kitts and Nevis' ),
      'LC' => esc_html__( 'Saint Lucia' ),
      'MF' => esc_html__( 'Saint Martin (French)' ),
      'SX' => esc_html__( 'Saint Martin (Dutch)' ),
      'PM' => esc_html__( 'Saint Pierre and Miquelon' ),
      'VC' => esc_html__( 'Saint Vincent and the Grenadines' ),
      'SM' => esc_html__( 'San Marino' ),
      'ST' => esc_html__( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe' ),
      'SA' => esc_html__( 'Saudi Arabia' ),
      'SN' => esc_html__( 'Senegal' ),
      'RS' => esc_html__( 'Serbia' ),
      'SC' => esc_html__( 'Seychelles' ),
      'SL' => esc_html__( 'Sierra Leone' ),
      'SG' => esc_html__( 'Singapore' ),
      'SK' => esc_html__( 'Slovak Republic' ),
      'SI' => esc_html__( 'Slovenia' ),
      'SB' => esc_html__( 'Solomon Islands' ),
      'SO' => esc_html__( 'Somalia' ),
      'ZA' => esc_html__( 'South Africa' ),
      'GS' => esc_html__( 'South Georgia' ),
      'KP' => esc_html__( 'South Korea' ),
      'SS' => esc_html__( 'South Sudan' ),
      'ES' => esc_html__( 'Spain' ),
      'LK' => esc_html__( 'Sri Lanka' ),
      'SD' => esc_html__( 'Sudan' ),
      'SR' => esc_html__( 'Suriname' ),
      'SJ' => esc_html__( 'Svalbard and Jan Mayen Islands' ),
      'SZ' => esc_html__( 'Swaziland' ),
      'SE' => esc_html__( 'Sweden' ),
      'CH' => esc_html__( 'Switzerland' ),
      'SY' => esc_html__( 'Syrian Arab Republic' ),
      'TW' => esc_html__( 'Taiwan' ),
      'TJ' => esc_html__( 'Tajikistan' ),
      'TZ' => esc_html__( 'Tanzania' ),
      'TH' => esc_html__( 'Thailand' ),
      'TL' => esc_html__( 'Timor-Leste' ),
      'TG' => esc_html__( 'Togo' ),
      'TK' => esc_html__( 'Tokelau' ),
      'TO' => esc_html__( 'Tonga' ),
      'TT' => esc_html__( 'Trinidad and Tobago' ),
      'TN' => esc_html__( 'Tunisia' ),
      'TR' => esc_html__( 'Turkey' ),
      'TM' => esc_html__( 'Turkmenistan' ),
      'TC' => esc_html__( 'Turks and Caicos Islands' ),
      'TV' => esc_html__( 'Tuvalu' ),
      'UG' => esc_html__( 'Uganda' ),
      'UA' => esc_html__( 'Ukraine' ),
      'AE' => esc_html__( 'United Arab Emirates' ),
      'UY' => esc_html__( 'Uruguay' ),
      'UM' => esc_html__( 'US Minor Outlying Islands' ),
      'UZ' => esc_html__( 'Uzbekistan' ),
      'VU' => esc_html__( 'Vanuatu' ),
      'VE' => esc_html__( 'Venezuela' ),
      'VN' => esc_html__( 'Vietnam' ),
      'VG' => esc_html__( 'Virgin Islands (British)' ),
      'VI' => esc_html__( 'Virgin Islands (USA)' ),
      'WF' => esc_html__( 'Wallis and Futuna Islands' ),
      'EH' => esc_html__( 'Western Sahara' ),
      'WS' => esc_html__( 'Western Samoa' ),
      'YE' => esc_html__( 'Yemen' ),
      'ZM' => esc_html__( 'Zambia' ),
      'ZW' => esc_html__( 'Zimbabwe' )
    );
  }

  /**
   * Render options in the Country dropdown.
   *
   * @since     2.1.14
   */
  private function add_countries() {
    $countries = $this->get_countries();
    $advanced = get_option( 'book_review_advanced' );
    $selected_country = isset( $advanced['book_review_country'] ) ? $advanced['book_review_country'] : '';

    foreach( $countries as $country_code => $country ) {
      echo '<option value="' . esc_attr( $country_code ) . '"' . selected( $country_code, $selected_country, false ) . '>'
        . $country
        . '</option>';
    }
  }

  /**
   * Add settings action link to the plugins page.
   *
   * @since    2.0.0
   */
  public function add_action_links( $links ) {
    return array_merge(
      array(
        'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=' .
          $this->plugin_name ) ) . '">' . esc_html__( 'Settings', $this->plugin_name ) . '</a>'
      ), $links);
  }

  /**
   * Register settings so that they will be saved.
   *
   * @since    1.0.0
   */
  public function init_menu() {
    register_setting( 'general_options', 'book_review_general', array( $this, 'sanitize_appearance' )  );
    register_setting( 'ratings_options', 'book_review_ratings', array( $this, 'sanitize_rating_images' ) );
    register_setting( 'links_options', 'book_review_links', array( $this, 'sanitize_links' ) );
    register_setting( 'advanced_options', 'book_review_advanced', array( $this, 'sanitize_advanced' ) );
  }

  /**
   * Sanitize fields on the Appearance tab.
   *
   * @since     2.1.9
   */
  public function sanitize_appearance( $input ) {
    $output = array();

    $output['book_review_box_position'] = isset( $input['book_review_box_position'] ) ?
      $input['book_review_box_position'] : 'top';
    $output['book_review_bg_color'] = isset( $input['book_review_bg_color'] ) ?
      $input['book_review_bg_color'] : '';
    $output['book_review_border_color'] = isset( $input['book_review_border_color'] ) ?
      $input['book_review_border_color'] : '';

    // Sanitize border width.
    if ( isset( $input['book_review_border_width'] ) ) {
      $input['book_review_border_width'] = trim( $input['book_review_border_width'] );
      $output['book_review_border_width'] = intval( $input['book_review_border_width'] );

      if ( $input['book_review_border_width'] != '0' ) {
        // Value is empty or a string.
        if ( intval( $input['book_review_border_width'] ) == 0 ) {
          add_settings_error(
            'book_review_appearance',
            'border-width-error',
            esc_html__( 'Review Box Border Width must be numeric.', $this->plugin_name )
          );
        }
      }
    }
    else {
      $output['book_review_border_width'] = 0;
    }

    // Post Types - Only checked boxes are posted.
    $allowed_post_types = array_keys( $this->get_post_types() );

    foreach ( $allowed_post_types as $allowed_post_type ) {
      if ( isset( $input['book_review_post_types'][$allowed_post_type] ) ) {
        $output['book_review_post_types'][$allowed_post_type] = '1';
      }
      else {
        $output['book_review_post_types'][$allowed_post_type] = '0';
      }
    }

    return $output;
  }

  /**
   * Sanitize rating image URLs.
   *
   * @since     1.0.0
   */
  public function sanitize_rating_images( $input ) {
    $image_error = false;
    $output = array();
    $output['book_review_rating_home'] = isset( $input['book_review_rating_home'] ) ? $input['book_review_rating_home'] : '';
    $output['book_review_rating_default'] = isset( $input['book_review_rating_default'] ) ? $input['book_review_rating_default'] : '';

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
        esc_html__( 'Rating Image URLs are required fields when not using the default rating images. Please ensure you enter a URL for each rating.', $this->plugin_name )
      );
    }

    return $output;
  }

  /**
   * Sanitize link image URLs.
   *
   * @since     1.0.0
   */
  public function sanitize_links( $input ) {
    $output = array();
    $output['book_review_target'] = isset( $input['book_review_target'] ) ? $input['book_review_target'] : '';

    if ( isset( $input ) ) {
      foreach ( $input as $key => $value ) {
        $error = false;

        // Custom Links
        if ( is_array( $value ) ) {
          $id = '';
          $text = '';
          $image_url = '';
          // An unchecked checkbox will not be POSTed and so its value will not be set.
          $active = 0;

          foreach( $value as $link_key => $link_value ) {
            if ( $link_key == 'id' ) {
              $id = sanitize_text_field( $link_value );
            }
            else if ( $link_key == 'text' ) {
              $text = sanitize_text_field( $link_value );

              // Link Text is a required field.
              if ( empty( $text ) ) {
                $error = true;
              }
            }
            else if ( $link_key == 'image' ) {
              $image_url = esc_url_raw( $link_value );
            }
            else if ( $link_key == 'active' ) {
              $active = (int)$link_value;
            }
          }

          if ( !$error ) {
            global $wpdb;

            // Insert a new row.
            if ( empty( $id ) ) {
              $wpdb->insert(
                $wpdb->book_review_custom_links,
                array(
                  'text' => $text,
                  'image_url' => $image_url,
                  'active' => $active
                ),
                array( '%s', '%s', '%d' )
              );
            }
            // Update the existing row.
            else {
              $wpdb->update(
                $wpdb->book_review_custom_links,
                array(
                  'text' => $text,
                  'image_url' => $image_url,
                  'active' => $active
                ),
                array( 'custom_link_id' => $id ),
                array( '%s', '%s', '%d' ),
                array( '%d' )
              );
            }
          }
          else {
            add_settings_error(
              'book_review_links',
              'link-error',
              esc_html__( 'Link Text is a required field. Please ensure you enter text for each link.', $this->plugin_name )
            );
          }
        }
      }
    }

    return $output;
  }

  /**
   * Sanitize fields on Advanced tab.
   *
   * @since     2.1.6
   */
  public function sanitize_advanced( $input = array() ) {
    $output = array();

    // API Key
    $output['book_review_api_key'] = isset( $input['book_review_api_key'] ) ?
      sanitize_text_field( $input['book_review_api_key'] ) : '';

    // Country
    $allowed_countries = array_keys( $this->get_countries() );

    if ( isset( $input['book_review_country'] ) && in_array( $input['book_review_country'], $allowed_countries ) ) {
      $output['book_review_country'] = $input['book_review_country'];
    }
    else {
      $output['book_review_country'] = '';
    }

    return $output;
  }

  /**
   * Add Rating column to Posts Admin screen.
   *
   * @since    1.9.0
   */
  public function column_heading( $columns ) {
    return array_merge( $columns, array( 'rating' => esc_html__( 'Rating', $this->plugin_name ) ) );
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
          echo '<img src="' . esc_url( $plugin->get_rating()->get_rating_image( $rating ) ) .
            '" class="book_review_column_rating">';
        }
      }
    }
  }
}
?>