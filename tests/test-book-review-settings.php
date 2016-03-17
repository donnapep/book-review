<?php

class Book_Review_Settings_Tests extends WP_UnitTestCase {
  protected $settings;

  public function setup() {
    global $wpdb;

    parent::setUp();

    // Initialize necessary classes.
    run_book_review();
    $this->settings = new Book_Review_Settings();

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
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testDefaultPosition() {
    $this->assertSame( 'top', $this->settings->get_book_review_general_option()['book_review_box_position'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testNoDefaultPosition() {
    $general_option = array(
      'book_review_box_position' => 'bottom',
    );

    update_option( 'book_review_general', $general_option );

    $this->assertSame( 'bottom', $this->settings->get_book_review_general_option()['book_review_box_position'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testDefaultBackgroundColor() {
    $this->assertSame( '', $this->settings->get_book_review_general_option()['book_review_bg_color'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testNoDefaultBackgroundColor() {
    $general_option = array(
      'book_review_bg_color' => '#fff',
    );

    update_option( 'book_review_general', $general_option );

    $this->assertSame( '#fff', $this->settings->get_book_review_general_option()['book_review_bg_color'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testDefaultBorderColor() {
    $this->assertSame( '', $this->settings->get_book_review_general_option()['book_review_border_color'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testNoDefaultBorderColor() {
    $general_option = array(
      'book_review_border_color' => '#000',
    );

    update_option( 'book_review_general', $general_option );

    $this->assertSame( '#000', $this->settings->get_book_review_general_option()['book_review_border_color'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testDefaultBorderWidth() {
    $this->assertSame( 1, $this->settings->get_book_review_general_option()['book_review_border_width'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testNoDefaultBorderWidth() {
    $general_option = array(
      'book_review_border_width' => 5,
    );

    update_option( 'book_review_general', $general_option );

    $this->assertSame( 5, $this->settings->get_book_review_general_option()['book_review_border_width'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testDefaultPostType() {
    $output = $this->settings->get_book_review_general_option()['book_review_post_types'];

    $this->assertArrayHasKey( 'post', $output, 'key' );
    $this->assertSame( '1', $output['post'], 'value' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testNoDefaultPostType() {
    $general_option = array(
      'book_review_post_types' => array(
        'post' => '0'
      )
    );

    update_option( 'book_review_general', $general_option );

    $output = $this->settings->get_book_review_general_option()['book_review_post_types'];

    $this->assertArrayHasKey( 'post', $output, 'key' );
    $this->assertSame( '0', $output['post'], 'value' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testDefaultCustomPostType() {
    register_post_type( 'documentation', array( 'public' => true ) );

    $output = $this->settings->get_book_review_general_option()['book_review_post_types'];

    $this->assertArrayHasKey( 'documentation', $output, 'key' );
    $this->assertSame( '1', $output['post'], 'value' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testNoDefaultCustomPostType() {
    register_post_type( 'documentation', array( 'public' => false ) );

    $output = $this->settings->get_book_review_general_option()['book_review_post_types'];

    $this->assertArrayNotHasKey( 'documentation', $output );
  }

  public function testGeneralOptionNoDefaults() {
    $general_option = array(
      'book_review_box_position' => 'bottom',
      'book_review_bg_color' => '#fff',
      'book_review_border_color' => '#000',
      'book_review_border_width' => 5,
      'book_review_post_types' => array(
        'post' => '0'
      ),
    );

    update_option( 'book_review_general', $general_option );

    $output = $this->settings->get_book_review_general_option( false );

    $this->assertSame( 'bottom', $output['book_review_box_position'], 'Position' );
    $this->assertSame( '#fff', $output['book_review_bg_color'], 'Background color' );
    $this->assertSame( '#000', $output['book_review_border_color'], 'Border color' );
    $this->assertSame( 5, $output['book_review_border_width'], 'Border width' );
    $this->assertArrayHasKey( 'post', $output['book_review_post_types'], 'Post type key' );
    $this->assertSame( '0', $output['book_review_post_types']['post'], 'Post type value' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_general_option
   */
  public function testGeneralOptionNoDefaultsNoOptions() {
    $output = $this->settings->get_book_review_general_option( false );

    $this->assertFalse( $output );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultExcerpts() {
    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultExcerpts() {
    $ratings_option = array(
      'book_review_rating_home' => '1',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( '1', $this->settings->get_book_review_ratings_option()['book_review_rating_home'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultRatingImages() {
    $this->assertSame( '1', $this->settings->get_book_review_ratings_option()['book_review_rating_default'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultRatingImages() {
    $ratings_option = array(
      'book_review_rating_default' => '',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_default'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultRatingImage1() {
    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_image1'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultRatingImage1() {
    $ratings_option = array(
      'book_review_rating_image1' => 'http://url.to.image1.png',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( 'http://url.to.image1.png',
      $this->settings->get_book_review_ratings_option()['book_review_rating_image1'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultRatingImage2() {
    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_image2'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultRatingImage2() {
    $ratings_option = array(
      'book_review_rating_image2' => 'http://url.to.image2.png',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( 'http://url.to.image2.png',
      $this->settings->get_book_review_ratings_option()['book_review_rating_image2'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultRatingImage3() {
    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_image3'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultRatingImage3() {
    $ratings_option = array(
      'book_review_rating_image3' => 'http://url.to.image3.png',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( 'http://url.to.image3.png',
      $this->settings->get_book_review_ratings_option()['book_review_rating_image3'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultRatingImage4() {
    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_image4'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultRatingImage4() {
    $ratings_option = array(
      'book_review_rating_image4' => 'http://url.to.image4.png',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( 'http://url.to.image4.png',
      $this->settings->get_book_review_ratings_option()['book_review_rating_image4'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testDefaultRatingImage5() {
    $this->assertSame( '', $this->settings->get_book_review_ratings_option()['book_review_rating_image5'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testNoDefaultRatingImage5() {
    $ratings_option = array(
      'book_review_rating_image5' => 'http://url.to.image5.png',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $this->assertSame( 'http://url.to.image5.png',
      $this->settings->get_book_review_ratings_option()['book_review_rating_image5'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testRatingOptionNoDefaults() {
    $ratings_option = array(
      'book_review_rating_home' => '1',
      'book_review_rating_default' => '',
      'book_review_rating_image1' => 'http://url.to.image1.png',
      'book_review_rating_image2' => 'http://url.to.image2.png',
      'book_review_rating_image3' => 'http://url.to.image3.png',
      'book_review_rating_image4' => 'http://url.to.image4.png',
      'book_review_rating_image5' => 'http://url.to.image5.png',
    );

    update_option( 'book_review_ratings', $ratings_option );

    $output = $this->settings->get_book_review_ratings_option( false );

    $this->assertSame( '1', $output['book_review_rating_home'], 'Excerpts' );
    $this->assertSame( '', $output['book_review_rating_default'], 'Default Rating Images' );
    $this->assertSame( 'http://url.to.image1.png', $output['book_review_rating_image1'], 'Rating image 1' );
    $this->assertSame( 'http://url.to.image2.png', $output['book_review_rating_image2'], 'Rating image 2' );
    $this->assertSame( 'http://url.to.image3.png', $output['book_review_rating_image3'], 'Rating image 3' );
    $this->assertSame( 'http://url.to.image4.png', $output['book_review_rating_image4'], 'Rating image 4' );
    $this->assertSame( 'http://url.to.image5.png', $output['book_review_rating_image5'], 'Rating image 5' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_ratings_option
   */
  public function testRatingOptionNoDefaultsNoOptions() {
    $output = $this->settings->get_book_review_ratings_option( false );

    $this->assertFalse( $output );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultTarget() {
    $this->assertSame( '', $this->settings->get_book_review_links_option()['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testNoDefaultTarget() {
    $links_option = array(
      'book_review_target' => '1',
    );

    update_option( 'book_review_links', $links_option );

    $this->assertSame( '1', $this->settings->get_book_review_links_option()['book_review_target'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultSiteLinks() {
    $this->assertCount( 2, $this->settings->get_book_review_links_option()['sites'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultGoodreadsSiteLink() {
    $this->assertCount( 4, $this->settings->get_book_review_links_option()['sites']['book_review_goodreads'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultGoodreadsSiteLinkType() {
    $this->assertSame( 'button', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['type'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultGoodreadsSiteLinkText() {
    $this->assertSame( 'Goodreads', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['text'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultGoodreadsSiteLinkUrl() {
    $this->assertSame( '', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['url'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultGoodreadsSiteLinkActive() {
    $this->assertSame( '0', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['active'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultBarnesNobleSiteLink() {
    $this->assertCount( 4, $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultBarnesNobleSiteLinkType() {
    $this->assertSame( 'button', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['type'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultBarnesNobleSiteLinkText() {
    $this->assertSame( 'Barnes & Noble', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['text'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultBarnesNobleSiteLinkUrl() {
    $this->assertSame( '', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['url'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testDefaultvSiteLinkActive() {
    $this->assertSame( '0', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['active'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testNoDefaultGoodreadsSiteLink() {
    $links_option = array(
      'sites' => array(
        'book_review_goodreads' => array(
          'type' => 'text',
          'text' => 'GR',
          'url' => 'http://fakeurl.com/goodreads.png',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );

    $this->assertSame( 'text', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['type'], 'Type' );
    $this->assertSame( 'GR', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['text'], 'Text' );
    $this->assertSame( 'http://fakeurl.com/goodreads.png', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['url'], 'URL' );
    $this->assertSame( '1', $this->settings->get_book_review_links_option()['sites']['book_review_goodreads']['active'], 'Active' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_links_option
   */
  public function testNoDefaultBarnesNobleSiteLink() {
    $links_option = array(
      'sites' => array(
        'book_review_barnes_noble' => array(
          'type' => 'text',
          'text' => 'B&N',
          'url' => 'http://fakeurl.com/barnes-noble.png',
          'active' => '1'
        )
      )
    );

    update_option( 'book_review_links', $links_option );

    $this->assertSame( 'text', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['type'], 'Type' );
    $this->assertSame( 'B&N', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['text'], 'Text' );
    $this->assertSame( 'http://fakeurl.com/barnes-noble.png', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['url'], 'URL' );
    $this->assertSame( '1', $this->settings->get_book_review_links_option()['sites']['book_review_barnes_noble']['active'], 'Active' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_fields_option
   */
  public function testDefaultField() {
    $this->assertSame( array(), $this->settings->get_book_review_fields_option()['fields'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_fields_option
   */
  public function testNoDefaultField() {
    $fields_option = array(
      'fields' => array(
        'book_review_565adc1c2d403' => array(
          'label' => 'Illustrator'
        )
      )
    );

    update_option( 'book_review_fields', $fields_option );

    $output = $this->settings->get_book_review_fields_option()['fields'];

    $this->assertArrayHasKey( 'book_review_565adc1c2d403', $output, 'field key' );
    $this->assertArrayHasKey( 'label', $output['book_review_565adc1c2d403'], 'label key' );
    $this->assertSame( 'Illustrator', $output['book_review_565adc1c2d403']['label'], 'label' );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_advanced_option
   */
  public function testDefaultAPIKey() {
    $this->assertSame( '', $this->settings->get_book_review_advanced_option()['book_review_api_key'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_advanced_option
   */
  public function testNoDefaultAPIKey() {
    $advanced_option = array(
      'book_review_api_key' => 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0',
    );

    update_option( 'book_review_advanced', $advanced_option );

    $this->assertSame( 'AIzaSyBlL-VCuJ3yoCPHOMrGjO48Gjj3c216Md0',
      $this->settings->get_book_review_advanced_option()['book_review_api_key'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_advanced_option
   */
  public function testDefaultCountry() {
    $this->assertSame( '', $this->settings->get_book_review_advanced_option()['book_review_country'] );
  }

  /**
   * @covers Book_Review_Admin::get_book_review_advanced_option
   */
  public function testNoDefaultCountry() {
    $advanced_option = array(
      'book_review_country' => 'SO',
    );

    update_option( 'book_review_advanced', $advanced_option );

    $this->assertSame( 'SO', $this->settings->get_book_review_advanced_option()['book_review_country'] );
  }
}