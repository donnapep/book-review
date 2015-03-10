<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <donnapep@gmail.com>
 * @license   GPL-2.0+
 * @link      http://donnapeplinskie.com
 * @copyright 2014 Donna Peplinskie
 */
?>

<div id="book-review" itemscope itemtype="http://schema.org/Review" <?php echo $review_box_style ?>>
  <!-- Meta for schema.org -->
  <meta itemprop="headline" content="<?php echo get_the_title(); ?>">
  <!-- author is mandatory! -->
  <meta itemprop="author" content="<?php echo get_the_author(); ?>">
  <meta itemprop="datePublished" content="<?php the_date( 'Y-m-d' ); ?>">
  <!-- Cover -->
  <?php if ( !empty( $book_review_cover_url ) ) { ?>
  <img itemprop="image" id="book_review_cover_image" class="cover"
    src="<?php echo $book_review_cover_url ?>"
    alt="<?php echo $book_review_title . ' ';
      _e( 'Book Cover', $this->plugin_name )?>" />
  <?php } ?>

  <!-- Title -->
  <?php if ( !empty( $book_review_title ) ) { ?>
  <label for="book_review_title">
    <?php _e( 'Title', $this->plugin_name ) ?>:
  </label>
  <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing" id="book_review_title">
    <span itemprop="name"><?php echo $book_review_title ?></span>
  </span>
  <br>
  <?php } ?>

  <!-- Series -->
  <?php if ( !empty( $book_review_series ) ) { ?>
  <label for="book_review_series">
    <?php _e( 'Series', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_series">
    <?php echo $book_review_series ?>
  </span>
  <br>
  <?php } ?>

  <!-- Author -->
  <?php if ( !empty( $book_review_author ) ) { ?>
  <label for="book_review_author">
    <?php _e( 'Author', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_author">
    <span>
      <?php echo $book_review_author ?>
    </span>
  </span>
  <br>
  <?php } ?>

  <!-- Genre -->
  <?php if ( !empty( $book_review_genre ) ) { ?>
  <label for="book_review_genre">
    <?php _e( 'Genre', $this->plugin_name ) ?>:
  </label>
  <span itemprop="genre" id="book_review_genre">
    <?php echo $book_review_genre ?>
  </span>
  <br>
  <?php } ?>

  <!-- Publisher -->
  <?php if ( !empty( $book_review_publisher ) ) { ?>
  <label for="book_review_publisher">
    <?php _e( 'Publisher', $this->plugin_name ) ?>:
  </label>
  <span itemprop="publisher" id="book_review_publisher">
    <?php echo $book_review_publisher ?>
  </span>
  <br>
  <?php } ?>

  <!-- Release Date -->
  <?php if ( !empty( $book_review_release_date ) ) { ?>
  <label for="book_review_release_date">
    <?php _e( 'Release Date', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_release_date">
    <?php echo $book_review_release_date ?>
  </span>
  <br>
  <?php } ?>

  <!-- Format -->
  <?php if ( !empty( $book_review_format ) ) { ?>
  <label for="book_review_format">
    <?php _e( 'Format', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_format">
    <?php echo $book_review_format ?>
  </span>
  <br>
  <?php } ?>

  <!-- Pages -->
  <?php if ( !empty( $book_review_pages ) ) { ?>
  <label for="book_review_pages">
    <?php _e( 'Pages', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_pages">
    <?php echo $book_review_pages ?>
  </span>
  <br>
  <?php } ?>

  <!-- Source -->
  <?php if ( !empty( $book_review_source ) ) { ?>
  <label for="book_review_source">
    <?php _e( 'Source', $this->plugin_name ) ?>:
  </label>
  <span id="book_review_source">
    <?php echo $book_review_source ?>
  </span>
  <br>
  <?php } ?>

  <!-- Rating -->
  <?php if ( !empty ( $book_review_rating_url ) ) { ?>
  <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
    <meta itemprop="ratingValue" content="<?php echo $book_review_rating ?>">
    <img id="book_review_rating_image" class="rating"
      src="<?php echo $book_review_rating_url ?>" />
    <br>
  </div>
  <?php } ?>
  <!-- Summary / Synopsis -->
  <?php if ( !empty( $book_review_summary ) ) { ?>
  <div id="book_review_summary">
    <?php echo wpautop( $book_review_summary, true ) ?>
  </div>
  <?php } ?>

  <!-- Links -->
  <ul id="book-review-links" class="links">
    <?php
      do_action( 'book_review_before_render_links', $links_option );

      foreach( $results as $result ) { ?>
    <li>
        <?php
          if ( !empty( $result->image_url ) ) {
            echo '<a class="custom-link" href="' . $result->url . '" ' . $target . '>' .
              '<img src="' . $result->image_url . '" alt="' . $result->text . '" />' .
              '</a>';
          }
          else {
            echo '<a class="custom-link" href="' . $result->url . '" ' . $target . '>' . $result->text . '</a>';
          }
        ?>
    </li>
    <?php
      }

      do_action( 'book_review_after_render_links', $links_option );
    ?>
  </ul>
</div>