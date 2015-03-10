<p>
  <?php _e( 'Configure the images to use for displaying ratings.', $this->plugin_name ); ?>
</p>

<form action="options.php" method="post">
  <?php
    @settings_fields( 'ratings_options' );
    @do_settings_fields( 'ratings_options' );
  ?>

  <table class="form-table">
    <tbody>
      <!-- Show rating in excerpts -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_home">
            <?php _e( 'Excerpts', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_home" type="checkbox"
            name="book_review_ratings[book_review_rating_home]" value="1"
            <?php echo checked( '1', $ratings['book_review_rating_home'], false ); ?>>
          <?php _e( 'Show the rating in excerpts',
            $this->plugin_name ); ?>
        </td>
      </tr>

      <!-- Use default rating images -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_default">
            <?php _e( 'Default Rating Images', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_default" type="checkbox"
            name="book_review_ratings[book_review_rating_default]" value="1"
            <?php echo checked( '1', $ratings['book_review_rating_default'], false ); ?>
            onchange="showRatingImages();">
          <?php _e( 'Use the default rating images', $this->plugin_name ); ?>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Rating Image URLs -->
  <div class="rating">
    <h3><?php _e( 'Rating Image URLs', $this->plugin_name ); ?></h3>
    <p>
      <?php _e( 'To use your own rating images, enter the URL of an image for each rating below (1-5).',
        $this->plugin_name ); ?>
    </p>
  </div>

  <table class="form-table rating">
    <tbody>
      <!-- 1-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image1">
            <?php _e( 'One-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image1" class="text-input"
            type="text" name="book_review_ratings[book_review_rating_image1]"
            value="<?php echo isset( $ratings['book_review_rating_image1'] ) ?
              esc_url( $ratings['book_review_rating_image1'] ) : ''; ?>" />
        </td>
      </tr>

      <!-- 2-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image2">
            <?php _e( 'Two-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image2" class="text-input"
            type="text" name="book_review_ratings[book_review_rating_image2]"
            value="<?php echo isset( $ratings['book_review_rating_image2'] ) ?
              esc_url( $ratings['book_review_rating_image2'] ) : ''; ?>" />
        </td>
      </tr>

      <!-- 3-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image3">
            <?php _e( 'Three-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image3" class="text-input"
            type="text" name="book_review_ratings[book_review_rating_image3]"
            value="<?php echo isset( $ratings['book_review_rating_image3'] ) ?
              esc_url( $ratings['book_review_rating_image3'] ) : ''; ?>" />
        </td>
      </tr>

      <!-- 4-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image4">
            <?php _e( 'Four-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image4" class="text-input"
            type="text" name="book_review_ratings[book_review_rating_image4]"
            value="<?php echo isset( $ratings['book_review_rating_image4'] ) ?
              esc_url( $ratings['book_review_rating_image4'] ) : ''; ?>" />
        </td>
      </tr>

      <!-- 5-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image5">
            <?php _e( 'Five-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image5" class="text-input"
            type="text" name="book_review_ratings[book_review_rating_image5]"
            value="<?php echo isset( $ratings['book_review_rating_image5'] ) ?
              esc_url( $ratings['book_review_rating_image5'] ) : ''; ?>" />
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>

<script type="text/javascript">
  showRatingImages();
</script>