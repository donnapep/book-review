<p>
  <?php printf( __( 'Configure the images to use to display ratings. Please see the <a href="%s" target="_blank">documentation</a> for more information.', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/documentation/settings-rating-images/' ) ); ?>
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
            <?php esc_html_e( 'Excerpts', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_home" type="checkbox"
            name="book_review_ratings[book_review_rating_home]" value="1"
            <?php checked( '1', $ratings_option['book_review_rating_home'] ); ?>>
          <?php esc_html_e( 'Show the rating in excerpts', $this->plugin_name ); ?>
        </td>
      </tr>

      <!-- Use default rating images -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_default">
            <?php esc_html_e( 'Default Rating Images', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_default" type="checkbox"
            name="book_review_ratings[book_review_rating_default]" value="1"
            <?php checked( '1', $ratings_option['book_review_rating_default'] ); ?>>
          <?php esc_html_e( 'Use the default rating images', $this->plugin_name ); ?>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Rating Image URLs -->
  <div class="rating">
    <h3><?php esc_html_e( 'Rating Image URLs', $this->plugin_name ); ?></h3>
    <p>
      <?php esc_html_e( 'To use your own rating images, enter the URL of an image for each rating below (1-5).',
        $this->plugin_name ); ?>
    </p>
  </div>

  <table class="form-table rating">
    <tbody>
      <!-- 1-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image1">
            <?php esc_html_e( 'One-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image1" class="regular-text text-input"
            type="text" name="book_review_ratings[book_review_rating_image1]"
            value="<?php echo $ratings_option['book_review_rating_image1']; ?>">
        </td>
      </tr>

      <!-- 2-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image2">
            <?php esc_html_e( 'Two-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image2" class="regular-text text-input"
            type="text" name="book_review_ratings[book_review_rating_image2]"
            value="<?php echo $ratings_option['book_review_rating_image2']; ?>">
        </td>
      </tr>

      <!-- 3-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image3">
            <?php esc_html_e( 'Three-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image3" class="regular-text text-input"
            type="text" name="book_review_ratings[book_review_rating_image3]"
            value="<?php echo $ratings_option['book_review_rating_image3']; ?>">
        </td>
      </tr>

      <!-- 4-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image4">
            <?php esc_html_e( 'Four-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image4" class="regular-text text-input"
            type="text" name="book_review_ratings[book_review_rating_image4]"
            value="<?php echo $ratings_option['book_review_rating_image4']; ?>">
        </td>
      </tr>

      <!-- 5-Star Image URL -->
      <tr>
        <th scope="row">
          <label for="book_review_rating_image5">
            <?php esc_html_e( 'Five-star Image URL', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_rating_image5" class="regular-text text-input"
            type="text" name="book_review_ratings[book_review_rating_image5]"
            value="<?php echo $ratings_option['book_review_rating_image5']; ?>">
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>

<script type="text/javascript">
  // showRatingImages();
</script>