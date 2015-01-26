<?php

// TODO: Test hooks are created.
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
  public function testRatingFileLoads() {
    $this->assertFileExists( BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-rating.php' );
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

  //public function testActivatorHooks() {
    // Use tests_add_filter to hook into an action or filter before it gets called.
    //global $wp_filter;

    //$this->assertarrayHasKey( 'activate_new_site', $wp_filter['wpmu_new_blog'][10] );
    // The ID changes every time.
    //$this->assertarrayHasKey( 'activate_new_site', $wp_filter['wpmu_new_blog'][10]['000000000e577996000000005a8a8ccdactivate_new_site]'] );
  //}
}

