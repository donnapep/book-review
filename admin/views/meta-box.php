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
<div style="<?php echo $show_isbn; ?>">
  <label for="book_review_isbn">
    <?php _e( 'ISBN', $this->plugin_slug ) ?>:
  </label>
  <input type="text" id="book_review_isbn" name="book_review_isbn"
    value="<?php echo $book_review_isbn; ?>" />
  <a id="get-book-info" href="#" class="button-primary">
    <?php _e( 'Get Book Info', $this->plugin_slug ) ?>
  </a>
  <span class="spinner"></span>
  <span id="ajax_isbn_nonce" class="hidden"><?php echo wp_create_nonce( 'ajax_isbn_nonce' ); ?></span>
  <br>
</div>

<!-- Title -->
<label for="book_review_title">
  <?php _e( 'Title', $this->plugin_slug ) ?>:
  <span class="required">*</span>
</label>
<input type="text" id="book_review_title" name="book_review_title"
  value="<?php echo $book_review_title; ?>" />
<br>

<!-- Series -->
<label for="book_review_series">
  <?php _e( 'Series', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_series" name="book_review_series"
  value="<?php echo $book_review_series; ?>" />
<br>

<!-- Author -->
<label for="book_review_author">
  <?php _e( 'Author', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_author" name="book_review_author"
  value="<?php echo $book_review_author; ?>" />
<br>

<!-- Genre -->
<label for="book_review_genre">
  <?php _e( 'Genre', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_genre" name="book_review_genre"
  value="<?php echo $book_review_genre; ?>" />
<br>

<!-- Publisher -->
<label for="book_review_publisher">
  <?php _e( 'Publisher', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_publisher" name="book_review_publisher"
  value="<?php echo $book_review_publisher; ?>" />
<br>

<!-- Release Date -->
<label for="book_review_release_date">
  <?php _e( 'Release Date', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_release_date" name="book_review_release_date"
  value="<?php echo $book_review_release_date; ?>" />
<br>

<!-- Format -->
<label for="book_review_format">
  <?php _e( 'Format', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_format" name="book_review_format"
  value="<?php echo $book_review_format; ?>" />
<br>

<!-- Pages -->
<label for="book_review_pages">
  <?php _e( 'Pages', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_pages" name="book_review_pages"
  value="<?php echo $book_review_pages; ?>" />
<br>

<!-- Source -->
<label for="book_review_source">
  <?php _e( 'Source', $this->plugin_slug ) ?>:
</label>
<input type="text" id="book_review_source" name="book_review_source"
  value="<?php echo $book_review_source; ?>" />
<br>

<!-- Links -->
<?php $this->render_links(); ?>

<!-- Cover URL -->
<label for="book_review_cover_url">
  <?php _e( 'Cover URL', $this->plugin_slug ) ?>:
</label>
<input id="book_review_cover_url" name="book_review_cover_url" type="text"
  value="<?php echo $book_review_cover_url; ?>" />
<a href="#" class="button-primary upload-image-button">
  <?php _e( 'Upload Cover', $this->plugin_slug ) ?>
</a>
<br>
<img id="book_review_cover_image" src="<?php echo $book_review_cover_url; ?>"
  style="<?php echo $show_cover; ?>" />
<br>

<!-- Synopsis -->
<label for="book_review_summary" class="summary">
  <?php _e( 'Synopsis', $this->plugin_slug ) ?>:
</label>
<?php wp_editor( $book_review_summary, 'book_review_summary', $args ); ?>
<br>

<!-- Rating -->
<label for="book_review_rating">
  <?php _e( 'Rating', $this->plugin_slug ) ?>:
</label>
<select id="book_review_rating" name="book_review_rating">
  <?php $this->render_rating($book_review_rating); ?>
</select>
<br>
<img id="book_review_rating_image" src="<?php echo $src; ?>"
  style="<?php echo $show_rating_image; ?>" />

<!-- Include post in archives -->
<label for="book_review_archive_post">
  <?php _e( 'Include post in archives', $this->plugin_slug ) ?>:
</label>
<input id="book_review_archive_post" type="checkbox"
  name="book_review_archive_post" value="1"
  <?php echo checked( '1', $book_review_archive_post, false ) ?> />
<br>