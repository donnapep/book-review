<?php
/**
 * Represents the view for the meta box on the posts page.
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <support@wpreviewplugins.com>
 * @license   GPL-2.0+
 * @link      http://wpreviewplugins.com/
 * @copyright 2014 Donna Peplinskie
 */
?>
<div class="book-review-meta">
  <div class="error-details"></div>

  <!-- ISBN -->
  <div class="<?php echo esc_attr( $this->get_isbn_class() ); ?>">
    <label for="book_review_isbn">
      <?php esc_html_e( 'ISBN', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_isbn" name="book_review_isbn" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_isbn( $post->ID ) ); ?>">
    <a id="get-book-info" class="button-primary" href="#">
      <?php esc_html_e( 'Get Book Info', $this->plugin_name ) ?>
    </a>
    <span class="spinner"></span>
    <span id="ajax_isbn_nonce" class="hidden"><?php echo esc_attr( wp_create_nonce( 'ajax_isbn_nonce' ) ); ?></span>
  </div>

  <!-- Title -->
  <div class="row">
    <label for="book_review_title">
      <?php esc_html_e( 'Title', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_title" name="book_review_title" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_title( $post->ID ) ); ?>">
  </div>

  <!-- Series -->
  <div class="row">
    <label for="book_review_series">
      <?php esc_html_e( 'Series', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_series" name="book_review_series" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_series( $post->ID ) ); ?>">
  </div>

  <!-- Author -->
  <div class="row">
    <label for="book_review_author">
      <?php esc_html_e( 'Author', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_author" name="book_review_author" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_author( $post->ID ) ); ?>">
  </div>

  <!-- Genre -->
  <div class="row">
    <label for="book_review_genre">
      <?php esc_html_e( 'Genre', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_genre" name="book_review_genre" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_genre( $post->ID ) ); ?>">
  </div>

  <!-- Publisher -->
    <div class="row">
    <label for="book_review_publisher">
      <?php esc_html_e( 'Publisher', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_publisher" name="book_review_publisher" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_publisher( $post->ID ) ); ?>">
    <br>
  </div>

  <!-- Release Date -->
  <div class="row">
    <label for="book_review_release_date">
      <?php esc_html_e( 'Release Date', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_release_date" name="book_review_release_date" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_release_date( $post->ID ) ); ?>">
  </div>

  <!-- Format -->
  <div class="row">
    <label for="book_review_format">
      <?php esc_html_e( 'Format', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_format" name="book_review_format" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_format( $post->ID ) ); ?>">
  </div>

  <!-- Pages -->
  <div class="row">
    <label for="book_review_pages">
      <?php esc_html_e( 'Pages', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_pages" name="book_review_pages" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_pages( $post->ID ) ); ?>">
  </div>

  <!-- Source -->
  <div class="row">
    <label for="book_review_source">
      <?php esc_html_e( 'Source', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_source" name="book_review_source" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_source( $post->ID ) ); ?>">
  </div>

  <!-- Custom Fields -->
  <?php
    $fields = $this->settings->get_book_review_fields_option();

    foreach ( $fields['fields'] as $field_id => $field_values ):
      if ( isset( $field_values['label'] ) ):
  ?>
  <div class="row">
    <label for="<?php echo esc_attr( $field_id ); ?>">
      <?php echo esc_html( $field_values['label'] ) ?>:
    </label>
    <input id="<?php echo esc_attr( $field_id ); ?>"
      name="<?php echo "book_review_fields[" . esc_attr( $field_id ) . "]"; ?>" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_field( $post->ID, $field_id ) ); ?>">
  </div>
  <?php
      endif;
    endforeach;
  ?>

  <!-- Site Links -->
  <?php
    $site_links = $this->settings->get_book_review_links_option();

    foreach ( $site_links['sites'] as $site_id => $site_values ):
      if ( ( isset( $site_values['active'] ) && ( $site_values['active'] === '1' ) ) ):
  ?>
  <div class="row">
    <label for="<?php echo esc_attr( $site_id ); ?>">
      <?php echo esc_html( $site_values['text'] ) ?>:
    </label>
    <input id="<?php echo esc_attr( $site_id ); ?>"
      name="<?php echo "book_review_sites[" . esc_attr( $site_id ) . "]"; ?>" type="text"
      value="<?php echo esc_attr( $this->book_info->get_book_review_site_link( $post->ID, $site_id ) ); ?>">
  </div>
  <?php
      endif;
    endforeach; ?>

  <!-- Links -->
  <?php
    $links = $this->book_info->get_book_review_links_meta( $post->ID );

    foreach ( $links as $link ):
  ?>
  <div class="row">
    <label for="<?php echo esc_attr( 'book_review_custom_link' . $link->custom_link_id ); ?>">
      <?php echo esc_html( $link->text ); ?>:
    </label>
    <input type="text" id="<?php echo esc_attr( 'book_review_custom_link' . $link->custom_link_id ); ?>"
      name="<?php echo esc_attr( 'book_review_custom_link' . $link->custom_link_id ); ?>"
      value="<?php echo esc_url( $link->url ); ?>">
  </div>
  <?php endforeach; ?>

  <!-- Cover URL -->
  <div class="row">
    <label for="book_review_cover_url">
      <?php esc_html_e( 'Cover URL', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_cover_url" name="book_review_cover_url" type="text"
      value="<?php echo esc_url( $this->book_info->get_book_review_cover_url( $post->ID ) ); ?>">
    <a class="button-primary upload-image-button" href="#">
      <?php esc_html_e( 'Upload Cover', $this->plugin_name ) ?>
    </a>
    <br>
    <img id="book_review_cover_image"
      class="<?php echo esc_attr( $this->get_cover_url_class( $post->ID ) ); ?>"
      src="<?php echo esc_url( $this->book_info->get_book_review_cover_url( $post->ID ) ); ?>">
  </div>

  <!-- Synopsis -->
  <div class="row">
    <label for="book_review_summary" class="summary">
      <?php esc_html_e( 'Synopsis', $this->plugin_name ) ?>:
    </label>
    <?php wp_editor( stripslashes( $this->book_info->get_book_review_summary( $post->ID ) ),
      'book_review_summary', array(
        'textarea_rows' => 15,
        'media_buttons' => false
      ) ); ?>
  </div>

  <!-- Rating -->
  <div class="row">
    <label for="book_review_rating">
      <?php esc_html_e( 'Rating', $this->plugin_name ) ?>:
    </label>
    <select id="book_review_rating" name="book_review_rating">
      <?php $this->display_rating( $post->ID ); ?>
    </select>
    <br>
    <img id="book_review_rating_image"
      class="<?php echo esc_attr( $this->get_rating_image_class( $post->ID ) ); ?>"
      src="<?php echo esc_url( $this->book_info->get_book_review_rating_image( $post->ID ) ); ?>">
  </div>

  <!-- Include post in archives -->
  <div class="row">
    <label for="book_review_archive_post">
      <?php esc_html_e( 'Include post in archives', $this->plugin_name ) ?>:
    </label>
    <input id="book_review_archive_post" name="book_review_archive_post" type="checkbox" value="1"
      <?php checked( '1', $this->book_info->get_book_review_archive_post( $post->ID ) ) ?>>
  </div>
</div>