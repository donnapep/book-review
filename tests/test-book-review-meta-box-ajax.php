<?php

class Book_Review_Ajax extends WP_Ajax_UnitTestCase {
  public function setup() {
    parent::setUp();

    $_POST['nonce'] = wp_create_nonce( 'ajax_isbn_nonce' );

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'AIzaSyCBbpjgTzdGQ0wZtCzfZ7xKRZI5cmiBHjQ'
    );

    add_option( 'book_review_advanced', $advanced );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testGetBookInfoSuccess() {
    $_POST['isbn'] = '0439023483';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertInternalType( 'object', $response, 'response is an object' );
    $this->assertObjectHasAttribute( 'data', $response, 'response has a data property' );
    $this->assertEquals( 'success', $response->status, 'response status is "success"' );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testEmptyISBN() {
    $_POST['isbn'] = '';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    // Empty ISBN returns multiple results.
    $this->assertEquals( 'error', $response->status );
    $this->assertEquals( 'No API key or empty ISBN', $response->data );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testInvalidISBN() {
    $_POST['isbn'] = 'invalid';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    // Invalid ISBN returns a success response but no results.
    $this->assertEquals( 'success', $response->status );
    $this->assertObjectHasAttribute( 'data', $response );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testEmptyAPIKey() {
    $_POST['isbn'] = '0525478817';

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => ''
    );

    update_option( 'book_review_advanced', $advanced );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( 'error', $response->status );
    $this->assertEquals( 'No API key or empty ISBN', $response->data );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testInvalidAPIKey() {
    $_POST['isbn'] = '0525478817';

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'invalid'
    );

    update_option( 'book_review_advanced', $advanced );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( 'error', $response->status );
    $this->assertEquals( '400 Bad Request to https://www.googleapis.com/books/v1/volumes?q=isbn:0525478817&#038;key=invalid', $response->data );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testInvalidNonce() {
    $_POST['isbn'] = '0525478817';
    $_POST['nonce'] = 'invalid';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( 'error', $response->status );
    $this->assertEquals( 'Invalid nonce', $response->data );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testReleaseDateDefaultFormat() {
    $_POST['isbn'] = '0439023513';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( 'August 24, 2010', $response->releaseDate );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testReleaseDateYmdFormat() {
    $_POST['isbn'] = '0439023513';

    update_option( 'date_format', 'Y-m-d' );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( '2010-08-24', $response->releaseDate );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testReleaseDatemdYFormat() {
    $_POST['isbn'] = '0439023513';

    update_option( 'date_format', 'm/d/Y' );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( '08/24/2010', $response->releaseDate );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testReleaseDatedmYFormat() {
    $_POST['isbn'] = '0439023513';

    update_option( 'date_format', 'd/m/Y' );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( '24/08/2010', $response->releaseDate );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testEmptyReleaseDate() {
    $_POST['isbn'] = '978-1595266545';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertObjectNotHasAttribute( 'releaseDate', $response );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testInvalidDateFormat() {
    $_POST['isbn'] = '0439023483';

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( '2008', $response->releaseDate );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testGetBookInfoWithCountry() {
    $_POST['isbn'] = '0525478817';

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'AIzaSyCBbpjgTzdGQ0wZtCzfZ7xKRZI5cmiBHjQ',
      'book_review_country' => 'CA'
    );

    update_option( 'book_review_advanced', $advanced );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertInternalType( 'object', $response );
    $this->assertEquals( 'success', $response->status );
    $this->assertObjectHasAttribute( 'data', $response );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testFailure() {
    // TODO: Not sure how to test the case where an exception is caught in get_book_info.
  }
}
?>