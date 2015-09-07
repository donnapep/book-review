<?php

class Book_Review_Meta_Box_Tests extends WP_UnitTestCase {
  protected $plugin;
  protected $plugin_name;
  protected $plugin_meta;

  public function setup() {
    global $wpdb;

    parent::setUp();

    $this->plugin = run_book_review();
    $this->plugin_name = $this->plugin->get_plugin_name();
    $this->plugin_meta = new Book_Review_Meta_Box( $this->plugin_name );

    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php';
    Book_Review_Activator::activate( false );

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
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   * @covers Book_Review_Meta_Box::save_url_field
   * @covers Book_Review_Meta_Box::save_field
   */
  public function testSavePostMeta() {
    // Create post data.
    $this->createPostMeta();

    $post_id = $this->factory->post->create();
    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_custom( $post_id );

    $this->assertEquals( '0525478817', $custom_fields['book_review_isbn'][0] );
    $this->assertEquals( 'The Fault in Our Stars', $custom_fields['book_review_title'][0] );
    $this->assertEquals( 'None', $custom_fields['book_review_series'][0] );
    $this->assertEquals( 'John Green', $custom_fields['book_review_author'][0] );
    $this->assertEquals( 'Young Adult', $custom_fields['book_review_genre'][0] );
    $this->assertEquals( 'Dutton Books', $custom_fields['book_review_publisher'][0] );
    $this->assertEquals( '2010-05-25', $custom_fields['book_review_release_date'][0] );
    $this->assertEquals( 'Paperback', $custom_fields['book_review_format'][0] );
    $this->assertEquals( '313', $custom_fields['book_review_pages'][0] );
    $this->assertEquals( 'Purchased', $custom_fields['book_review_source'][0] );
    $this->assertEquals( 'http://example.com/wp-content/uploads/2014/04/The_Fault_in_Our_Stars_Book_Cover.jpg', $custom_fields['book_review_cover_url'][0] );
    $this->assertEquals( 'Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis. But when a gorgeous plot twist named Augustus Waters suddenly appears at Cancer Kid Support Group, Hazel\'s story is about to be completely rewritten.', $custom_fields['book_review_summary'][0] );
    $this->assertEquals( '4', $custom_fields['book_review_rating'][0] );
    $this->assertEquals( '1', $custom_fields['book_review_archive_post'][0] );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   * @covers Book_Review_Meta_Box::save_text_field
   * @covers Book_Review_Meta_Box::save_url_field
   * @covers Book_Review_Meta_Box::save_field
   */
  public function testSaveEmptyPostMeta() {
    // Create post data.
    $this->createPostMeta();

    $post_id = $this->factory->post->create();
    $this->plugin_meta->save_meta_box( $post_id );

    // Create empty post data.
    $_POST['book_review_isbn'] = '';
    $_POST['book_review_title'] = '';
    $_POST['book_review_series'] = '';
    $_POST['book_review_author'] = '';
    $_POST['book_review_genre'] = '';
    $_POST['book_review_publisher'] = '';
    $_POST['book_review_release_date'] = '';
    $_POST['book_review_format'] = '';
    $_POST['book_review_pages'] = '';
    $_POST['book_review_source'] = '';
    $_POST['book_review_cover_url'] = '';
    $_POST['book_review_summary'] = '';
    $_POST['book_review_rating'] ='';
    $_POST['book_review_archive_post'] = '';
    $_POST['book-review-meta-box-nonce'] = wp_create_nonce( 'save_meta_box_nonce' );

    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_custom( $post_id );

    $this->assertArrayNotHasKey( 'book_review_isbn', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_title', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_archive_title', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_series', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_author', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_genre', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_publisher', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_release_date', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_format', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_pages', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_source', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_cover_url', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_summary', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_rating', $custom_fields );
    $this->assertArrayNotHasKey( 'book_review_archive_post', $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   */
  public function testSaveCustomLinks() {
    global $wpdb;

    // Create post data.
    $this->createPostMeta();
    $_POST['book_review_custom_link1'] = 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars';
    $_POST['book_review_custom_link2'] = 'http://www.barnesandnoble.com/w/the-fault-in-our-stars-john-green/1104045488?ean=9780525478812';

    $post_id = $this->factory->post->create();

    // Add some custom links.
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

    $this->plugin_meta->save_meta_box( $post_id );

    $index = 1;
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( 2, count( $results ), '2 custom link URLs were saved' );

    foreach ( $results as $result ) {
      if ( $index == 1) {
        $this->assertEquals( $_POST['book_review_custom_link1'], $result->url, 'Goodreads custom link URL was saved' );
      }
      else {
        $this->assertEquals( $_POST['book_review_custom_link2'], $result->url, 'Barnes & Noble custom link URL was saved' );
      }

      $index++;
    }
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   */
  public function testSaveEmptyCustomLinks() {
    global $wpdb;

    // Create post data.
    $this->createPostMeta();
    $_POST['book_review_custom_link1'] = 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars';
    $_POST['book_review_custom_link2'] = 'http://www.barnesandnoble.com/w/the-fault-in-our-stars-john-green/1104045488?ean=9780525478812';

    $post_id = $this->factory->post->create();

    // Add some custom links.
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

    $this->plugin_meta->save_meta_box( $post_id );

    // Ensure customs links were saved.
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( 2, count( $results ), '2 custom link URLs were saved' );

    // Save empty custom links.
    $_POST['book_review_custom_link1'] = '';
    $_POST['book_review_custom_link2'] = '';

    $this->plugin_meta->save_meta_box( $post_id );

    // Ensure customs links were deleted.
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( 0, count( $results ), 'Custom link URLs were deleted' );
  }

  /**
   * @covers Book_Review_Meta_Box::save_meta_box
   */
  public function testSaveInvalidCustomLink() {
    global $wpdb;

    // Create post data.
    $this->createPostMeta();
    $_POST['book_review_custom_link1'] = 'www.goodreads.com/book/show/11870085-the-fault-in-our-stars';

    $post_id = $this->factory->post->create();

    // Add some custom links.
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

    $this->assertEquals( 1, count( $results ), '1 custom link URL was saved' );

    foreach ( $results as $result ) {
      $this->assertEquals( 'http://' . $_POST['book_review_custom_link1'], $result->url, 'Goodreads custom link URL was saved' );
    }
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

    $this->createPostMeta();
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

    $this->createPostMeta();
    $this->plugin_meta->save_meta_box( $post->ID );
    $custom_fields = get_post_custom( $post->ID );

    $this->assertCount( 0, $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::user_can_save
   */
  public function testNoNonce() {
    $this->createPostMeta();
    $_POST['book-review-meta-box-nonce'] = '';
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
    $this->createPostMeta();
    $_POST['book-review-meta-box-nonce'] = 'invalid';
    $post_id = $this->factory->post->create();

    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_meta( $post_id, 'book_review_isbn' );

    // If ISBN wasn't saved, then none of the other fields will have been saved either.
    $this->assertCount( 0, $custom_fields );
  }

  /**
   * @covers Book_Review_Meta_Box::render_rating
   */
  public function testRenderRating() {
    $items = '<option value="-1" >Select...</option><option value="1" >1</option><option value="2" >2</option><option value="3" selected="selected">3</option><option value="4" >4</option><option value="5" >5</option>';

    $this->expectOutputString( $items, $this->plugin_meta->render_rating( '3' ) );
  }

  /**
   * @covers Book_Review_Meta_Box::get_archive_title
   */
  public function testGetArchiveTitleStartingWithThe() {
    // Create post data.
    $this->createPostMeta();

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
    $this->createPostMeta();
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
    $this->createPostMeta();
    $_POST['book_review_title'] = 'An Abundance of Katherines';

    $post_id = $this->factory->post->create();
    $this->plugin_meta->save_meta_box( $post_id );
    $custom_fields = get_post_custom( $post_id );

    $this->assertEquals( 'Abundance of Katherines, An', $custom_fields['book_review_archive_title'][0] );
  }

  private function createPostMeta() {
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
    $_POST['book-review-meta-box-nonce'] = wp_create_nonce( 'save_meta_box_nonce' );
  }
}
?>