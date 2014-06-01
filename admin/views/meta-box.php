<?php
/**
 * Represents the view for the meta box on the posts page.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>
<div class="error-details"></div>
<div style="<?php echo $show_isbn; ?>">
	<label for="book_review_isbn">ISBN:</label>
	<input type="text" id="book_review_isbn" name="book_review_isbn" value="<?php echo $isbn; ?>" />
	<a id="get-book-info" href="#" class="button-primary"><?php _e( 'Get Book Info', $this->plugin_slug ) ?></a>
	<span class="spinner"></span>
	<span id="ajax_isbn_nonce" class="hidden"><?php echo wp_create_nonce( 'ajax_isbn_nonce' ); ?></span>
	<br />
</div>
<label for="book_review_title"><?php _e( 'Title', $this->plugin_slug ) ?>:<span class="required">*</span></label>
<input type="text" id="book_review_title" name="book_review_title" value="<?php echo $title; ?>" />
<br />
<label for="book_review_series"><?php _e( 'Series', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_series" name="book_review_series" value="<?php echo $series; ?>" />
<br />
<label for="book_review_author"><?php _e( 'Author', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_author" name="book_review_author" value="<?php echo $author; ?>" />
<br />
<label for="book_review_genre"><?php _e( 'Genre', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_genre" name="book_review_genre" value="<?php echo $genre; ?>" />
<br />
<label for="book_review_publisher"><?php _e( 'Publisher', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_publisher" name="book_review_publisher" value="<?php echo $publisher; ?>" />
<br />
<label for="book_review_release_date"><?php _e( 'Release Date', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_release_date" name="book_review_release_date" value="<?php echo $release_date; ?>" />
<br />
<label for="book_review_format"><?php _e( 'Format', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_format" name="book_review_format" value="<?php echo $format; ?>" />
<br />
<label for="book_review_pages"><?php _e( 'Pages', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_pages" name="book_review_pages" value="<?php echo $pages; ?>" />
<br />
<label for="book_review_source"><?php _e( 'Source', $this->plugin_slug ) ?>:</label>
<input type="text" id="book_review_source" name="book_review_source" value="<?php echo $source; ?>" />
<br />
<?php $this->render_links(); ?>
<label for="book_review_cover_url"><?php _e( 'Cover URL', $this->plugin_slug ) ?>:</label>
<input id="book_review_cover_url" name="book_review_cover_url" type="text" value="<?php echo $cover_url; ?>" />
<a href="#" class="button-primary upload-image-button"><?php _e( 'Upload Cover', $this->plugin_slug ) ?></a>
<br />
<img id="book_review_cover_image" src="<?php echo $cover_url; ?>" style="<?php echo $show_cover; ?>" />
<br />
<label for="book_review_summary" class="summary"><?php _e( 'Synopsis', $this->plugin_slug ) ?>:</label>
<?php wp_editor( $summary, 'book_review_summary', $args ); ?>
<br />
<label for="book_review_rating"><?php _e( 'Rating', $this->plugin_slug ) ?>:</label>
<select id="book_review_rating" name="book_review_rating">
	<?php $this->render_rating($rating); ?>
</select>
<br />
<img id="book_review_rating_image" src="<?php echo $src; ?>" style="<?php echo $show_rating_image; ?>" />
<label for="book_review_archive_post"><?php _e( 'Include post in archives', $this->plugin_slug ) ?>:</label>
<input id="book_review_archive_post" type="checkbox" name="book_review_archive_post" value="1" <?php echo checked( 1, $archive_post, false ) ?> />
<br />