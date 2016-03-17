<?php

class Book_Review_Meta_Box_Tests extends WP_UnitTestCase {
  protected $plugin;
  protected $plugin_meta;

  public function setup() {
    global $wpdb;

    parent::setUp();

    $this->plugin = run_book_review();
    $this->plugin_meta = new Book_Review_Meta_Box( $this->plugin->get_plugin_name(),
      $this->plugin->get_settings(), $this->plugin->get_book_info() );

    $this->suppress = $wpdb->suppress_errors();
  }

  public function tearDown() {
    global $wpdb;

    parent::tearDown();

    $wpdb->suppress_errors( $this->suppress );
  }

  /**
   * @covers Book_Review_Meta_Box::meta_box_setup
   * TODO: Figure out how to test that an action was added.
   */
  public function testMetaBoxSetup() {
  }

  /**
   * @covers Book_Review_Meta_Box::add_meta_box
   */
  public function testNoMetaBoxOnPageByDefault() {
    global $wp_meta_boxes;

    $post_type = 'page';
    $screen = convert_to_screen( $post_type );
    $screen_id = $screen->id;
    $this->plugin_meta->add_meta_box( $post_type );

    $this->assertEquals( 0, count( $wp_meta_boxes ) );
  }

  /**
   * @covers Book_Review_Meta_Box::add_meta_box
   */
  public function testAddMetaBoxToPostByDefault() {
    global $wp_meta_boxes;

    $post_type = 'post';
    $screen = convert_to_screen( $post_type );
    $screen_id = $screen->id;
    $this->plugin_meta->add_meta_box( $post_type );

    $this->assertArrayHasKey( 'book-review-meta-box', $wp_meta_boxes[$screen_id]['normal']['high'] );
  }

  /**
   * @covers Book_Review_Meta_Box::add_meta_box
   */
  public function testAddMetaBoxToCustomPostTypeByDefault() {
    global $wp_meta_boxes;

    register_post_type( 'acme_product',
      array(
        'labels' => array(
          'name' => __( 'Products' ),
          'singular_name' => __( 'Product' )
        ),
        'public' => true,
      )
    );

    $post_type = 'acme_product';
    $screen = convert_to_screen( $post_type );
    $screen_id = $screen->id;
    $this->plugin_meta->add_meta_box( $post_type );

    $this->assertArrayHasKey( 'book-review-meta-box', $wp_meta_boxes[$screen_id]['normal']['high'] );
  }

  /**
   * @covers Book_Review_Meta_Box::add_meta_box
   */
  public function testAddMetaBoxBySetting() {
    global $wp_meta_boxes;

    $general = array();
    $general['book_review_post_types'] = array(
      'page' => '1'
    );

    add_option( 'book_review_general', $general );

    $post_type = 'page';
    $screen = convert_to_screen( $post_type );
    $screen_id = $screen->id;
    $this->plugin_meta->add_meta_box( $post_type );

    $this->assertArrayHasKey( 'book-review-meta-box', $wp_meta_boxes[$screen_id]['normal']['high'] );
  }

  /**
   * @covers Book_Review_Meta_Box::get_isbn_class
   */
  public function testISBNClass() {
    $advanced = array(
      'book_review_api_key' => 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0'
    );

    add_option( 'book_review_advanced', $advanced );

    $this->assertSame( 'row show', $this->plugin_meta->get_isbn_class() );
  }

  /**
   * @covers Book_Review_Meta_Box::get_isbn_class
   */
  public function testISBNClassNoAPIKey() {
    $this->assertSame( 'row hide', $this->plugin_meta->get_isbn_class() );
  }

  /**
   * @covers Book_Review_Meta_Box::get_cover_url_class
   */
  public function testCoverUrlClass() {
    $post_id = $this->factory->post->create();

    update_post_meta( $post_id, 'book_review_cover_url', 'http://url.to.image1.png' );

    $this->assertSame( 'cover-image show', $this->plugin_meta->get_cover_url_class( $post_id ) );
  }

  /**
   * @covers Book_Review_Meta_Box::get_cover_url_class
   */
  public function testCoverUrlClassNoCover() {
    $post_id = $this->factory->post->create();

    $this->assertSame( 'cover-image hide', $this->plugin_meta->get_cover_url_class( $post_id ) );
  }

