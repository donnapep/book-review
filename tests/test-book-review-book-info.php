<?php

class Book_Review_Book_Info_Tests extends WP_UnitTestCase {
  protected $plugin;
  protected $book_info;
  protected $post_id;
  protected $link_url;

  public function setup() {
    global $wpdb;

    parent::setUp();

    // Initialize necessary classes.
    $this->plugin = run_book_review();
    $this->book_info = new Book_Review_Book_Info( $this->plugin->get_plugin_name(), $this->plugin->get_settings() );

    // Create post.
    $this->post_id = $this->factory->post->create();

    $this->link_url = 'https://www.goodreads.com/book/show/20312459-descent';

    // Suppress errors.
    $this->suppress = $wpdb->suppress_errors();
  }

  public function tearDown() {
    global $wp_settings_errors;
    global $wpdb;

    parent::tearDown();

    $GLOBALS['wp_settings_errors'] = array();

    $wpdb->suppress_errors( $this->suppress );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_isbn
   */
  public function testISBN() {
    update_post_meta( $this->post_id, 'book_review_isbn', '0525478817' );

    $this->assertSame( '0525478817', $this->book_info->get_book_review_isbn( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_isbn
   */
  public function testNoISBN() {
    $this->assertSame( '', $this->book_info->get_book_review_isbn( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_title
   */
  public function testTitle() {
    update_post_meta( $this->post_id, 'book_review_title', 'The Fault in Our Stars' );

    $this->assertSame( 'The Fault in Our Stars', $this->book_info->get_book_review_title( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_title
   */
  public function testNoTitle() {
    $this->assertSame( '', $this->book_info->get_book_review_title( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_series
   */
  public function testSeries() {
    update_post_meta( $this->post_id, 'book_review_series', 'None' );

    $this->assertSame( 'None', $this->book_info->get_book_review_series( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_series
   */
  public function testNoSeries() {
    $this->assertSame( '', $this->book_info->get_book_review_series( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_author
   */
  public function testAuthor() {
    update_post_meta( $this->post_id, 'book_review_author', 'John Green' );

    $this->assertSame( 'John Green', $this->book_info->get_book_review_author( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_author
   */
  public function testNoAuthor() {
    $this->assertSame( '', $this->book_info->get_book_review_author( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_genre
   */
  public function testGenre() {
    update_post_meta( $this->post_id, 'book_review_genre', 'Young Adult' );

    $this->assertSame( 'Young Adult', $this->book_info->get_book_review_genre( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_genre
   */
  public function testNoGenre() {
    $this->assertSame( '', $this->book_info->get_book_review_genre( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_publisher
   */
  public function testPublisher() {
    update_post_meta( $this->post_id, 'book_review_publisher', 'Dutton Books' );

    $this->assertSame( 'Dutton Books', $this->book_info->get_book_review_publisher( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_publisher
   */
  public function testNoPublisher() {
    $this->assertSame( '', $this->book_info->get_book_review_publisher( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_release_date
   */
  public function testReleaseDate() {
    update_post_meta( $this->post_id, 'book_review_release_date', '2010-05-25' );

    $this->assertSame( '2010-05-25', $this->book_info->get_book_review_release_date( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_release_date
   */
  public function testNoReleaseDate() {
    $this->assertSame( '', $this->book_info->get_book_review_release_date( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_format
   */
  public function testFormat() {
    update_post_meta( $this->post_id, 'book_review_format', 'Paperback' );

    $this->assertSame( 'Paperback', $this->book_info->get_book_review_format( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_format
   */
  public function testNoFormat() {
    $this->assertSame( '', $this->book_info->get_book_review_format( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_pages
   */
  public function testPages() {
    update_post_meta( $this->post_id, 'book_review_pages', '313' );

    $this->assertSame( '313', $this->book_info->get_book_review_pages( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_pages
   */
  public function testNoPages() {
    $this->assertSame( '', $this->book_info->get_book_review_pages( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_source
   */
  public function testSource() {
    update_post_meta( $this->post_id, 'book_review_source', 'Purchased' );

    $this->assertSame( 'Purchased', $this->book_info->get_book_review_source( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_source
   */
  public function testNoSource() {
    $this->assertSame( '', $this->book_info->get_book_review_source( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_field
   */
  public function testField() {
    update_post_meta( $this->post_id, 'book_review_565adc1c2d403', 'Charlie Adlard' );

    $this->assertSame( 'Charlie Adlard', $this->book_info->get_book_review_field( $this->post_id,
      'book_review_565adc1c2d403' ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_field
   */
  public function testNoField() {
    $this->assertSame( '', $this->book_info->get_book_review_field( $this->post_id,
      'book_review_565adc1c2d403' ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link
   */
  public function testSiteLink() {
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $this->link_url, $this->book_info->get_book_review_site_link( $this->post_id,
      'book_review_goodreads' ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link
   */
  public function testNoSiteLink() {
    $this->assertSame( '', $this->book_info->get_book_review_site_link( $this->post_id, 'book_review_goodreads' ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_button_site_link
   * @covers Book_Review_Book_Info::get_button_site_link_url
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testSiteLinkGoodreadsButton() {
    $html[] = '<a class="custom-link" href="' . $this->link_url . '">'.
      '<img src="http://example.org/wp-content/plugins/vagrant/www/github/book-review/src/includes/images/goodreads.png" alt="Goodreads"></a>';

    // Add a site link.
    $links_option = array(
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'button',
          'text' => 'Goodreads',
          'url' => '',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_button_site_link
   * @covers Book_Review_Book_Info::get_button_site_link_url
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testSiteLinkGoodreadsButtonWithTarget() {
    $html[] = '<a class="custom-link" href="' . $this->link_url . '" target="_blank">'.
      '<img src="http://example.org/wp-content/plugins/vagrant/www/github/book-review/src/includes/images/goodreads.png" alt="Goodreads"></a>';

    // Add a site link.
    $links_option = array(
      'book_review_target' => '1',
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'button',
          'text' => 'Goodreads',
          'url' => '',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_button_site_link
   * @covers Book_Review_Book_Info::get_button_site_link_url
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testSiteLinkBarnesNobleButton() {
    $html[] = '<a class="custom-link" href="' . $this->link_url . '">'.
      '<img src="http://example.org/wp-content/plugins/vagrant/www/github/book-review/src/includes/images/barnes-noble.png" alt="Barnes &amp; Noble"></a>';

    // Add a site link.
    $links_option = array(
      'sites' => array(
        'book_review_barnes_noble' => array(
          'type' => 'button',
          'text' => 'Barnes & Noble',
          'url' => '',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_barnes_noble', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_text_site_link
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testTextSiteLinkText() {
    $html[] = '<a class="custom-link" href="'. $this->link_url . '">Goodreads</a>';

    // Add a site link.
    $links_option = array(
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'text',
          'text' => 'Goodreads',
          'url' => '',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_text_site_link
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testTextSiteLinkTextWithTarget() {
    $html[] = '<a class="custom-link" href="'. $this->link_url . '" target="_blank">Goodreads</a>';

    // Add a site link.
    $links_option = array(
      'book_review_target' => '1',
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'text',
          'text' => 'Goodreads',
          'url' => '',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_custom_site_link
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testSiteLinkCustom() {
    $html[] = '<a class="custom-link" href="' . $this->link_url . '">'.
      '<img src="http://fakeurl.com/goodreads.png" alt="Goodreads"></a>';

    // Add a site link.
    $links_option = array(
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'custom',
          'text' => 'Goodreads',
          'url' => 'http://fakeurl.com/goodreads.png',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   * @covers Book_Review_Book_Info::get_custom_site_link
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testSiteLinkCustomWithTarget() {
    $html[] = '<a class="custom-link" href="' . $this->link_url . '" target="_blank">'.
      '<img src="http://fakeurl.com/goodreads.png" alt="Goodreads"></a>';

    // Add a site link.
    $links_option = array(
      'book_review_target' => '1',
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'custom',
          'text' => 'Goodreads',
          'url' => 'http://fakeurl.com/goodreads.png',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( $html[0], $this->book_info->get_book_review_site_link_html( $this->post_id )[0] );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   */
  public function testSiteLinkNoLinkUrl() {
    // Add a site link.
    $links_option = array(
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'button',
          'text' => 'Goodreads',
          'url' => '',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );

    $this->assertSame( array(), $this->book_info->get_book_review_site_link_html( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   */
  public function testInactiveSiteLink() {
    // Add a site link.
    $links_option = array(
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'button',
          'text' => 'Goodreads',
          'url' => '',
          'active' => '0'
        )
      )
    );

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( array(), $this->book_info->get_book_review_site_link_html( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_site_link_html
   */
  public function testDefaultSiteLinks() {
    $links_option = array();

    update_option( 'book_review_links', $links_option );
    update_post_meta( $this->post_id, 'book_review_goodreads', $this->link_url );

    $this->assertSame( array(), $this->book_info->get_book_review_site_link_html( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_meta
   */
  public function testLinksMeta() {
    global $wpdb;

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png'
      ),
      array( '%d', '%s', '%s' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $links = $this->book_info->get_book_review_links_meta( $this->post_id );

    $this->assertSame( 1, count( $links ), 'Link count' );
    // MySQL returns everything as strings regardless of data type.
    $this->assertSame( '1', $links[0]->custom_link_id, 'Link ID' );
    $this->assertSame( 'Goodreads', $links[0]->text, 'Link text' );
    $this->assertSame( 'http://fakeurl.com/Goodreads.png', $links[0]->image_url, 'Link image' );
    $this->assertSame( 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars',
      $links[0]->url, 'Link URL' );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_meta
   */
  public function testNoLinksMeta() {
    $this->create_tables();

    $this->assertSame( array(), $this->book_info->get_book_review_links_meta( $this->post_id ) );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_meta
   */
  public function testNoActiveLinksMeta() {
    global $wpdb;

    $this->create_tables();

    // Add an inactive link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png',
        'active' => 0
      ),
      array( '%d', '%s', '%s', '%d' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( array(), $this->book_info->get_book_review_links_meta( $this->post_id ) );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_html
   */
  public function testImageLinkHtml() {
    global $wpdb;

    $html[] = '<li><a class="custom-link" href="https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars"><img src="http://fakeurl.com/Goodreads.png" alt="Goodreads"></a></li>';

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png'
      ),
      array( '%d', '%s', '%s' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( $html[0], $this->book_info->get_book_review_links_html( $this->post_id )[0] );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_html
   */
  public function testTextLinkHtml() {
    global $wpdb;

    $html[] = '<li><a class="custom-link" href="https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars">Goodreads</a></li>';

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads'
      ),
      array( '%d', '%s', '%s' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( $html[0], $this->book_info->get_book_review_links_html( $this->post_id )[0] );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_html
   */
  public function testNoUrlLinkHtml() {
    global $wpdb;

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( array(), $this->book_info->get_book_review_links_html( $this->post_id ) );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_html
   */
  public function testNoImageUrlNoTextLinkHtml() {
    global $wpdb;

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1
      ),
      array( '%d', '%s', '%s' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( array(), $this->book_info->get_book_review_links_html( $this->post_id ) );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_html
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testImageLinkHtmlTarget() {
    global $wpdb;

    $html[] = '<li><a class="custom-link" href="https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars" target="_blank"><img src="http://fakeurl.com/Goodreads.png" alt="Goodreads"></a></li>';

    $links_option = array(
      'book_review_target' => '1'
    );

    add_option( 'book_review_links', $links_option );

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads',
        'image_url' => 'http://fakeurl.com/Goodreads.png'
      ),
      array( '%d', '%s', '%s' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( $html[0], $this->book_info->get_book_review_links_html( $this->post_id )[0] );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_links_html
   * @covers Book_Review_Book_Info::get_link_target
   */
  public function testTextLinkHtmlTarget() {
    global $wpdb;

    $html[] = '<li><a class="custom-link" href="https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars" target="_blank">Goodreads</a></li>';

    $links_option = array(
      'book_review_target' => '1'
    );

    add_option( 'book_review_links', $links_option );

    $this->create_tables();

    // Add a link.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_links",
      array(
        'custom_link_id' => 1,
        'text' => 'Goodreads'
      ),
      array( '%d', '%s', '%s' )
    );

    // Add a link URL.
    $wpdb->insert(
      $wpdb->prefix . "book_review_custom_link_urls",
      array(
        'post_id' => $this->post_id,
        'custom_link_id' => 1,
        'url' => 'https://www.goodreads.com/book/show/11870085-the-fault-in-our-stars'
      ),
      array( '%d', '%s', '%s' )
    );

    $this->assertSame( $html[0], $this->book_info->get_book_review_links_html( $this->post_id )[0] );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_cover_url
   */
  public function testCoverUrl() {
    $url = 'http://example.com/wp-content/uploads/2014/04/The_Fault_in_Our_Stars_Book_Cover.jpg';

    update_post_meta( $this->post_id, 'book_review_cover_url', $url );

    $this->assertSame( $url, $this->book_info->get_book_review_cover_url( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_cover_url
   */
  public function testNoCoverUrl() {
    $this->assertSame( '', $this->book_info->get_book_review_cover_url( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_summary
   */
  public function testSummary() {
    $summary = 'Despite the tumor-shrinking medical miracle that has bought her a few years, Hazel has never been anything but terminal, her final chapter inscribed upon diagnosis. But when a gorgeous plot twist named Augustus Waters suddenly appears at Cancer Kid Support Group, Hazel\'s story is about to be completely rewritten.';

    update_post_meta( $this->post_id, 'book_review_summary', $summary);

    $this->assertSame( $summary, $this->book_info->get_book_review_summary( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_summary
   */
  public function testNoSummary() {
    $this->assertSame( '', $this->book_info->get_book_review_summary( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating
   */
  public function testRating() {
    update_post_meta( $this->post_id, 'book_review_rating', '4' );

    $this->assertSame( '4', $this->book_info->get_book_review_rating( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating
   */
  public function testNoRating() {
    $this->assertSame( '', $this->book_info->get_book_review_rating( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating_image
   */
  public function testDefaultRatingImage() {
    update_post_meta( $this->post_id, 'book_review_rating', '4' );

    $this->assertSame( 'http://example.org/wp-content/plugins/vagrant/www/github/book-review/src/includes/images/four-star.png', $this->book_info->get_book_review_rating_image( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating_image
   */
  public function testNoDefaultRatingImage() {
    update_post_meta( $this->post_id, 'book_review_rating', '-1' );

    $this->assertSame( '', $this->book_info->get_book_review_rating_image( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating_image
   */
  public function testCustomRatingImage() {
    $ratings_option = array(
      'book_review_rating_default' => '',
      'book_review_rating_image1' => 'http://url.to.image1.png'
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '1' );

    $this->assertSame( 'http://url.to.image1.png', $this->book_info->get_book_review_rating_image( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating_image
   */
  public function testNoCustomRatingImage() {
    $ratings_option = array(
      'book_review_rating_default' => ''
    );

    add_option( 'book_review_ratings', $ratings_option );
    update_post_meta( $this->post_id, 'book_review_rating', '-1' );

    $this->assertSame( '', $this->book_info->get_book_review_rating_image( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_rating_image
   */
  public function testCustomRatingImageNotSet() {
    $ratings_option = array(
      'book_review_rating_default' => ''
    );

    add_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( '', $this->book_info->get_book_review_rating_image( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_archive_post
   */
  public function testArchivePostChecked() {
    update_post_meta( $this->post_id, 'book_review_archive_post', '1' );

    $this->assertSame( '1', $this->book_info->get_book_review_archive_post( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_archive_post
   */
  public function testArchivePostNotChecked() {
    update_post_meta( $this->post_id, 'book_review_archive_post', '' );

    $this->assertSame( '', $this->book_info->get_book_review_archive_post( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_archive_post
   */
  public function testArchivePostNotSet() {
    $this->assertSame( '1', $this->book_info->get_book_review_archive_post( $this->post_id ) );
  }

  /**
   * @covers Book_Review_Book_Info::get_book_review_archive_post
   */
  public function testArchivePostNoMeta() {
    // Delete all post meta.
    delete_post_meta( $this->post_id, '_pingme' );
    delete_post_meta( $this->post_id, '_encloseme' );

    $this->assertNull( get_post_custom_keys( $this->post_id ), 'No meta data' );
    $this->assertSame( '1', $this->book_info->get_book_review_archive_post( $this->post_id ), 'Archive post' );
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

    // Delete options manually since removing the filter below won't delete them in the teardown.
    delete_option( 'book_review_version' );
    delete_option( 'book_review_links' );

    remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );

    $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_links' );
    $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'book_review_custom_link_urls' );
  }
}