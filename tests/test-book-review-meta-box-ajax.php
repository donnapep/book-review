<?php

class Book_Review_Ajax extends WP_Ajax_UnitTestCase {
  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testGetBookInfo() {
    $_POST['isbn'] = '0525478817';
    $_POST['nonce'] = wp_create_nonce( 'ajax_isbn_nonce' );

    // Add Release Date Format.
    $general = array(
      'book_review_date_format' => 'medium'
    );
    add_option( 'book_review_general', $general );

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'AIzaSyCBbpjgTzdGQ0wZtCzfZ7xKRZI5cmiBHjQ'
    );
    add_option( 'book_review_advanced', $advanced );

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
    $this->assertEquals( 'medium', $response->format );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testEmptyISBN() {
    $_POST['isbn'] = '';
    $_POST['nonce'] = wp_create_nonce( 'ajax_isbn_nonce' );

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'AIzaSyCBbpjgTzdGQ0wZtCzfZ7xKRZI5cmiBHjQ'
    );
    add_option( 'book_review_advanced', $advanced );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    // Empty ISBN returns multiple results.
    $this->assertEquals( 'success', $response->status );
    $this->assertObjectHasAttribute( 'data', $response );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testInvalidISBN() {
    $_POST['isbn'] = 'invalid';
    $_POST['nonce'] = wp_create_nonce( 'ajax_isbn_nonce' );

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'AIzaSyCBbpjgTzdGQ0wZtCzfZ7xKRZI5cmiBHjQ'
    );
    add_option( 'book_review_advanced', $advanced );

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
    $_POST['nonce'] = wp_create_nonce( 'ajax_isbn_nonce' );

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => ''
    );
    add_option( 'book_review_advanced', $advanced );

    try {
      $this->_handleAjax( 'get_book_info' );
    }
    catch ( WPAjaxDieContinueException $e ) {
      // We expected this; do nothing.
    }

    $response = json_decode( $this->_last_response );

    $this->assertEquals( 'error', $response->status );
    $this->assertEquals( 'No API key', $response->data );
  }

  /**
   * @covers Book_Review_Meta_Box::get_book_info
   */
  public function testInvalidAPIKey() {
    $_POST['isbn'] = '0525478817';
    $_POST['nonce'] = wp_create_nonce( 'ajax_isbn_nonce' );

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'invalid'
    );
    add_option( 'book_review_advanced', $advanced );

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

    // Add Google API Key.
    $advanced = array(
      'book_review_api_key' => 'AIzaSyCBbpjgTzdGQ0wZtCzfZ7xKRZI5cmiBHjQ'
    );
    add_option( 'book_review_advanced', $advanced );

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
  public function testFailure() {
    // TODO: Not sure how to test the case where an exception is caught in get_book_info.
  }
}
?>