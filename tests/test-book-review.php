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
}

