<?php

class Book_Review_Public_Tests extends WP_UnitTestCase {
  protected $plugin_name;
  protected $plugin_public;
  protected $post_id;

  public function setup() {
    global $wpdb;
    global $wp_query;
    global $post;

    parent::setUp();

    $plugin = run_book_review();

    $this->plugin_name = $plugin->get_plugin_name();
    $this->plugin_public = new Book_Review_Public( $this->plugin_name, $plugin->get_version(),
      $plugin->get_settings(), $plugin->get_book_info() );

    $wp_query->is_home = true;
    $this->post_id = $this->factory->post->create();
    $post = get_post( $this->post_id );

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

  /**
   * @covers Book_Review_Public::display_book_info
   */
  public function testNoBookInfoWhenNoTitle() {
    global $wp_query;

    $wp_query->in_the_loop = true;

    set_post_type( $this->post_id, 'post' );

    $content = $this->plugin_public->display_book_info( '' );

    $this->assertEquals( '', $content );
  }

  /**
   * @covers Book_Review_Public::display_book_info
   */
  public function testBookInfoPostWhenPostTypesNotSet() {
    global $wp_query;

    $wp_query->in_the_loop = true;

    set_post_type( $this->post_id, 'post' );
    update_post_meta( $this->post_id, 'book_review_title', 'The Fault in Our Stars' );

    $content = $this->plugin_public->display_book_info( '' );

    $this->assertNotEquals( '', $content );
  }

  /**
   * @covers Book_Review_Public::display_book_info
   */
  public function testBookInfoCPTWhenPostTypesNotSet() {
    global $wp_query;

    $wp_query->in_the_loop = true;

    set_post_type( $this->post_id, 'documentation' );
    update_post_meta( $this->post_id, 'book_review_title', 'The Fault in Our Stars' );

    $content = $this->plugin_public->display_book_info( '' );

    $this->assertNotEquals( '', $content );
  }

  /**
   * @covers Book_Review_Public::display_book_info
   */
  public function testBookInfoCPTWhenPostTypeSelected() {
    global $wp_query;

    $wp_query->in_the_loop = true;

    $general_option = array();
    $general_option['book_review_post_types'] = array(
      'documentation' => '1'
    );

    add_option( 'book_review_general', $general_option );
    set_post_type( $this->post_id, 'documentation' );
    update_post_meta( $this->post_id, 'book_review_title', 'The Fault in Our Stars' );

    $content = $this->plugin_public->display_book_info( '' );

    $this->assertNotEquals( '', $content );
  }

  /**
   * @covers Book_Review_Public::display_book_info
   */
  public function testNoBookInfoCPTWhenPostTypeNotSelected() {
    global $wp_query;

    $wp_query->in_the_loop = true;

    $general_option = array();
    $general_option['book_review_post_types'] = array(
      'documentation' => '0'
    );

    add_option( 'book_review_general', $general_option );
    set_post_type( $this->post_id, 'documentation' );
    update_post_meta( $this->post_id, 'book_review_title', 'The Fault in Our Stars' );

    $content = $this->plugin_public->display_book_info( '' );

    $this->assertEquals( '', $content );
  }

  /**
   * @covers Book_Review_Public::display_book_info
   * TODO: Test showing book info at top of content.
   */
  // public function testTopPosition() {

  // }

  /**
   * @covers Book_Review_Public::display_book_info
   * TODO: Test showing book info at bottom of content.
   */
  // public function testBottomPosition() {

  // }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testNoStyleForFeed() {
    global $wp_query;

    $wp_query->is_home = false;
    $wp_query->is_feed = true;

    $this->assertSame( '', $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testBorderColor() {
    $style = 'style="border-style: solid; border-width: 1px; border-color: #fff;"';
    $general_option = array(
      'book_review_border_color' => '#fff'
    );

    add_option( 'book_review_general', $general_option );

    $this->assertSame( $style, $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testNoBorderColor() {
    $style = 'style="border-style: solid; border-width: 1px;"';

    $this->assertSame( $style, $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testBorderWidth() {
    $style = 'style="border-style: solid; border-width: 5px;"';
    $general_option = array(
      'book_review_border_width' => 5
    );

    add_option( 'book_review_general', $general_option );

    $this->assertSame( $style, $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testNoBorderWidth() {
    $general_option = array(
      'book_review_border_width' => 0
    );

    add_option( 'book_review_general', $general_option );

    $this->assertSame( '', $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testBackgroundColor() {
    $style = 'style="border-style: solid; border-width: 1px; background-color: #e0e0e0;"';
    $general_option = array(
      'book_review_bg_color' => '#e0e0e0'
    );

    add_option( 'book_review_general', $general_option );

    $this->assertSame( $style, $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::get_review_box_style
   */
  public function testNoBackgroundColor() {
    $style = 'style="border-style: solid; border-width: 1px;"';

    $this->assertSame( $style, $this->plugin_public->get_review_box_style() );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testRatingInExcerpts() {
    $ratings_option = array(
      'book_review_rating_home' => '1'
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '1' );

    $this->assertSame( '<p class="book_review_rating_image"><img src="http://example.org' .
      '/wp-content/plugins/vagrant/www/github/book-review/src/includes' .
      '/images/one-star.png"></p>', $this->plugin_public->add_rating( '' ) );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testNoRatingInExcerpts() {
    update_post_meta( $this->post_id, 'book_review_rating', '1' );

    $this->assertSame( '', $this->plugin_public->add_rating( '' ) );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testNoRating() {
    $ratings_option = array(
      'book_review_rating_home' => '1'
    );

    add_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( '', $this->plugin_public->add_rating( '' ) );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testNoRatingSelected() {
    $ratings_option = array(
      'book_review_rating_home' => '1'
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '-1' );

    $this->assertSame( '', $this->plugin_public->add_rating( '' ) );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testRatingHomePage() {
    global $wp_query;

    $wp_query->is_home = true;

    $ratings_option = array(
      'book_review_rating_home' => '1'
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '1' );

    $this->assertSame( '<p class="book_review_rating_image"><img src="http://example.org' .
      '/wp-content/plugins/vagrant/www/github/book-review/src/includes' .
      '/images/one-star.png"></p>', $this->plugin_public->add_rating( '' ) );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testRatingArchivePage() {
    global $wp_query;

    $wp_query->is_home = false;
    $wp_query->is_archive = true;

    $ratings_option = array(
      'book_review_rating_home' => '1'
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '1' );

    $this->assertSame( '<p class="book_review_rating_image"><img src="http://example.org' .
      '/wp-content/plugins/vagrant/www/github/book-review/src/includes' .
      '/images/one-star.png"></p>', $this->plugin_public->add_rating( '' ) );
  }

  /**
   * @covers Book_Review_Public::add_rating
   */
  public function testRatingSearchPage() {
    global $wp_query;

    $wp_query->is_home = false;
    $wp_query->is_search = true;

    $ratings_option = array(
      'book_review_rating_home' => '1'
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '1' );

    $this->assertSame( '<p class="book_review_rating_image"><img src="http://example.org' .
      '/wp-content/plugins/vagrant/www/github/book-review/src/includes' .
      '/images/one-star.png"></p>', $this->plugin_public->add_rating( '' ) );
  }

  // TODO: Add tests for shortcode.
}