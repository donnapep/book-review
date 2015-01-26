<?php

if ( !is_multisite() ) :

/* Test that whatever tables and data are supposed to be created on a single-site activation
   actually get created. Note that these tests are only applicable for a single site install.
   They won't run with multi-site configured, so in order to run this test suite the
   WP_TESTS_MULTISITE const in phpunit.xml will need to be removed and the tests re-run. */
class Book_Review_Activator_Tests extends WP_UnitTestCase {
  protected $object;
  protected $old_version = '2.1.6';

  public function setup() {
    parent::setUp();

    remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php';
  }

  public function tearDown() {
    global $wpdb;

    parent::tearDown();

    delete_option( 'book_review_version' );
    remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

    $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_links' );
    $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_link_urls' );
  }

  public function testNoVersion() {
    global $wpdb;

    Book_Review_Activator::activate( false );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( $wpdb->prefix . 'book_review_custom_links', $links_table, 'Custom links table created' );
    $this->assertEquals( $wpdb->prefix . 'book_review_custom_link_urls', $link_urls_table, 'Custom link urls table created');
    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version added' );
  }

  public function testOldVersion() {
    global $wpdb;

    add_option( 'book_review_version', $this->old_version );
    Book_Review_Activator::activate( false );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( NULL, $links_table, 'Custom links table not created' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table not created');
    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version updated' );
  }

  public function testCurrentVersion() {
    global $wpdb;

    add_option( 'book_review_version', Book_Review_Activator::VERSION );
    Book_Review_Activator::activate( false );

    $links_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_links"' );
    $link_urls_table = $wpdb->get_var( 'SHOW TABLES LIKE "' . $wpdb->prefix . 'book_review_custom_link_urls"' );

    $this->assertEquals( NULL, $links_table, 'Custom links table not created' );
    $this->assertEquals( NULL, $link_urls_table, 'Custom link urls table not created');
    $this->assertEquals( get_option( 'book_review_version' ), Book_Review_Activator::VERSION, 'Version remains the same' );
  }
}

endif;