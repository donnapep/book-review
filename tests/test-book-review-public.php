<?php

class Book_Review_Public_Tests extends WP_UnitTestCase {
  protected $plugin_name;
  protected $plugin_public;
  protected $plugin_meta;

  public function setup() {
    global $wpdb;

    parent::setUp();

    $plugin = run_book_review();
    $this->plugin_name = $plugin->get_plugin_name();
    $this->plugin_public = new Book_Review_Public( $this->plugin_name, $plugin->get_version() );
    $this->plugin_meta = new Book_Review_Meta_Box( $this->plugin_name );

    $this->suppress = $wpdb->suppress_errors();
  }

  public function tearDown() {
    global $wpdb;

    parent::tearDown();

    $wpdb->suppress_errors( $this->suppress );
  }

  /**
   * @covers Book_Review_Public::enqueue_styles
   */
  public function testStyleIsLoaded() {
    $this->assertFalse( wp_style_is( $this->plugin_name ) );

    do_action( 'wp_enqueue_scripts' );

    $this->assertTrue( wp_style_is( $this->plugin_name ) );
  }

  private function addPostMeta() {
    $_POST['book_review_isbn'] = '0525478817';
    $_POST['book_review_title'] = 'The Fault in Our Stars';
    $_POST['book_review_series'] = 'None';
    $_POST['book_review_author'] = 'John Green';
    $_POST['book_review_genre'] = 'Young Adult';
    $_POST['book_review_publisher'] = 'Dutton Books';
    $_POST['book_review_release_date'] = 'January 12, 2012';
    $_POST['book_review_format'] = 'Paperback';
    $_POST['book_review_pages'] = '313';
    $_POST['book_review_source'] = 'Purchased';
    $_POST['book_review_cover_url'] = 'http://example.com/wp-content/uploads/2014/04/The_Fault_in_Our_Stars_Book_Cover.jpg';
    $_POST['book_review_summary'] = 'Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis.';
    $_POST['book_review_rating'] = '4';
    $_POST['book_review_archive_post'] = '1';
    $_POST['book-review-meta-box-nonce'] = wp_create_nonce( 'save_meta_box_nonce' );
  }
}