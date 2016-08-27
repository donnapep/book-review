<?php

class Book_Review_Admin_Tests extends WP_UnitTestCase {
  protected $plugin;
  protected $plugin_name;
  protected $plugin_admin;

  public function setup() {
    global $wpdb;

    parent::setUp();

    $this->plugin = run_book_review();
    $this->plugin_name = $this->plugin->get_plugin_name();
    $this->plugin_admin = new Book_Review_Admin( $this->plugin_name, $this->plugin->get_version(),
      $this->plugin->get_settings(), $this->plugin->get_book_info() );

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
   * @covers Book_Review_Admin::enqueue_styles
   * TODO: Figure out how to test this depending on the screen.
   */
  public function testAdminStyleIsLoaded() {
  }

  /**
   * @covers Book_Review_Admin::enqueue_scripts
   * TODO: Figure out how to test this depending on the screen.
   */
  public function testAdminScriptIsLoaded() {
  }

  /**
   * @covers Book_Review_Admin::render_tabs
   */
  public function testDefaultTab() {
    $tabs = '<a class="nav-tab nav-tab-active" href="?page=book-review&#038;tab=appearance">Appearance</a><a class="nav-tab" href="?page=book-review&#038;tab=images">Rating Images</a><a class="nav-tab" href="?page=book-review&#038;tab=links">Links</a><a class="nav-tab" href="?page=book-review&#038;tab=fields">Custom Fields</a><a class="nav-tab" href="?page=book-review&#038;tab=advanced">Advanced</a>';

    $this->expectOutputString( $tabs, $this->plugin_admin->render_tabs() );
  }

  /**
   * @covers Book_Review_Admin::render_tabs
   */
  public function testNonDefaultTab() {
    $_GET['tab'] = 'links';
    $tabs = '<a class="nav-tab" href="?page=book-review&#038;tab=appearance">Appearance</a><a class="nav-tab" href="?page=book-review&#038;tab=images">Rating Images</a><a class="nav-tab nav-tab-active" href="?page=book-review&#038;tab=links">Links</a><a class="nav-tab" href="?page=book-review&#038;tab=fields">Custom Fields</a><a class="nav-tab" href="?page=book-review&#038;tab=advanced">Advanced</a>';

    $this->expectOutputString( $tabs, $this->plugin_admin->render_tabs() );
  }

  /**
   * @covers Book_Review_Admin::add_action_links
   */
  public function testAddActionLinks() {
    $action_link = array(
      'settings' => '<a href="http://example.org/wp-admin/options-general.php?page=book-review">Settings</a>'
    );

    $this->assertEquals( $action_link, $this->plugin_admin->add_action_links( array() ) );
  }

  /** The only thing that can be done when testing hooks is to ensure that one has been added.
   *  It's not possible to check for the proper callback when using OOP.
   */

  /**
   * @covers Book_Review_Admin::register_settings
   */
  public function testRegisterGeneralSettings() {
    global $wp_filter;

    $this->plugin_admin->register_settings();

    $this->assertArrayHasKey( 'sanitize_option_book_review_general', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::register_settings
   */
  public function testRegisterRatingsSettings() {
    global $wp_filter;

    $this->plugin_admin->register_settings();

    $this->assertArrayHasKey( 'sanitize_option_book_review_ratings', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::register_settings
   */
  public function testRegisterLinksSettings() {
    global $wp_filter;

    $this->plugin_admin->register_settings();

    $this->assertArrayHasKey( 'sanitize_option_book_review_links', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::register_settings
   */
  public function testRegisterFieldsSettings() {
    global $wp_filter;

    $this->plugin_admin->register_settings();

    $this->assertArrayHasKey( 'sanitize_option_book_review_fields', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::register_settings
   */
  public function testRegisterAdvancedSettings() {
    global $wp_filter;

    $this->plugin_admin->register_settings();

    $this->assertArrayHasKey( 'sanitize_option_book_review_advanced', $wp_filter );
  }

  /**
   * @covers Book_Review_Admin::sanitize_checkbox
   */
  public function testSanitizeValidCheckbox() {
    $this->assertEquals( '1', $this->plugin_admin->sanitize_checkbox( '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_checkbox
   */
  public function testSanitizeInvalidCheckbox() {
    $this->assertEquals( '', $this->plugin_admin->sanitize_checkbox( 'abc' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_text
   */
  public function testSanitizeValidText() {
    $this->assertSame( 'My Custom Text', $this->plugin_admin->sanitize_text( 'My Custom Text' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_text
   */
  public function testSanitizeEmptyText() {
    $this->assertSame( '', $this->plugin_admin->sanitize_text( '' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_text
   */
  public function testSanitizeInvalidText() {
    $this->assertSame( 'My Custom Text', $this->plugin_admin->sanitize_text( '<script>alert("Injected javascript")</script>My Custom Text' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSanitizeValidUrl() {
    $this->assertSame( 'http://url.to.image1.png', $this->plugin_admin->sanitize_url( 'http://url.to.image1.png' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSanitizeInvalidUrl() {
    $this->assertSame( 'http://url.to.image1.png', $this->plugin_admin->sanitize_url( 'url.to.image1.png' ) );
  }

  /**
   * @covers Book_Review_Admin::save_appearance
   */
  public function testSaveAppearance() {
    $input = array(
      'book_review_box_position' => 'top',
      'book_review_bg_color' => '#fff',
      'book_review_border_color' => '#000',
      'book_review_border_width' => 1,
      'book_review_post_types' => array(
        'post' => '1'
      )
    );

    $output = $this->plugin_admin->save_appearance( $input );

    $this->assertSame( 'top', $output['book_review_box_position'], 'Position' );
    $this->assertSame( '#fff', $output['book_review_bg_color'], 'Background Color' );
    $this->assertSame( '#000', $output['book_review_border_color'], 'Border Color' );
    $this->assertSame( 1, $output['book_review_border_width'], 'Border Width' );
    $this->assertSame( 2, count( $output['book_review_post_types'] ), 'Post type count' );
    $this->assertArrayHasKey( 'post', $output['book_review_post_types'], 'Post key' );
    $this->assertSame( '1', $output['book_review_post_types']['post'], 'Post value' );
    $this->assertArrayHasKey( 'page', $output['book_review_post_types'], 'Page key' );
    $this->assertSame( '0', $output['book_review_post_types']['page'], 'Page value' );
  }

  /**
   * @covers Book_Review_Admin::save_appearance
   */
  public function testSaveInvalidPostType() {
    $input = array(
      'book_review_post_types' => array(
        'invalid' => '1'
      )
    );

    $output = $this->plugin_admin->save_appearance( $input );

    $this->assertArrayHasKey( 'book_review_post_types', $output, 'Post type' );
    $this->assertArrayNotHasKey( 'invalid', $output['book_review_post_types'] );
  }

  /**
   * @covers Book_Review_Admin::save_appearance
   */
  public function testSaveInvalidBorderWidth() {
    $input = array(
      'book_review_border_width' => '-5',
    );

    $output = $this->plugin_admin->save_appearance( $input );

    $this->assertArrayNotHasKey( 'book_review_border_width', $output );
  }

  /**
   * @covers Book_Review_Admin::save_appearance
   */
  public function testSaveNoAppearance() {
    $output = $this->plugin_admin->save_appearance();

    $this->assertSame( 2, count( $output['book_review_post_types'] ), 'Post type count' );
    $this->assertArrayHasKey( 'post', $output['book_review_post_types'], 'Post key' );
    $this->assertSame( '0', $output['book_review_post_types']['post'], 'Post value' );
    $this->assertArrayHasKey( 'page', $output['book_review_post_types'], 'Page key' );
    $this->assertSame( '0', $output['book_review_post_types']['page'], 'Page value' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_position
   */
  public function testSanitizeValidPosition() {
    $this->assertEquals( 'bottom', $this->plugin_admin->sanitize_position( 'bottom' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_position
   */
  public function testSanitizeInvalidPosition() {
    $this->assertEquals( 'top', $this->plugin_admin->sanitize_position( 'left' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_color
   */
  public function testSanitizeValidColor() {
    $this->assertEquals( '#ffffff', $this->plugin_admin->sanitize_color( '#ffffff' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_color
   */
  public function testSanitizeInvalidColor() {
    $this->assertEquals( '', $this->plugin_admin->sanitize_color( '#zab' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_color
   */
  public function testSanitizeEmptyColor() {
    $this->assertEquals( '', $this->plugin_admin->sanitize_color( '' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_border_width
   */
  public function testSanitizeValidBorderWidth() {
    $width = $this->plugin_admin->sanitize_border_width( '1' );

    $this->assertSame( 1, $width, 'Correct value' );
    $this->assertEquals( 0, count( get_settings_errors( 'book_review_appearance' ) ), 'No error' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_border_width
   */
  public function testSanitizeZeroBorderWidth() {
    $width = $this->plugin_admin->sanitize_border_width( '0' );

    $this->assertSame( 0, $width, 'Correct value' );
    $this->assertEquals( 0, count( get_settings_errors( 'book_review_appearance' ) ), 'No error' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_border_width
   */
  public function testSanitizeNonIntegerBorderWidth() {
    global $wp_settings_errors;

    $this->plugin_admin->sanitize_border_width( 'abc' );

    $this->assertEquals( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertEquals( 'non-integer-border-width-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_border_width
   */
  public function testSanitizeNegativeBorderWidth() {
    global $wp_settings_errors;

    $this->plugin_admin->sanitize_border_width( '-5' );

    $this->assertEquals( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertEquals( 'negative-border-width-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_border_width
   */
  public function testSanitizeBorderWidthPreviousValue() {
    $general_option = array(
      'book_review_border_width' => 10
    );

    update_option( 'book_review_general', $general_option );

    $this->assertSame( 10, $this->plugin_admin->sanitize_border_width( '-5' ), 'Correct value' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_border_width
   */
  public function testSanitizeBorderWidthNoPreviousValue() {
    $this->assertFalse( $this->plugin_admin->sanitize_border_width( '-5' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_post_type
   */
  public function testSanitizeValidPostType() {
    $this->assertSame( '1', $this->plugin_admin->sanitize_post_type( 'post', '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_post_type
   */
  public function testSanitizeInvalidPostType() {
    $this->assertFalse( $this->plugin_admin->sanitize_post_type( 'invalid', '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_post_type
   */
  public function testSanitizeInvalidPostTypeValue() {
    $this->assertFalse( $this->plugin_admin->sanitize_post_type( 'post', 'abc' ) );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveRatingImageUrl() {
    $input = array(
      'book_review_rating_image1' => 'http://url.to.image1.png'
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertSame( 'http://url.to.image1.png', $output['book_review_rating_image1'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveRatingImageUrlWithDefault() {
    $input = array(
      'book_review_rating_default' => '1',
      'book_review_rating_image1' => 'http://url.to.image1.png'
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertSame( 'http://url.to.image1.png', $output['book_review_rating_image1'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveEmptyRatingImageUrl() {
    $input = array(
      'book_review_rating_image1' => ''
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertArrayNotHasKey( 'book_review_rating_image1', $output );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveExcerpts() {
    $input = array(
      'book_review_rating_home' => ' 1 '
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertSame( '1', $output['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveInvalidExcerpts() {
    $input = array(
      'book_review_rating_home' => '5'
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertSame( '', $output['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveNoExcerpts() {
    $output = $this->plugin_admin->save_rating_images();

    $this->assertSame( '', $output['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveDefaultRatingImages() {
    $input = array(
      'book_review_rating_default' => ' 1 '
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertSame( '1', $output['book_review_rating_default'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveInvalidDefaultRatingImages() {
    $input = array(
      'book_review_rating_default' => '-5'
    );

    $output = $this->plugin_admin->save_rating_images( $input );

    $this->assertSame( '', $output['book_review_rating_default'] );
  }

  /**
   * @covers Book_Review_Admin::save_rating_images
   */
  public function testSaveNoDefaultRatingImages() {
    $output = $this->plugin_admin->save_rating_images();

    $this->assertSame( '', $output['book_review_rating_default'] );
  }

  /**
   * @covers Book_Review_Admin::sanitize_rating_image
   */
  public function testSanitizeValidRatingImage() {
    $this->assertSame( 'http://url.to.image1.png',
      $this->plugin_admin->sanitize_rating_image( 'book_review_rating_image1', 'http://url.to.image1.png', '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_rating_image
   */
  public function testSanitizeInvalidRatingImage() {
    $this->assertSame( 'http://%20url.to.image1.png%20',
      $this->plugin_admin->sanitize_rating_image( 'book_review_rating_image1', ' url.to.image1.png ', '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_rating_image
   */
  public function testSanitizeRatingImageNoDefault() {
    $this->assertSame( 'http://url.to.image1.png',
      $this->plugin_admin->sanitize_rating_image( 'book_review_rating_image1', 'http://url.to.image1.png', '' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_rating_image
   */
  public function testSanitizeRatingImageError() {
    global $wp_settings_errors;

    $this->plugin_admin->sanitize_rating_image( 'book_review_rating_image1', '', '' );

    $this->assertEquals( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertEquals( 'rating-image-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_rating_image
   */
  public function testSanitizeRatingImagePreviousValue() {
    $ratings_option = array(
      'book_review_rating_image1' => 'http://url.to.image1.png'
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( 'http://url.to.image1.png',
      $this->plugin_admin->sanitize_rating_image( 'book_review_rating_image1', '', '' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_rating_image
   */
  public function testSanitizeRatingImageNoPreviousValue() {
    $this->assertSame( '', $this->plugin_admin->sanitize_rating_image( 'book_review_rating_image1', '', '' ) );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_checkbox
   */
  public function testSaveActiveSiteLink() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['active'] = '1';
    $input['sites']['book_review_goodreads']['type'] = 'button';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( $input['sites']['book_review_goodreads']['active'], $output['sites']['book_review_goodreads']['active'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSaveNoGoodreadsSiteLink() {
    $input = array();

    $input['sites']['book_review_goodreads']['type'] = 'button';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_goodreads']['active'] );

  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSaveNoAmazonSiteLink() {
    $input = array();

    $input['sites']['book_review_barnes_noble']['type'] = 'button';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_barnes_noble']['active'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_link_type
   */
  public function testSaveSiteLinkType() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'text';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( $input['sites']['book_review_goodreads']['type'], $output['sites']['book_review_goodreads']['type'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_link_type
   */
  public function testSaveInvalidSiteLinkType() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'abc';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( 'button', $output['sites']['book_review_goodreads']['type'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_text
   */
  public function testSaveSiteLinkText() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['text'] = 'Goodreads';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( $input['sites']['book_review_goodreads']['text'], $output['sites']['book_review_goodreads']['text'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_text
   */
  public function testSaveInvalidSiteLinkText() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['text'] = '<script>alert("Injected javascript")</script>My Custom Text';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( 'My Custom Text', $output['sites']['book_review_goodreads']['text'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveSiteLinkUrl() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['url'] = 'http://url.to.Goodreads.png';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( $input['sites']['book_review_goodreads']['url'], $output['sites']['book_review_goodreads']['url'], 'URL' );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveSiteLinkUrlNoError() {
     global $wp_settings_errors;

    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['active'] = '1';
    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = 'http://url.to.Goodreads.png';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertEquals( 0, count( $wp_settings_errors ), 'Error count' );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveInvalidSiteLinkUrl() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = 'abc://url.to.Goodreads.png';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveInvalidSiteLinkUrlError() {
     global $wp_settings_errors;

    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['active'] = '1';
    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = 'abc://url.to.Goodreads.png';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertEquals( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertEquals( 'custom-image-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveInactiveInvalidSiteLinkUrlNoError() {
     global $wp_settings_errors;

    $input = array();

    $input['sites']['book_review_goodreads']['active'] = '';
    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = 'abc://url.to.Goodreads.png';
    $this->plugin_admin->save_links( $input );

    $this->assertEquals( 0, count( $wp_settings_errors ) );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveActiveInvalidSiteLinkUrlNoError() {
     global $wp_settings_errors;

    $input = array();

    $input['sites']['book_review_goodreads']['active'] = '1';
    $input['sites']['book_review_goodreads']['type'] = 'button';
    $input['sites']['book_review_goodreads']['url'] = 'abc://url.to.Goodreads.png';
    $this->plugin_admin->save_links( $input );

    $this->assertEquals( 0, count( $wp_settings_errors ) );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveEmptySiteLinkUrl() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = '';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveEmptySiteLinkUrlError() {
    global $wp_settings_errors;

    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['active'] = '1';
    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = '';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertEquals( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertEquals( 'custom-image-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveInvalidSiteLinkUrlNoLinkType() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['url'] = 'abc://url.to.Goodreads.png';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::sanitize_url
   */
  public function testSaveInvalidSiteLinkUrlIncorrectLinkType() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'text';
    $input['sites']['book_review_goodreads']['url'] = 'abc://url.to.Goodreads.png';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSavePreviousSiteLinkType() {
    $input = array();
    $output = array();

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

    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = '';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( 'text', $output['sites']['book_review_goodreads']['type'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testNoPreviousSiteLinkType() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = '';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( 'button', $output['sites']['book_review_goodreads']['type'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSavePreviousSiteLinkUrl() {
    $input = array();
    $output = array();

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

    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = '';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( 'http://fakeurl.com/goodreads.png', $output['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testNoPreviousSiteLinkUrl() {
    $input = array();
    $output = array();

    $input['sites']['book_review_goodreads']['type'] = 'custom';
    $input['sites']['book_review_goodreads']['url'] = '';
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::save_link
   */
  public function testSaveInvalidLinkId() {
    global $wpdb;

    $input = array();
    $output = array();

    $this->create_tables();

    // Add row.
    $input[1]['id'] = '-1';
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->save_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );

    $this->assertEquals( 1, count( $results ) );

    foreach ( $results as $result ) {
      $this->assertEquals( 1, $result->custom_link_id, 'Link ID is correct' );
      $this->assertEquals( $input[1]['text'], $result->text, 'Link text is correct' );
      $this->assertEquals( $input[1]['image'], $result->image_url, 'Link Image URL is correct' );
      $this->assertEquals( 1, $result->active, 'Active is correct' );
    }

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::save_link
   */
  public function testSaveEmptyLinkText() {
    global $wpdb;

    $input = array();
    $output = array();

    $this->create_tables();

    // Add row.
    $input[1]['text'] = '';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->save_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );

    $this->assertEquals( 0, count( $results ) );

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::save_link
   */
  public function testInsertLink() {
    global $wpdb;

    $input = array();
    $output = array();

    $this->create_tables();

    // Add row.
    $input[1]['id'] = '';
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->save_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );

    $this->assertEquals( 1, count( $results ), 'Single row added to book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( 1, $result->custom_link_id, 'Link ID is correct' );
      $this->assertEquals( $input[1]['text'], $result->text, 'Link text is correct' );
      $this->assertEquals( $input[1]['image'], $result->image_url, 'Link Image URL is correct' );
      $this->assertEquals( $input[1]['active'], $result->active, 'Active is correct' );
    }

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Admin::save_links
   * @covers Book_Review_Admin::save_link
   */
  public function testUpdateLink() {
    global $wpdb;

    $input = array();
    $output = array();

    $this->create_tables();

    // Add row.
    $input[1]['id'] = '';
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->save_links( $input );

    // Update row.
    $input[1]['id'] = '1';
    $input[1]['text'] = 'Amazon.com';
    $input[1]['image'] = 'http://url.to.Amazon.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->save_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );

    $this->assertEquals( 1, count( $results ), 'Single row in book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( $input[1]['id'], $result->custom_link_id, 'ID is correct' );
      $this->assertEquals( $input[1]['text'], $result->text, 'Link text is correct' );
      $this->assertEquals( $input[1]['image'], $result->image_url, 'Link Image URL is correct' );
      $this->assertEquals( $input[1]['active'], $result->active, 'Active is correct' );
    }

    $this->drop_tables();
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSaveLinkTarget() {
    $input = array(
      'book_review_target' => '1'
    );
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '1', $output['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSaveInvalidLinkTarget() {
    $input = array(
      'book_review_target' => '5'
    );
    $output = $this->plugin_admin->save_links( $input );

    $this->assertSame( '', $output['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::save_links
   */
  public function testSaveNoLinkTarget() {
    $output = $this->plugin_admin->save_links();

    $this->assertSame( '', $output['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_id
   */
  public function testSanitizeValidLinkId() {
    $this->assertSame( 1, $this->plugin_admin->sanitize_link_id( '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_id
   */
  public function testSanitizeZeroLinkId() {
    $this->assertSame( 0, $this->plugin_admin->sanitize_link_id( '0' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_id
   */
  public function testSanitizeNegativeLinkId() {
    $this->assertSame( 0, $this->plugin_admin->sanitize_link_id( '-5' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_id
   */
  public function testSanitizeInvalidLinkId() {
    $this->assertSame( 0, $this->plugin_admin->sanitize_link_id( 'invalid' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_text
   */
  public function testSanitizeValidLinkText() {
    $this->assertSame( 'My Custom Link', $this->plugin_admin->sanitize_link_text( 'My Custom Link' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_text
   */
  public function testSanitizeEmptyLinkText() {
    $this->assertSame( '', $this->plugin_admin->sanitize_link_text( '' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_text
   */
  public function testSanitizeInvalidLinkText() {
    $this->assertSame( 'My Custom Link', $this->plugin_admin->sanitize_link_text( '<script>alert("Injected javascript")</script>My Custom Link' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_text
   */
  public function testLinkTextError() {
    global $wp_settings_errors;

    $this->plugin_admin->sanitize_link_text( '' );

    $this->assertEquals( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertEquals( 'link-text-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_status
   */
  public function testSanitizeActiveLinkStatus() {
    $this->assertSame( 1, $this->plugin_admin->sanitize_link_status( '1' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_status
   */
  public function testSanitizeInactiveLinkStatus() {
    $this->assertSame( 1, $this->plugin_admin->sanitize_link_status( '0' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_status
   */
  public function testSanitizeNegativeLinkStatus() {
    $this->assertSame( 1, $this->plugin_admin->sanitize_link_status( '-5' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_status
   */
  public function testSanitizeInvalidLinkStatus() {
    $this->assertSame( 1, $this->plugin_admin->sanitize_link_status( 'invalid' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_type
   */
  public function testSanitizeValidLinkType() {
    $this->assertSame( 'text', $this->plugin_admin->sanitize_link_type( 'text' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_link_type
   */
  public function testSanitizeInvalidLinkType() {
    $this->assertSame( 'button', $this->plugin_admin->sanitize_link_type( 'invalid' ) );
  }

  /**
   * @covers Book_Review_Admin::save_custom_fields
   */
  public function testSaveValidCustomField() {
    $input = array(
      'fields' => array(
        'book_review_565adc1c2d403' => array(
          'label' => 'Illustrator'
        )
      )
    );

    $output = $this->plugin_admin->save_custom_fields( $input );

    $this->assertSame( 'Illustrator', $output['fields']['book_review_565adc1c2d403']['label'] );
  }

  /**
   * @covers Book_Review_Admin::save_custom_fields
   */
  public function testSaveNoCustomField() {
    $output = $this->plugin_admin->save_custom_fields();

    $this->assertArrayNotHasKey( 'fields', $output, 'No fields key' );
    $this->assertSame( array(), $output, 'Return value' );
  }

  /**
   * @covers Book_Review_Admin::save_custom_fields
   */
  public function testCustomFieldError() {
    global $wp_settings_errors;

    $input = array(
      'fields' => array(
        'book_review_565adc1c2d403' => array(
          'label' => ''
        )
      )
    );

    $output = $this->plugin_admin->save_custom_fields( $input );

    $this->assertSame( 1, count( $wp_settings_errors ), 'Error count' );
    $this->assertSame( 'custom-field-error', $wp_settings_errors[0]['code'], 'Error code' );
  }

  /**
   * @covers Book_Review_Admin::save_advanced
   */
  public function testSaveAdvanced() {
    $input = array(
      'book_review_api_key' => 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0',
      'book_review_country' => 'SO'
    );

    $output = $this->plugin_admin->save_advanced( $input );

    $this->assertSame( 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0', $output['book_review_api_key'], 'API key' );
    $this->assertSame( 'SO', $output['book_review_country'], 'Country' );
  }

  /**
   * @covers Book_Review_Admin::save_advanced
   */
  public function testSaveNoAdvanced() {
    $this->assertSame( array(), $this->plugin_admin->save_advanced() );
  }

  /**
   * @covers Book_Review_Admin::sanitize_country
   */
  public function testSanitizeValidCountry() {
    $this->assertSame( 'CA', $this->plugin_admin->sanitize_country( 'CA' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_country
   */
  public function testSanitizeInvalidCountry() {
    $this->assertSame( '', $this->plugin_admin->sanitize_country( 'invalid' ) );
  }

  /**
   * @covers Book_Review_Admin::sanitize_country
   */
  public function testSanitizeEmptyCountry() {
    $this->assertSame( '', $this->plugin_admin->sanitize_country( '' ) );
  }

  /**
   * @covers Book_Review_Admin::column_heading
   */
  public function testColumnHeading() {
    $output = $this->plugin_admin->column_heading( array( 'title' => 'Title' ) );

    $this->assertArrayHasKey( 'title', $output, 'Title Column' );
    $this->assertArrayHasKey( 'rating', $output, 'Rating Column' );
  }

  /**
   * @covers Book_Review_Admin::column_content
   */
  public function testColumnContentValidRating() {
    $post_id = $this->factory->post->create();
    $content = '<img src="' . plugin_dir_url( dirname(__FILE__) ) . 'src/includes/images/three-star.png" class="book_review_column_rating">';

    update_post_meta( $post_id, 'book_review_rating', '3' );

    $this->expectOutputString( $content, $this->plugin_admin->column_content( 'rating', $post_id ) );
  }

  /**
   * @covers Book_Review_Admin::column_content
   */
  public function testColumnContentNoRating() {
    $post_id = $this->factory->post->create();

    update_post_meta( $post_id, 'book_review_rating', '-1' );

    $this->expectOutputString( '', $this->plugin_admin->column_content( 'rating', $post_id ) );
  }

  /**
   * @covers Book_Review_Admin::column_content
   */
  public function testColumnContentRatingNotSet() {
    $post_id = $this->factory->post->create();

    $this->expectOutputString( '', $this->plugin_admin->column_content( 'rating', $post_id ) );
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