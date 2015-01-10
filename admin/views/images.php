<p>
  <?php _e( 'Configure the images to use for displaying ratings.',
    $this->plugin_slug ) ?>
</p>

<form action="options.php" method="post">
  <?php
    @settings_fields( 'ratings_options' );
    @do_settings_fields( 'ratings_options' );
  ?>

  <table class="form-table">
    <tbody>
      <!-- Show rating on home page -->
      <tr>
        <th>
          <label for="book_review_rating_home">
            <?php _e( 'Show rating on home page', $this->plugin_slug ) ?>:
            &nbsp;&nbsp;
            <a href="#" class="tooltip">
              <?php echo $tooltip ?>
              <span>
                <?php _e( 'Whether to show the rating image on your home ' .
                  'page when summary text is used.', $this->plugin_slug ) ?>
              </span>
            </a>
          </label>
        </th>
        <td>
          <input id="book_review_rating_home" type="checkbox"
            name="book_review_ratings[book_review_rating_home]" value="1"
            <?php echo checked( '1', $ratings['book_review_rating_home'],
              false ); ?>>
        </td>
      </tr>

      <!-- Use default rating images -->
      <tr>
        <th>
          <label for="book_review_rating_default">
            <?php _e( 'Use default rating images', $this->plugin_slug ) ?>:
            &nbsp;&nbsp;
            <a href="#" class="tooltip">
              <?php echo $tooltip ?>
              <span>
                <?php _e( 'Whether to use the default rating images or ' .
                  'your own.', $this->plugin_slug ) ?>
              </span>
            </a>
          </label>
        </th>
        <td>
          <input id="book_review_rating_default" type="checkbox"
            name="book_review_ratings[book_review_rating_default]" value="1"
            <?php echo checked( '1', $ratings['book_review_rating_default'],
              false ); ?>
            onchange="showRatingImages();">
        </td>
      </tr>

      <!-- Rating Image URLs -->
      <tr>
        <th>
          <h4>
            <label>
              <?php _e( 'Rating Image URLs', $this->plugin_slug ) ?>
              &nbsp;&nbsp;
              <a href="#" class="tooltip">
                <?php echo $tooltip ?>
                <span>
                  <?php _e( 'To use your own rating images, enter the ' .
                    'URL of an image for each rating below (1-5).',
                    $this->plugin_slug ) ?>
                </span>
              </a>
            </label>
          </h4>
        </th>
        <td></td>
      </tr>

      <!-- 1-Star Image URL -->
      <tr class="rating">
        <th>
          <label for="book_review_rating_image1">
            <?php
              _e( 'One-star Image URL', $this->plugin_slug );
            ?>:
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
      <tr class="rating">
        <th>
          <label for="book_review_rating_image2">
            <?php
              _e( 'Two-star Image URL', $this->plugin_slug );
            ?>:
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
      <tr class="rating">
        <th>
          <label for="book_review_rating_image3">
            <?php
              _e( 'Three-star Image URL', $this->plugin_slug );
            ?>:
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
      <tr class="rating">
        <th>
          <label for="book_review_rating_image4">
            <?php
              _e( 'Four-star Image URL', $this->plugin_slug );
            ?>:
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
      <tr class="rating">
        <th>
          <label for="book_review_rating_image5">
            <?php
              _e( 'Five-star Image URL', $this->plugin_slug );
            ?>:
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