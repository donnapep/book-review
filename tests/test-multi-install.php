<?php

if ( is_multisite() ) :

/* Test that whatever tables and data are supposed to be created on a multi-site activation
   actually get created. */
// TODO: Test that custom tables are empty when no data to convert.
// TODO: Test failure when inserting rows into book_review_custom_links.
// TODO: Test failure when inserting rows into book_review_custom_link_urls.
class Book_Review_Multi_Install_Tests extends WP_UnitTestCase {
  protected $old_version = '2.1.6';
  protected $suppress = false;

  private static $blog_id;

  public function setup() {
    global $wpdb;

    parent::setUp();
    remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php';

    $this->suppress = $wpdb->suppress_errors();
  }

  public function tearDown() {
    global $wpdb;

    parent::tearDown();
    remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

    // Drop custom tables across all blogs.
    foreach ( $blog_ids as $id ) {
      switch_to_blog( $id );
      delete_option( 'book_review_version' );
      $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_links' );
      $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_link_urls' );
    }

    restore_current_blog();
    $wpdb->suppress_errors( $this->suppress );
  }

  public static function tearDownAfterClass() {
    wpmu_delete_blog( self::$blog_id, true );
  }

  /* Activate plugin for a single site. */

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::single_activate
   * @covers Book_Review_Activator::create_tables
   * @covers Book_Review_Activator::convert_data
   */
  public function testNoVersion() {
    global $wpdb;

    Book_Review_Activator::activate( false );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version added' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_links', $links_table, 'Custom links table created' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_link_urls', $link_urls_table, 'Custom link urls table created');
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::single_activate
   */
  public function testOldVersion() {
    global $wpdb;

    add_option( 'book_review_version', $this->old_version );
    Book_Review_Activator::activate( false );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version updated' );
    $this->assertEquals( NULL, $links_table, 'Custom links table not created' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table not created');
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::single_activate
   */
  public function testCurrentVersion() {
    global $wpdb;

    add_option( 'book_review_version', Book_Review_Activator::VERSION );
    Book_Review_Activator::activate( false );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version remains the same' );
    $this->assertEquals( NULL, $links_table, 'Custom links table not created' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table not created');
  }

  /**
   * @covers Book_Review_Activator::activate_new_site
   * @covers Book_Review_Activator::single_activate
   * @covers Book_Review_Activator::create_tables
   * @covers Book_Review_Activator::convert_data
   */
  public function testActivateNewSite() {
    global $wpdb;

    self::$blog_id = $this->factory->blog->create();
    switch_to_blog( self::$blog_id );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version added' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_links', $links_table, 'Custom links table created' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_link_urls', $link_urls_table, 'Custom link urls table created');

    restore_current_blog();
  }

  /* Network activate plugin for multiple sites. */

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   * @covers Book_Review_Activator::create_tables
   * @covers Book_Review_Activator::convert_data
   */
  public function testNetworkActivatePrimarySiteNoVersion() {
    global $wpdb;

    Book_Review_Activator::activate( true );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version added' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_links', $links_table, 'Custom links table exists' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_link_urls', $link_urls_table, 'Custom link urls table exists');
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   * @covers Book_Review_Activator::create_tables
   * @covers Book_Review_Activator::convert_data
  */
  public function testNetworkActivateSecondarySiteNoVersion() {
    global $wpdb;

    Book_Review_Activator::activate( true );
    switch_to_blog( self::$blog_id );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version added' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_links', $links_table, 'Custom links table exists' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_link_urls', $link_urls_table, 'Custom link urls table exists');

    restore_current_blog();
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   */
  public function testNetworkActivatePrimarySiteOldVersion() {
    global $wpdb;

    add_option( 'book_review_version', $this->old_version );
    Book_Review_Activator::activate( true );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version updated' );
    $this->assertEquals( NULL, $links_table, 'Custom links table does not exist' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table does not exist');
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   */
  public function testNetworkActivateSecondarySiteOldVersion() {
    global $wpdb;

    switch_to_blog( self::$blog_id );
    add_option( 'book_review_version', $this->old_version );
    Book_Review_Activator::activate( true );
    switch_to_blog( self::$blog_id );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version updated' );
    $this->assertEquals( NULL, $links_table, 'Custom links table does not exist' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table does not exist');

    restore_current_blog();
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   */
  public function testNetworkActivatePrimarySiteCurrentVersion() {
    global $wpdb;

    add_option( 'book_review_version', Book_Review_Activator::VERSION );
    Book_Review_Activator::activate( true );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version is unchanged' );
    $this->assertEquals( NULL, $links_table, 'Custom links table does not exist' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table does not exist');
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   */
  public function testNetworkActivateSecondarySiteCurrentVersion() {
    global $wpdb;

    $version = Book_Review_Activator::VERSION;

    switch_to_blog( self::$blog_id );
    add_option( 'book_review_version', $version );
    Book_Review_Activator::activate( true );
    switch_to_blog( self::$blog_id );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( get_option( 'book_review_version' ), $version, 'Version is unchanged' );
    $this->assertEquals( NULL, $links_table, 'Custom links table does not exist' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table does not exist');

    restore_current_blog();
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   * @covers Book_Review_Activator::create_tables
   * @covers Book_Review_Activator::convert_data
  */
  public function testPrimarySiteConvertData() {
    global $wpdb;

    $total_links = 5;

    // Insert some test data for the primary site.
    $data = array(
      'book_review_num_links' => $total_links,
      'book_review_link_target' => 1,
      'book_review_link_image1' => 'http://fakeurl.com/Goodreads.png',
      'book_review_link_text1' => 'Goodreads',
      'book_review_link_image2' => 'http://fakeurl.com/Amazon.png',
      'book_review_link_text2' => 'Amazon',
      'book_review_link_image3' => 'http://fakeurl.com/TheBookDepository.png',
      'book_review_link_text3' => 'The Book Depository',
      'book_review_link_image4' => 'http://fakeurl.com/BarnesNoble.png',
      'book_review_link_text4' => 'Barnes & Noble',
      'book_review_link_image5' => 'http://fakeurl.com/Indigo.png',
      'book_review_link_text5' => 'Indigo'
    );
    $meta_data = array(
      'book_review_link1' => 'https://www.goodreads.com/book/show/20170404-station-eleven',
      'book_review_link2' => 'http://www.amazon.com/Station-Eleven-Emily-John-Mandel/dp/0385353308',
      'book_review_link3' => 'http://www.bookdepository.com/Station-Eleven-Emily-St-John-Mandel/9781447268963',
      'book_review_link4' => 'http://www.barnesandnoble.com/w/station-eleven-emily-st-john-mandel/1117737038?ean=9780385353304',
      'book_review_link5' => 'http://www.chapters.indigo.ca/en-ca/books/station-eleven/9781443434867-item.html'
    );
    $post_id = $this->factory->post->create();

    foreach ( $meta_data as $key => $value ) {
      update_post_meta( $post_id, $key, $value );
    }

    add_option( 'book_review_links', $data );

    Book_Review_Activator::activate( true );

    // Check that data was moved to book_review_custom_links table.
    $index = 1;
    $links = get_option( 'book_review_links' );
    $results = $wpdb->get_results( 'SELECT text, image_url FROM ' . $wpdb->prefix . 'book_review_custom_links' );

    $this->assertEquals( $total_links, count( $results ),
      'Correct number of rows were added to book_review_custom_links table' );
    $this->assertEquals( $data['book_review_link_target'], $links['book_review_target'], 'Link target is correct' );

    foreach ( $results as $result ) {
      $this->assertEquals( $data['book_review_link_text' . $index], $result->text,
        'Text is correct in book_review_custom_links table' );
      $this->assertEquals( $data['book_review_link_image' . $index], $result->image_url,
        'Image URL is correct in book_review_custom_links table' );
      $index++;
    }

    // Check that data was moved to book_review_custom_link_urls table.
    $index = 1;
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    $this->assertEquals( $total_links, count( $results ),
      'Correct number of rows were added to book_review_custom_link_urls table' );

    foreach ( $results as $result ) {
      $this->assertEquals( $meta_data['book_review_link' . $index], $result->url,
        'URL is correct in book_review_custom_link_urls table' );
      $index++;
    }
  }

  /**
   * @covers Book_Review_Activator::activate
   * @covers Book_Review_Activator::get_blog_ids
   * @covers Book_Review_Activator::single_activate
   * @covers Book_Review_Activator::create_tables
   * @covers Book_Review_Activator::convert_data
  */
  public function testSecondarySiteConvertData() {
    global $wpdb;

    $total_links = 2;

    // Insert some test data.
    $data = array(
      'book_review_num_links' => $total_links,
      'book_review_link_target' => 0,
      'book_review_link_image1' => 'http://fakeurl.com/Goodreads.png',
      'book_review_link_text1' => 'Goodreads',
      'book_review_link_image2' => 'http://fakeurl.com/Amazon.png',
      'book_review_link_text2' => 'Amazon'
    );
    $meta_data = array(
      'book_review_link1' => 'https://www.goodreads.com/book/show/23637440-kyland',
      'book_review_link2' => ''
    );

    switch_to_blog( self::$blog_id );
    $post_id = $this->factory->post->create();

    foreach ( $meta_data as $key => $value ) {
      update_post_meta( $post_id, $key, $value );
    }

    add_option( 'book_review_links', $data );
    Book_Review_Activator::activate( true );
    switch_to_blog( self::$blog_id );

    // Check that data was moved to book_review_custom_links table.
    $index = 1;
    $links = get_option( 'book_review_links' );
    $results = $wpdb->get_results( 'SELECT text, image_url FROM ' . $wpdb->prefix . 'book_review_custom_links' );

    $this->assertEquals( $total_links, count( $results ),
      'Correct number of rows were added to book_review_custom_links table' );
    $this->assertEquals( $data['book_review_link_target'], $links['book_review_target'], 'Link target is correct' );

    foreach ( $results as $result ) {
      $this->assertEquals( $data['book_review_link_text' . $index], $result->text,
        'Text is correct in book_review_custom_links table' );
      $this->assertEquals( $data['book_review_link_image' . $index], $result->image_url,
        'Image URL is correct in book_review_custom_links table' );
      $index++;
    }

    // Check that data was moved to book_review_custom_link_urls table.
    $index = 1;
    $results = $wpdb->get_results( 'SELECT url FROM ' . $wpdb->prefix . 'book_review_custom_link_urls ' .
      'WHERE post_id = ' . $post_id );

    // Empty links are not copied.
    $this->assertEquals( 1, count( $results ),
      'Correct number of rows were added to book_review_custom_link_urls table' );

    foreach ( $results as $result ) {
      $this->assertEquals( $meta_data['book_review_link' . $index], $result->url,
        'URL is correct in book_review_custom_link_urls table' );
      $index++;
    }

    restore_current_blog();
  }
}

endif;