  /**
   * @covers Book_Review_Meta_Box::get_rating_image_class
   */
  public function testRatingImageClass() {
    $post_id = $this->factory->post->create();

    update_post_meta( $post_id, 'book_review_rating', '4' );

    $this->assertSame( 'rating-image show', $this->plugin_meta->get_rating_image_class( $post_id ) );
  }

  /**
   * @covers Book_Review_Meta_Box::get_rating_image_class
   */
  public function testRatingImageClassNoRatingImageUrl() {
    $post_id = $this->factory->post->create();

    $this->assertSame( 'rating-image hide', $this->plugin_meta->get_rating_image_class( $post_id ) );
  }

  /**
   * @covers Book_Review_Meta_Box::display_rating
   */
  public function testDisplayValidRating() {
    $post_id = $this->factory->post->create();
    $items = '<option value="-1" >Select...</option><option value="1" >1</option><option value="2" >2</option><option value="3" selected="selected">3</option><option value="4" >4</option><option value="5" >5</option>';

    update_post_meta( $post_id, 'book_review_rating', '3' );

    $this->expectOutputString( $items, $this->plugin_meta->display_rating( $post_id ) );
  }

  /**
   * @covers Book_Review_Meta_Box::display_rating
   */
  public function testDisplayInvalidRating() {
    $post_id = $this->factory->post->create();
    $items = '<option value="-1" >Select...</option><option value="1" >1</option><option value="2" >2</option><option value="3" >3</option><option value="4" >4</option><option value="5" >5</option>';

    update_post_meta( $post_id, 'book_review_rating', '-5' );

    $this->expectOutputString( $items, $this->plugin_meta->display_rating( $post_id ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveISBN() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '0525478817', get_post_meta( $post_id, 'book_review_isbn', true ) );

    // Delete
    $_POST['book_review_isbn'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_isbn', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveTitle() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'The Fault in Our Stars', get_post_meta( $post_id, 'book_review_title', true ) );

    // Delete
    $_POST['book_review_title'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_title', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveSeries() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'None', get_post_meta( $post_id, 'book_review_series', true ) );

    // Delete
    $_POST['book_review_series'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_series', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveAuthor() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'John Green', get_post_meta( $post_id, 'book_review_author', true ) );

    // Delete
    $_POST['book_review_author'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_author', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveGenre() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Young Adult', get_post_meta( $post_id, 'book_review_genre', true ) );

    // Delete
    $_POST['book_review_genre'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_genre', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSavePublisher() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Dutton Books', get_post_meta( $post_id, 'book_review_publisher', true ) );

    // Delete
    $_POST['book_review_publisher'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_publisher', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveReleaseDate() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '2010-05-25', get_post_meta( $post_id, 'book_review_release_date', true ) );

    // Delete
    $_POST['book_review_release_date'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_release_date', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveFormat() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Paperback', get_post_meta( $post_id, 'book_review_format', true ) );

    // Delete
    $_POST['book_review_format'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_format', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSavePages() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '313', get_post_meta( $post_id, 'book_review_pages', true ) );

    // Delete
    $_POST['book_review_pages'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_pages', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveSource() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Purchased', get_post_meta( $post_id, 'book_review_source', true ) );

    // Delete
    $_POST['book_review_source'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_source', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_url_field
   */
  public function testSaveCoverUrl() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'http://example.com/wp-content/uploads/2014/04/The_Fault_in_Our_Stars_Book_Cover.jpg',
      get_post_meta( $post_id, 'book_review_cover_url', true ) );

    // Delete
    $_POST['book_review_cover_url'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_cover_url', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_summary
   */
  public function testSaveSummary() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis. But when a gorgeous plot twist named Augustus Waters suddenly appears at Cancer Kid Support Group, Hazel\'s story is about to be completely rewritten.',
      get_post_meta( $post_id, 'book_review_summary', true ) );

    // Delete
    $_POST['book_review_summary'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_summary', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_rating
   */
  public function testSaveRating() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '4', get_post_meta( $post_id, 'book_review_rating', true ) );

    // Delete
    $_POST['book_review_rating'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_rating', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   */
  public function testSaveArchivePost() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '1', get_post_meta( $post_id, 'book_review_archive_post', true ) );

    // Delete
    $_POST['book_review_archive_post'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_archive_post', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::get_archive_title
   */
  public function testSaveArchiveTitle() {
    $post_id = $this->factory->post->create();

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Fault in Our Stars, The', get_post_meta( $post_id, 'book_review_archive_title', true ) );

    // Delete
    $_POST['book_review_title'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_archive_title', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   */
  public function testSaveCustomFields() {
    $post_id = $this->factory->post->create();
    $_POST['book_review_fields'] = array(
      'book_review_565adc1c2d403' => 'Charlie Adlard'
    );

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( 'Charlie Adlard', get_post_meta( $post_id, 'book_review_565adc1c2d403', true ) );

    // Delete
    $_POST['book_review_fields']['book_review_565adc1c2d403'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_565adc1c2d403', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_url_field
   */
  public function testSaveSiteLinks() {
    $post_id = $this->factory->post->create();
    $_POST['book_review_sites'] = array(
      'book_review_goodreads' => 'https://www.goodreads.com/book/show/20312459-descent',
      'book_review_barnes_noble' => 'http://www.barnesandnoble.com/w/descent-tim-johnston/1117904882'
    );

    // Update
    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( $_POST['book_review_sites']['book_review_goodreads'],
      get_post_meta( $post_id, 'book_review_goodreads', true ), 'Goodreads' );
    $this->assertSame( $_POST['book_review_sites']['book_review_barnes_noble'],
      get_post_meta( $post_id, 'book_review_barnes_noble', true ), 'Amazon' );

    // Delete
    $_POST['book_review_sites']['book_review_goodreads'] = '';
    $_POST['book_review_sites']['book_review_barnes_noble'] = '';
    $this->plugin_meta->save_meta_box( $post_id );

    $this->assertSame( '', get_post_meta( $post_id, 'book_review_goodreads', true ) );
    $this->assertSame( '', get_post_meta( $post_id, 'book_review_barnes_noble', true ) );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   */
  public function testSaveLinks() {
    global $wpdb;

    $post_id = $this->factory->post->create();
    $_POST['book_review_custom_link1'] = 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars';
    $_POST['book_review_custom_link2'] = 'http://www.barnesandnoble.com/w/the-fault-in-our-stars-john-green/1104045488?ean=9780525478812';

    $this->create_post_meta();
    $this->create_tables();

    // Add some links.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png'
      ),
      array( '%s', '%s' )
    );

    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'text' => 'Barnes & Noble',
        'image_url' => 'http://fakeurl.com/BarnesNoble.png'
      ),
      array( '%s', '%s' )
    );

    // Update
    $this->plugin_meta->save_meta_box( $post_id );

    // Confirm link URLs were added.
    $index = 1;
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( 2, count( $results ), 'Links added' );

    foreach ( $results as $result ) {
      if ( $index == 1) {
        $this->assertEquals( $_POST['book_review_custom_link1'], $result->url, 'Goodreads' );
      }
      else {
        $this->assertEquals( $_POST['book_review_custom_link2'], $result->url, 'Barnes & Noble' );
      }

      $index++;
    }

    // Delete
    $_POST['book_review_custom_link1'] = '';
    $_POST['book_review_custom_link2'] = '';

    $this->plugin_meta->save_meta_box( $post_id );

    // Ensure links were deleted.
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( 0, count( $results ), 'Links deleted' );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   */
  public function testSaveInvalidCustomLink() {
    global $wpdb;

    $post_id = $this->factory->post->create();
    $_POST['book_review_custom_link1'] = 'www.goodreads.com/book/show/11870085-the-fault-in-our-stars';

    $this->create_post_meta();
    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png'
      ),
      array( '%s', '%s' )
    );

    $this->plugin_meta->save_meta_box( $post_id );

    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( 1, count( $results ), 'Link added' );

    foreach ( $results as $result ) {
      $this->assertEquals( 'http://' . $_POST['book_review_custom_link1'], $result->url, 'Goodreads' );
    }

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Meta_Box::user_can_save
   */
  public function testIsAutoSave() {
    // Create parent post.
    $post_id = $this->factory->post->create();

    // Create an autosave post.
    $post = $this->factory->post->create_and_get( array(
      'post_parent' => $post_id,
      'post_status' => 'inherit',
      'post_type' => 'revision',
      'post_name' => "{$post_id}-autosave"
    ) );

    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post->ID );
    $custom_fields = get_post_custom( $post->ID );

    $this->assertCount( 0, $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::user_can_save
   */
  public function testIsRevision() {
    // Create parent post.
    $post_id = $this->factory->post->create();

    // Create an autosave post.
    $post = $this->factory->post->create_and_get( array(
      'post_parent' => $post_id,
      'post_status' => 'inherit',
      'post_type' => 'revision',
      'post_name' => "{$post_id}-revision-v1"
    ) );

    $this->create_post_meta();
    $this->plugin_meta->save_meta_box( $post->ID );
    $custom_fields = get_post_custom( $post->ID );

    $this->assertCount( 0, $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::user_can_save
   */
  public function testEmptyNonce() {
    $this->create_post_meta();
    $_POST['book_review_meta_box_nonce'] = '';
    $post_id = $this->factory->post->create();

    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_meta( $post_id, 'book_review_isbn' );

    // If ISBN wasn't saved, then none of the other fields will have been saved either.
    $this->assertCount( 0, $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::user_can_save
   */
  public function testInvalidNonce() {
    $this->create_post_meta();
    $_POST['book_review_meta_box_nonce'] = 'invalid';
    $post_id = $this->factory->post->create();

    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_meta( $post_id, 'book_review_isbn' );

    // If ISBN wasn't saved, then none of the other fields will have been saved either.
    $this->assertCount( 0, $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::get_archive_title
   */
  public function testGetArchiveTitleStartingWithThe() {
    // Create post data.
    $this->create_post_meta();

    $post_id = $this->factory->post->create();
    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_custom( $post_id );

    $this->assertEquals( 'Fault in Our Stars, The', $custom_fields['book_review_archive_title'][0] );
  }

  /**
   * @covers Book_Review_Meta_Box::get_archive_title
   */
  public function testGetArchiveTitleStartingWithA() {
    // Create post data.
    $this->create_post_meta();
    $_POST['book_review_title'] = 'A Game of Thrones';

    $post_id = $this->factory->post->create();
    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_custom( $post_id );

    $this->assertEquals( 'Game of Thrones, A', $custom_fields['book_review_archive_title'][0] );
  }

  /**
   * @covers Book_Review_Meta_Box::get_archive_title
   */
  public function testGetArchiveTitleStartingWithAn() {
    // Create post data.
    $this->create_post_meta();
    $_POST['book_review_title'] = 'An Abundance of Katherines';

    $post_id = $this->factory->post->create();
    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_custom( $post_id );

    $this->assertEquals( 'Abundance of Katherines, An', $custom_fields['book_review_archive_title'][0] );
  }

  /**
   * Helper function to create post meta.
   */
  private function create_post_meta() {
    $_POST['book_review_isbn'] = '0525478817';
    $_POST['book_review_title'] = 'The Fault in Our Stars';
    $_POST['book_review_series'] = 'None';
    $_POST['book_review_author'] = 'John Green';
    $_POST['book_review_genre'] = 'Young Adult';
    $_POST['book_review_publisher'] = 'Dutton Books';
    $_POST['book_review_release_date'] = '2010-05-25';
    $_POST['book_review_format'] = 'Paperback';
    $_POST['book_review_pages'] = '313';
    $_POST['book_review_source'] = 'Purchased';
    $_POST['book_review_cover_url'] = 'http://example.com/wp-content/uploads/2014/04/The_Fault_in_Our_Stars_Book_Cover.jpg';
    $_POST['book_review_summary'] = 'Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis. But when a gorgeous plot twist named Augustus Waters suddenly appears at Cancer Kid Support Group, Hazel\'s story is about to be completely rewritten.';
    $_POST['book_review_rating'] = '4';
    $_POST['book_review_archive_post'] = '1';
    $_POST['book_review_meta_box_nonce'] = wp_create_nonce( 'save_meta_box' );
  }

  /**
   * Helper function to create custom tables.
   */
  private function create_tables() {
    remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php';

    Book_Review_Activator::activate( false );
  }

  /**
   * Helper function to drop custom tables.
   */
  private function drop_tables() {
    global $wpdb;

    delete_option( 'book_review_version' );
    remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

    $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_links' );
    $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_link_urls' );
  }
}
?>