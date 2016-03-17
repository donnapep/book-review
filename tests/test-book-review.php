<?php

class Book_Review_Tests extends WP_UnitTestCase {
  protected $object;

  public function setup() {
    parent::setUp();

    $this->object = run_book_review();
  }

  public function tearDown() {
    parent::tearDown();
  }

  public function testBookReviewInstance() {
    $this->assertClassHasStaticAttribute( 'instance', 'Book_Review' );
  }

  /**
   * @covers Book_Review::__construct
   */
  public function testBookReviewPluginDirConstant() {
    $path = plugin_dir_path( dirname( __FILE__ ) );
    $this->assertSame( BOOK_REVIEW_PLUGIN_DIR, $path );
  }

  /**
   * @covers Book_Review::__construct
   */
  public function testBookReviewPluginUrlConstant() {
    $url = plugin_dir_url( dirname( __FILE__ ) );
    $this->assertSame( BOOK_REVIEW_PLUGIN_URL, $url );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testActivatorFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testi18nFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-i18n.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testLoaderFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-loader.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testSettingsFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-settings.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testBookInfoFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-book-info.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testAdminNoticeFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'admin/class-book-review-admin-notice.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testAdminFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'admin/class-book-review-admin.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testMetaBoxFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'admin/class-book-review-meta-box.php' );
  }

  /**
   * @covers Book_Review::load_dependencies
   */
  public function testPublicFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'public/class-book-review-public.php' );
  }

  /** The only thing that can be done when testing hooks is to ensure that one has been added.
   *  It's not possible to check for the proper callback when using OOP.
   */

  // TODO: Check actions have been added.

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testPositionFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_box_position', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testBgColorFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_bg_color', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testBorderColorFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_border_color', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testBorderWidthFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_border_width', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testPostTypeFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_post_type', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingHomeFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_home', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingDefaultFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_default', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingImage1Filter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_image1', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingImage2Filter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_image2', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingImage3Filter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_image3', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingImage4Filter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_image4', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testRatingImage5Filter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_rating_image5', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testSiteLinkActiveFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_site_link_active', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testSiteLinkTypeFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_site_link_type', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testSiteLinkTextFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_site_link_text', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testSiteLinkUrlFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_site_link_url', $wp_filter );
  }

   /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testLinkIdFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_link_id', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testLinkTextFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_link_text', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testLinkUrlFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_link_url', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testLinkStatusFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_link_status', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testTargetFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_target', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testCustomFieldFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_custom_field', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testAPIKeyFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_api_key', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testCountryFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'sanitize_book_review_country', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testPluginActionsLinkFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'plugin_action_links_' . plugin_basename( plugin_dir_path( dirname(__FILE__) ) .
      'book-review.php' ), $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_admin_hooks
   */
  public function testManagePostsColumnsFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'manage_posts_columns', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_public_hooks
   */
  public function testTheExcerptFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'the_excerpt', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::define_public_hooks
   */
  public function testTheContentFilter() {
    global $wp_filter;

    $this->assertArrayHasKey( 'the_content', $wp_filter );
  }
}

