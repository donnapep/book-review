<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <support@wpreviewplugins.com>
 * @license   GPL-2.0+
 * @link      http://wpreviewplugins.com/
 * @copyright 2014 Donna Peplinskie
 */
?>

<div id="book-review" itemscope itemtype="http://schema.org/Review"
  <?php echo $this->get_review_box_style(); ?>>

  <!-- Meta for schema.org -->
  <meta itemprop="headline" content="<?php echo esc_attr( get_the_title() ); ?>">
  <!-- author is mandatory! -->
  <meta itemprop="author" content="<?php echo esc_attr( get_the_author() ); ?>">
  <meta itemprop="datePublished" content="<?php esc_attr( the_date( 'Y-m-d' ) ); ?>">

  <!-- Cover -->
  <?php
    $cover_url = $this->book_info->get_book_review_cover_url( $post_id );

    if ( !empty( $cover_url ) ): ?>
  <img itemprop="image" id="book_review_cover_image" class="cover"
    src="<?php echo esc_url( $cover_url ); ?>"
    alt="<?php echo esc_attr( $this->book_info->get_book_review_title( $post_id ) . ' ' .
      __( 'Book Cover', $this->plugin_name ) ); ?>">
  <?php endif; ?>

  <!-- Title -->
  <?php
    $title = $this->book_info->get_book_review_title( $post_id );

    if ( !empty( $title ) ): ?>
  <label for="book_review_title">
    <?php esc_html_e( 'Title', $this->plugin_name ); ?>:
  </label>
  <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing" id="book_review_title">
    <span itemprop="name"><?php echo esc_html( $title ); ?></span>
  </span>
  <br>
  <?php endif; ?>

  <!-- Series -->
  <?php
    $series = $this->book_info->get_book_review_series( $post_id );

    if ( !empty( $series ) ): ?>
  <label for="book_review_series">
    <?php esc_html_e( 'Series', $this->plugin_name ); ?>:
  </label>
  <span id="book_review_series">
    <?php echo esc_html( $series ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Author -->
  <?php
    $author = $this->book_info->get_book_review_author( $post_id );

    if ( !empty( $author ) ): ?>
  <label for="book_review_author">
    <?php esc_html_e( 'Author', $this->plugin_name ); ?>:
  </label>
  <span id="book_review_author">
    <span>
      <?php echo esc_html( $author ); ?>
    </span>
  </span>
  <br>
  <?php endif; ?>

  <!-- Genre -->
  <?php
    $genre = $this->book_info->get_book_review_genre( $post_id );

    if ( !empty( $genre ) ): ?>
  <label for="book_review_genre">
    <?php esc_html_e( 'Genre', $this->plugin_name ); ?>:
  </label>
  <span itemprop="genre" id="book_review_genre">
    <?php echo esc_html( $genre ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Publisher -->
  <?php
    $publisher = $this->book_info->get_book_review_publisher( $post_id );

    if ( !empty( $publisher ) ): ?>
  <label for="book_review_publisher">
    <?php esc_html_e( 'Publisher', $this->plugin_name ); ?>:
  </label>
  <span itemprop="publisher" id="book_review_publisher">
    <?php echo esc_html( $publisher ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Release Date -->
  <?php
    $release_date = $this->book_info->get_book_review_release_date( $post_id );

    if ( !empty( $release_date ) ): ?>
  <label for="book_review_release_date">
    <?php esc_html_e( 'Release Date', $this->plugin_name ); ?>:
  </label>
  <span id="book_review_release_date">
    <?php echo esc_html( $release_date ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Format -->
  <?php
    $format = $this->book_info->get_book_review_format( $post_id );

    if ( !empty( $format ) ): ?>
  <label for="book_review_format">
    <?php esc_html_e( 'Format', $this->plugin_name ); ?>:
  </label>
  <span id="book_review_format">
    <?php echo esc_html( $format ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Pages -->
  <?php
    $pages = $this->book_info->get_book_review_pages( $post_id );

    if ( !empty( $pages ) ): ?>
  <label for="book_review_pages">
    <?php esc_html_e( 'Pages', $this->plugin_name ); ?>:
  </label>
  <span id="book_review_pages">
    <?php echo esc_html( $pages ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Source -->
  <?php
    $source = $this->book_info->get_book_review_source( $post_id );

    if ( !empty( $source ) ): ?>
  <label for="book_review_source">
    <?php esc_html_e( 'Source', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_source">
    <?php echo esc_html( $source ); ?>
  </span>
  <br>
  <?php endif; ?>

  <!-- Custom Fields -->
  <?php
    $fields = $this->settings->get_book_review_fields_option();

    foreach ( $fields['fields'] as $field_id => $field_values ):
      if ( isset( $field_values['label'] ) ):
        $value = $this->book_info->get_book_review_field( $post_id, $field_id );

        if ( !empty( $value ) ) :
  ?>
  <label for="<?php echo esc_attr( $field_id ); ?>">
    <?php echo esc_html( $field_values['label'] ) ?>:
  </label>
  <span id="<?php echo esc_attr( $field_id ); ?>">
    <?php echo esc_html( $value ); ?>
  </span>
  <br>
  <?php
        endif;
      endif;
    endforeach;
  ?>

  <!-- Rating -->
  <?php
    $rating_image = $this->book_info->get_book_review_rating_image( $post_id );

    if ( !empty( $rating_image ) ): ?>
  <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
    <meta itemprop="ratingValue" content="<?php echo esc_attr( $this->book_info->get_book_review_rating( $post_id ) ); ?>">
    <img id="book_review_rating_image" class="rating"
      src="<?php echo esc_url( $rating_image ); ?>">
    <br>
  </div>
  <?php endif; ?>

  <!-- Summary / Synopsis -->
  <?php
    $summary = $this->book_info->get_book_review_summary( $post_id );

    if ( !empty( $summary ) ): ?>
  <div id="book_review_summary">
    <?php echo wpautop( $summary ); ?>
  </div>
  <?php endif; ?>

  <ul id="book-review-links" class="links">
    <!-- Site Links -->
    <?php
      $site_links = $this->book_info->get_book_review_site_link_html( $post_id );

      foreach ( $site_links as $site_link ): ?>
    <li>
    <?php
        echo $site_link;
      endforeach;
    ?>
    </li>

    <!-- Custom Links -->
    <?php
      $links = $this->book_info->get_book_review_links_html( $post_id );

      foreach ( $links as $link ) {
        echo $link;
      }
    ?>
  </ul>
</div>