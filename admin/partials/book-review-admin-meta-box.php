<?php
/**
 * Represents the view for the meta box on the posts page.
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <donnapep@gmail.com>
 * @license   GPL-2.0+
 * @link      http://donnapeplinskie.com
 * @copyright 2014 Donna Peplinskie
 */
?>
<div class="error-details"></div>

<!-- ISBN -->
<div class="row" style="<?php echo esc_attr( $show_isbn ); ?>">
  <label for="book_review_isbn">
    <?php _e( 'ISBN', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_isbn" name="book_review_isbn"
    value="<?php echo esc_attr( $book_review_isbn ); ?>" />
  <a id="get-book-info" href="#" class="button-primary">
    <?php _e( 'Get Book Info', $this->plugin_name ) ?>
  </a>
  <span class="spinner"></span>
  <span id="ajax_isbn_nonce" class="hidden"><?php echo wp_create_nonce( 'ajax_isbn_nonce' ); ?></span>
</div>

<!-- Title -->
<div class="row">
  <label for="book_review_title">
    <?php _e( 'Title', $this->plugin_name ) ?>:
    <span class="required">*</span>
  </label>
  <input type="text" id="book_review_title" name="book_review_title"
    value="<?php echo esc_attr( $book_review_title ); ?>" />
</div>

<!-- Series -->
<div class="row">
  <label for="book_review_series">
    <?php _e( 'Series', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_series" name="book_review_series"
    value="<?php echo esc_attr( $book_review_series ); ?>" />
</div>

<!-- Author -->
<div class="row">
  <label for="book_review_author">
    <?php _e( 'Author', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_author" name="book_review_author"
    value="<?php echo esc_attr( $book_review_author ); ?>" />
</div>

<!-- Genre -->
<div class="row">
  <label for="book_review_genre">
    <?php _e( 'Genre', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_genre" name="book_review_genre"
    value="<?php echo esc_attr( $book_review_genre ); ?>" />
</div>

<!-- Publisher -->
  <div class="row">
  <label for="book_review_publisher">
    <?php _e( 'Publisher', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_publisher" name="book_review_publisher"
    value="<?php echo esc_attr( $book_review_publisher ); ?>" />
  <br>
</div>

<!-- Release Date -->
<div class="row">
  <label for="book_review_release_date">
    <?php _e( 'Release Date', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_release_date" name="book_review_release_date"
    value="<?php echo esc_attr( $book_review_release_date ); ?>" />
</div>

<!-- Format -->
<div class="row">
  <label for="book_review_format">
    <?php _e( 'Format', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_format" name="book_review_format"
    value="<?php echo esc_attr( $book_review_format ); ?>" />
</div>

<!-- Pages -->
<div class="row">
  <label for="book_review_pages">
    <?php _e( 'Pages', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_pages" name="book_review_pages"
    value="<?php echo esc_attr( $book_review_pages ); ?>" />
</div>

<!-- Source -->
<div class="row">
  <label for="book_review_source">
    <?php _e( 'Source', $this->plugin_name ) ?>:
  </label>
  <input type="text" id="book_review_source" name="book_review_source"
    value="<?php echo esc_attr( $book_review_source ); ?>" />
</div>

<!-- Links -->
<?php $this->render_links( $post ); ?>

<!-- Cover URL -->
<div class="row">
  <label for="book_review_cover_url">
    <?php _e( 'Cover URL', $this->plugin_name ) ?>:
  </label>
  <input id="book_review_cover_url" name="book_review_cover_url" type="text"
    value="<?php echo esc_url( $book_review_cover_url ); ?>" />
  <a href="#" class="button-primary upload-image-button">
    <?php _e( 'Upload Cover', $this->plugin_name ) ?>
  </a>
  <br>
  <img id="book_review_cover_image" class="cover-image" src="<?php echo esc_url( $book_review_cover_url ); ?>"
    style="<?php echo esc_attr( $show_cover ); ?>" />
</div>

<!-- Synopsis -->
<div class="row">
  <label for="book_review_summary" class="summary">
    <?php _e( 'Synopsis', $this->plugin_name ) ?>:
  </label>
  <?php wp_editor( $book_review_summary, 'book_review_summary', $args ); ?>
</div>

<!-- Rating -->
<div class="row">
  <label for="book_review_rating">
    <?php _e( 'Rating', $this->plugin_name ) ?>:
  </label>
  <select id="book_review_rating" name="book_review_rating">
    <?php $this->render_rating($book_review_rating); ?>
  </select>
  <br>
  <img id="book_review_rating_image" class="rating-image" src="<?php echo esc_url( $src ); ?>"
    style="<?php echo esc_attr( $show_rating_image ); ?>" />
</div>

<!-- Include post in archives -->
<div class="row">
  <label for="book_review_archive_post">
    <?php _e( 'Include post in archives', $this->plugin_name ) ?>:
  </label>
  <input id="book_review_archive_post" type="checkbox"
    name="book_review_archive_post" value="1"
    <?php echo checked( '1', $book_review_archive_post, false ) ?> />
</div>