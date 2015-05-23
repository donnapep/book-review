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
    $this->plugin_admin = new Book_Review_Admin( $this->plugin_name, $this->plugin->get_version() );

    require_once BOOK_REVIEW_PLUGIN_DIR . 'includes/class-book-review-activator.php';
    Book_Review_Activator::activate( false );

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
  public function testDefaultActiveTab() {
    $tabs = '<a class="nav-tab nav-tab-active" href="?page=book-review&#038;tab=appearance">Appearance</a><a class="nav-tab" href="?page=book-review&#038;tab=images">Rating Images</a><a class="nav-tab" href="?page=book-review&#038;tab=links">Links</a><a class="nav-tab" href="?page=book-review&#038;tab=advanced">Advanced</a>';

    $this->expectOutputString( $tabs, $this->plugin_admin->render_tabs() );
  }

  /**
   * @covers Book_Review_Admin::render_tabs
   */
  public function testLinksActiveTab() {
    $_GET['tab'] = 'links';
    $tabs = '<a class="nav-tab" href="?page=book-review&#038;tab=appearance">Appearance</a><a class="nav-tab" href="?page=book-review&#038;tab=images">Rating Images</a><a class="nav-tab nav-tab-active" href="?page=book-review&#038;tab=links">Links</a><a class="nav-tab" href="?page=book-review&#038;tab=advanced">Advanced</a>';

    $this->expectOutputString( $tabs, $this->plugin_admin->render_tabs() );
  }

  /**
   * @covers Book_Review_Admin::add_action_links
   */
  public function testAddActionLinks() {
    $action_link = array(
      'settings' => '<a href="' . admin_url( 'options-general.php?page=' .
        $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) .
      '</a>');

    $this->assertEquals( $action_link, $this->plugin_admin->add_action_links( array() ));
  }

  /**
   * @covers Book_Review_Admin::init_menu
   */
  public function testRegisterSettings() {
    // TODO
  }

  /**
   * @covers Book_Review_Admin::validate_appearance
   */
  public function testValidateBorderWidth() {
    $output = array();
    $input = array();

    $input['book_review_box_position'] = 'top';
    $input['book_review_bg_color'] = '#ffffff';
    $input['book_review_border_color'] = '#e0e0e0';
    $input['book_review_date_format'] = 'none';
    $input['book_review_border_width'] = '1';
    $output = $this->plugin_admin->validate_appearance( $input );

    $this->assertInternalType( 'integer', $output['book_review_border_width'], 'Border Width has correct type' );
    $this->assertEquals( 1, $output['book_review_border_width'], 'Border Width has correct value' );
    $this->assertEquals( 0, count( get_settings_errors( 'book_review_appearance' ) ), 'No errors' );
  }

  /**
   * @covers Book_Review_Admin::validate_appearance
   */
  public function testValidateZeroBorderWidth() {
    $output = array();
    $input = array();

    $input['book_review_box_position'] = 'top';
    $input['book_review_bg_color'] = '#ffffff';
    $input['book_review_border_color'] = '#e0e0e0';
    $input['book_review_date_format'] = 'none';
    $input['book_review_border_width'] = '0';
    $output = $this->plugin_admin->validate_appearance( $input );

    $this->assertInternalType( 'integer', $output['book_review_border_width'], 'Border Width has correct type' );
    $this->assertEquals( 0, $output['book_review_border_width'], 'Border Width has correct value' );
    $this->assertEquals( 0, count( get_settings_errors( 'book_review_appearance' ) ), 'No errors' );
  }

  /**
   * @covers Book_Review_Admin::validate_appearance
   */
  public function testValidateEmptyBorderWidth() {
    $output = array();
    $input = array();

    $input['book_review_box_position'] = 'top';
    $input['book_review_bg_color'] = '#ffffff';
    $input['book_review_border_color'] = '#e0e0e0';
    $input['book_review_date_format'] = 'none';
    $input['book_review_border_width'] = '';
    $output = $this->plugin_admin->validate_appearance( $input );

    $this->assertInternalType( 'integer', $output['book_review_border_width'], 'Border Width has correct type' );
    $this->assertEquals( 0, $output['book_review_border_width'], 'Border Width has correct value' );
    $this->assertEquals( 1, count( get_settings_errors( 'book_review_appearance' ) ), 'Error saving empty Border Width' );
  }

  /**
   * @covers Book_Review_Admin::validate_appearance
   */
  public function testValidateInvalidBorderWidth() {
    $output = array();
    $input = array();

    $input['book_review_box_position'] = 'top';
    $input['book_review_bg_color'] = '#ffffff';
    $input['book_review_border_color'] = '#e0e0e0';
    $input['book_review_date_format'] = 'none';
    $input['book_review_border_width'] = 'abc';
    $output = $this->plugin_admin->validate_appearance( $input );

    $this->assertInternalType( 'integer', $output['book_review_border_width'], 'Border Width has correct type' );
    $this->assertEquals( 0, $output['book_review_border_width'], 'Border Width has correct value' );
    $this->assertEquals( 1, count( get_settings_errors( 'book_review_appearance' ) ), 'Error saving invalid Border Width' );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateExcerpts() {
    $output = array();
    $input = array();

    $input['book_review_rating_home'] = '1';
    $input['book_review_rating_default'] = '1';
    $input['book_review_rating_image1'] = '';
    $input['book_review_rating_image2'] = '';
    $input['book_review_rating_image3'] = '';
    $input['book_review_rating_image4'] = '';
    $input['book_review_rating_image5'] = '';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertEquals( '1', $output['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateNoExcerpts() {
    $output = array();
    $input = array();

    $input['book_review_rating_default'] = '1';
    $input['book_review_rating_image1'] = '';
    $input['book_review_rating_image2'] = '';
    $input['book_review_rating_image3'] = '';
    $input['book_review_rating_image4'] = '';
    $input['book_review_rating_image5'] = '';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertEquals( '', $output['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateDefaultImages() {
    $output = array();
    $input = array();

    $input['book_review_rating_default'] = '1';
    $input['book_review_rating_image1'] = '';
    $input['book_review_rating_image2'] = '';
    $input['book_review_rating_image3'] = '';
    $input['book_review_rating_image4'] = '';
    $input['book_review_rating_image5'] = '';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertEquals( '1', $output['book_review_rating_default'] );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateImageURLs() {
    $output = array();
    $input = array();

    $input['book_review_rating_image1'] = 'http://url.to.image1.png';
    $input['book_review_rating_image2'] = 'http://url.to.image2.png';
    $input['book_review_rating_image3'] = 'http://url.to.image3.png';
    $input['book_review_rating_image4'] = 'http://url.to.image4.png';
    $input['book_review_rating_image5'] = 'http://url.to.image5.png';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertEquals( '', $output['book_review_rating_default'], 'Default Rating Images has correct value' );
    $this->assertEquals( $input['book_review_rating_image1'], $output['book_review_rating_image1'], 'Image 1 has correct value' );
    $this->assertEquals( $input['book_review_rating_image2'], $output['book_review_rating_image2'], 'Image 2 has correct value' );
    $this->assertEquals( $input['book_review_rating_image3'], $output['book_review_rating_image3'], 'Image 3 has correct value' );
    $this->assertEquals( $input['book_review_rating_image4'], $output['book_review_rating_image4'], 'Image 4 has correct value' );
    $this->assertEquals( $input['book_review_rating_image5'], $output['book_review_rating_image5'], 'Image 5 has correct value' );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateEmptyImageURLs() {
    $output = array();
    $input = array();

    $input['book_review_rating_image1'] = '';
    $input['book_review_rating_image2'] = '';
    $input['book_review_rating_image3'] = '';
    $input['book_review_rating_image4'] = '';
    $input['book_review_rating_image5'] = '';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertArrayNotHasKey( 'book_review_rating_image1', $output, 'Image 1 not saved' );
    $this->assertArrayNotHasKey( 'book_review_rating_image2', $output, 'Image 2 not saved' );
    $this->assertArrayNotHasKey( 'book_review_rating_image3', $output, 'Image 3 not saved' );
    $this->assertArrayNotHasKey( 'book_review_rating_image4', $output, 'Image 4 not saved' );
    $this->assertArrayNotHasKey( 'book_review_rating_image5', $output, 'Image 5 not saved' );
    $this->assertEquals( 1, count( get_settings_errors( 'book_review_ratings' ) ), 'Error saving empty image URLs' );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateInvalidImageURLs() {
    $output = array();
    $input = array();

    $input['book_review_rating_image1'] = 'url.to.image1.png';
    $input['book_review_rating_image2'] = 'url.to.image2.png';
    $input['book_review_rating_image3'] = 'url.to.image3.png';
    $input['book_review_rating_image4'] = 'url.to.image4.png';
    $input['book_review_rating_image5'] = 'url.to.image5.png';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertEquals( 'http://url.to.image1.png', $output['book_review_rating_image1'], 'Image 1 has correct value' );
    $this->assertEquals( 'http://url.to.image2.png', $output['book_review_rating_image2'], 'Image 2 has correct value' );
    $this->assertEquals( 'http://url.to.image3.png', $output['book_review_rating_image3'], 'Image 3 has correct value' );
    $this->assertEquals( 'http://url.to.image4.png', $output['book_review_rating_image4'], 'Image 4 has correct value' );
    $this->assertEquals( 'http://url.to.image5.png', $output['book_review_rating_image5'], 'Image 5 has correct value' );
  }

  /**
   * @covers Book_Review_Admin::validate_rating_images
   */
  public function testValidateDefaultImagesWithImageURLs() {
    $output = array();
    $input = array();

    $input['book_review_rating_default'] = '1';
    $input['book_review_rating_image1'] = '';
    $input['book_review_rating_image2'] = 'http://url.to.image2.png';
    $input['book_review_rating_image3'] = 'http://url.to.image3.gif';
    $input['book_review_rating_image4'] = 'http://url.to.image4.bmp';
    $input['book_review_rating_image5'] = 'http://url.to.image5.jpg';
    $output = $this->plugin_admin->validate_rating_images( $input );

    $this->assertEquals( '', $output['book_review_rating_image1'], 'Image 1 has correct value' );
    $this->assertEquals( 'http://url.to.image2.png', $output['book_review_rating_image2'], 'Image 2 has correct value' );
    $this->assertEquals( 'http://url.to.image3.gif', $output['book_review_rating_image3'], 'Image 3 has correct value' );
    $this->assertEquals( 'http://url.to.image4.bmp', $output['book_review_rating_image4'], 'Image 4 has correct value' );
    $this->assertEquals( 'http://url.to.image5.jpg', $output['book_review_rating_image5'], 'Image 5 has correct value' );
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testValidateLinkTarget() {
    $output = array();
    $input = array();

    $input['book_review_target'] = '1';
    $output = $this->plugin_admin->validate_links( $input );

    $this->assertEquals( '1', $output['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testValidateNoLinkTarget() {
    $output = array();
    $input = array();

    $output = $this->plugin_admin->validate_links( $input );

    $this->assertEquals( '', $output['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testInsertCustomLink() {
    global $wpdb;

    $output = array();
    $input = array();

    // Add row.
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->validate_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );
    $this->assertEquals( 1, count( $results ), 'Single row added to book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( $input[1]['text'], $result->text, 'Link text is correct' );
      $this->assertEquals( $input[1]['image'], $result->image_url, 'Link Image URL is correct' );
      $this->assertEquals( $input[1]['active'], $result->active, 'Active is correct' );
    }
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testUpdateCustomLink() {
    global $wpdb;

    $output = array();
    $input = array();

    // Add row.
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->validate_links( $input );

    // Update row.
    $input[1]['id'] = '1';
    $input[1]['text'] = 'Amazon.com';
    $input[1]['image'] = 'http://url.to.Amazon.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->validate_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );
    $this->assertEquals( 1, count( $results ), 'Single row in book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( $input[1]['id'], $result->custom_link_id, 'ID is correct' );
      $this->assertEquals( $input[1]['text'], $result->text, 'Link text is correct' );
      $this->assertEquals( $input[1]['image'], $result->image_url, 'Link Image URL is correct' );
      $this->assertEquals( $input[1]['active'], $result->active, 'Active is correct' );
    }
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testValidateEmptyLinkText() {
    global $wpdb;

    $output = array();
    $input = array();

    // Add row.
    $input[1]['text'] = '';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->validate_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );
    $this->assertEquals( 0, count( $results ), 'Single row added to book_review_custom_links' );
    $this->assertEquals( 1, count( get_settings_errors( 'book_review_links' ) ), 'Error saving empty Link Text' );
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testValidateInvalidLinkText() {
    global $wpdb;

    $output = array();
    $input = array();

    // Add row.
    $input[1]['text'] = ' <div> This is  some markup </div> ';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->validate_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );
    $this->assertEquals( 1, count( $results ), 'Single row added to book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( 'This is some markup', $result->text, 'Link text is correct' );
    }
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testValidateInvalidLinkImageURL() {
    global $wpdb;

    $output = array();
    $input = array();

    // Add row.
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'url.to.Goodreads.png';
    $input[1]['active'] = '1';
    $this->plugin_admin->validate_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );
    $this->assertEquals( 1, count( $results ), 'Single row added to book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( 'http://url.to.Goodreads.png', $result->image_url, 'Link Image URL is correct' );
    }
  }

  /**
   * @covers Book_Review_Admin::validate_links
   */
  public function testValidateInactiveLink() {
    global $wpdb;

    $output = array();
    $input = array();

    // Add row.
    $input[1]['text'] = 'Goodreads';
    $input[1]['image'] = 'http://url.to.Goodreads.png';
    $this->plugin_admin->validate_links( $input );

    $results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'book_review_custom_links' );
    $this->assertEquals( 1, count( $results ), 'Single row added to book_review_custom_links' );

    foreach ( $results as $result ) {
      $this->assertEquals( '0', $result->active, 'Custom Link is inactive' );
    }
  }

  /**
   * @covers Book_Review_Admin::validate_advanced
   */
  public function testValidateAPIKey() {
    $output = array();
    $input = array();

    $input['book_review_api_key'] = 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0';
    $output = $this->plugin_admin->validate_advanced( $input );

    $this->assertEquals( 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0', $output['book_review_api_key'] );
  }

  /**
   * @covers Book_Review_Admin::validate_advanced
   */
  public function testValidateEmptyAPIKey() {
    $output = array();
    $input = array();

    $output = $this->plugin_admin->validate_advanced( $input );

    $this->assertEquals( '', $output['book_review_api_key'] );
  }

  /**
   * @covers Book_Review_Admin::validate_advanced
   */
  public function testValidateInvalidAPIKey() {
    $output = array();
    $input = array();

    $input['book_review_api_key'] = '<script>alert("Injected javascript")</script>AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0';
    $output = $this->plugin_admin->validate_advanced( $input );

    $this->assertEquals( 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0', $output['book_review_api_key'] );
  }

  /**
   * @covers Book_Review_Admin::render_date_format_field
   */
  public function testDefaultDateFormat() {
    $short_date = date( 'n/j/Y', current_time( 'timestamp', 0 ) );
    $european_date = date( 'j/n/Y', current_time( 'timestamp', 0 ) );
    $medium_date = date( 'M j Y', current_time( 'timestamp', 0 ) );
    $long_date = date( 'F j, Y', current_time( 'timestamp', 0 ) );
    $date_format = '<option value="none" >None</option><option value="short" >'. $short_date . '</option><option value="european" >' . $european_date . '</option><option value="medium" selected="selected">' . $medium_date . '</option><option value="long" >' . $long_date . '</option>';

    // Set default date format.
    $options = array(
      'book_review_date_format' => 'medium'
    );

    add_option( 'book_review_general', $options );

    $this->expectOutputString( $date_format, $this->plugin_admin->render_date_format_field() );
  }

  /**
   * @covers Book_Review_Admin::render_date_format_field
   */
  public function testNoDefaultDateFormat() {
    $short_date = date( 'n/j/Y', current_time( 'timestamp', 0 ) );
    $european_date = date( 'j/n/Y', current_time( 'timestamp', 0 ) );
    $medium_date = date( 'M j Y', current_time( 'timestamp', 0 ) );
    $long_date = date( 'F j, Y', current_time( 'timestamp', 0 ) );
    $date_format = '<option value="none" selected="selected">None</option><option value="short" >' . $short_date . '</option><option value="european" >' . $european_date . '</option><option value="medium" >' . $medium_date . '</option><option value="long" >' . $long_date . '</option>';

    $this->expectOutputString( $date_format, $this->plugin_admin->render_date_format_field() );
  }

  /**
   * @covers Book_Review_Admin::column_heading
   */
  public function testColumnHeading() {
    $output = $this->plugin_admin->column_heading( array() );

    $this->assertArrayHasKey( 'rating', $output, 'Rating Column' );
  }

  /**
   * @covers Book_Review_Admin::column_content
   */
  public function testColumnContent() {
    $meta_data = array( 'book_review_rating' => '3' );
    $content = '<img src="http://example.org/wp-content/plugins/vagrant/www/github/wordpress-book-review-plugin/includes/images/three-star.png" class="book_review_column_rating">';
    $post_id = $this->factory->post->create();

    foreach( $meta_data as $key => $value ) {
      update_post_meta( $post_id, $key, $value );
    }

    $this->expectOutputString( $content,  $this->plugin_admin->column_content( 'rating', $post_id ) );
  }

  /**
   * @covers Book_Review_Admin::column_content
   */
  public function testColumnContentNoRating() {
    $meta_data = array( 'book_review_rating' => '-1' );
    $post_id = $this->factory->post->create();

    foreach( $meta_data as $key => $value ) {
      update_post_meta( $post_id, $key, $value );
    }

    $this->expectOutputString( '',  $this->plugin_admin->column_content( 'rating', $post_id ) );
  }
}