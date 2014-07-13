<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * the User Interface to the end user.
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <donnapep@gmail.com>
 * @license   GPL-2.0+
 * @link      http://donnapeplinskie.com
 * @copyright 2014 Donna Peplinskie
 */
?>

<div class="wrap">
  <h2>
    <?php echo esc_html( get_admin_page_title() ); ?>
  </h2>
  <div id="book_review_settings" class="postbox-container">
    <form action="options.php" method="post">
      <?php @settings_fields( 'book_review_options' ); ?>
      <?php @do_settings_fields( 'book_review_options' ); ?>
      <h3>
        <?php _e( 'Appearance', $this->plugin_slug ) ?>
      </h3>
      <table class="form-table">
        <tbody>

          <!-- Review Box Position -->
          <tr valign="top">
            <th scope="row">
              <?php _e( 'Review Box Position', $this->plugin_slug ) ?>:
              &nbsp;&nbsp;
              <a href="#" class="tooltip">
                <?php echo $tooltip ?>
                <span>
                  <?php _e( 'Whether to show the review box at the top or bottom
                    of a post.', $this->plugin_slug ) ?>
                </span>
              </a>
            </th>
            <td>
              <!-- Top -->
              <input id="book_review_box_position_top" type="radio"
                name="book_review_general[book_review_box_position]" value="top"
                <?php echo checked( 'top', $general['book_review_box_position'],
                  false ); ?>>
              <label for="book_review_box_position_top">
                <?php _e( 'Top', $this->plugin_slug ) ?>
              </label>

              <!-- Bottom -->
              <input id="book_review_box_position_bottom" type="radio"
                name="book_review_general[book_review_box_position]"
                value="bottom"
                <?php echo checked( 'bottom',
                  $general['book_review_box_position'], false ); ?>>
              <label for="book_review_box_position_bottom">
                <?php _e( 'Bottom', $this->plugin_slug ) ?>
              </label>
            </td>
          </tr>

          <!-- Review Box Background Color -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_bg_color">
                <?php _e( 'Review Box Background Color', $this->plugin_slug ) ?>:
              </label>
            </th>
            <td>
              <input id="book_review_bg_color" class="color-picker" type="text"
                name="book_review_general[book_review_bg_color]"
                value="<?php echo $general['book_review_bg_color']; ?>">
            </td>
          </tr>

          <!-- Review Box Border Color -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_border_color">
                <?php _e( 'Review Box Border Color', $this->plugin_slug ) ?>:
              </label>
            </th>
            <td>
              <input id="book_review_border_color" class="color-picker"
                type="text" name="book_review_general[book_review_border_color]"
                value="<?php echo $general['book_review_border_color']; ?>">
            </td>
          </tr>

          <!-- Release Date Format -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_date_format">
                <?php _e( 'Release Date Format', $this->plugin_slug ) ?>:
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Format that the Release Date will be shown in.',
                      $this->plugin_slug ) ?>
                  </span>
                </a>
              </label>
            </th>
            <td>
              <select id="book_review_date_format"
                name="book_review_general[book_review_date_format]">
                <?php $this -> render_date_format_field(); ?>
              </select>
            </td>
          </tr>
        </tbody>
      </table>
      <h3>
        <?php _e( 'Rating Images', $this->plugin_slug ) ?>
      </h3>
      <p>
        <?php _e( 'Configure the images to use for displaying ratings.',
          $this->plugin_slug ) ?>
      </p>
      <table class="form-table">
        <tbody>

          <!-- Show rating on home page -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_rating_home">
                <?php _e( 'Show rating on home page', $this->plugin_slug ) ?>:
                &nbsp;&nbsp;
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Whether to show the rating image on your home
                      page when summary text is used.', $this->plugin_slug ) ?>
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
          <tr valign="top">
            <th scope="row">
              <label for="book_review_rating_default">
                <?php _e( 'Use default rating images', $this->plugin_slug ) ?>:
                &nbsp;&nbsp;
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Whether to use the default rating images or your
                      own.', $this->plugin_slug ) ?>
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
          <tr class="rating">
            <th scope="row">
              <h4>
                <label>
                  <?php _e( 'Rating Image URLs', $this->plugin_slug ) ?>
                  &nbsp;&nbsp;
                  <a href="#" class="tooltip">
                    <?php echo $tooltip ?>
                    <span>
                      <?php _e( 'To use your own rating images, enter the URL of
                        an image for each rating below (1-5).',
                        $this->plugin_slug ) ?>
                    </span>
                  </a>
                </label>
              </h4>
            </th>
            <td></td>
          </tr>

          <!-- 1-Star Image URL -->
          <tr class="rating" valign="top">
            <th scope="row">
              <label for="book_review_rating_image1">
                <?php
                  _e( 'One-star Image URL', $this->plugin_slug );
                ?>:
              </label>
            </th>
            <td>
              <input id="book_review_rating_image1" class="text-input"
                type="text" name="book_review_ratings[book_review_rating_image1]"
                value="<?php
                  echo esc_url( $ratings['book_review_rating_image1'] ); ?>" />
            </td>
          </tr>

          <!-- 2-Star Image URL -->
          <tr class="rating" valign="top">
            <th scope="row">
              <label for="book_review_rating_image2">
                <?php
                  _e( 'Two-star Image URL', $this->plugin_slug );
                ?>:
              </label>
            </th>
            <td>
              <input id="book_review_rating_image2" class="text-input"
                type="text" name="book_review_ratings[book_review_rating_image2]"
                value="<?php
                  echo esc_url( $ratings['book_review_rating_image2'] ); ?>" />
            </td>
          </tr>

          <!-- 3-Star Image URL -->
          <tr class="rating" valign="top">
            <th scope="row">
              <label for="book_review_rating_image3">
                <?php
                  _e( 'Three-star Image URL', $this->plugin_slug );
                ?>:
              </label>
            </th>
            <td>
              <input id="book_review_rating_image3" class="text-input"
                type="text" name="book_review_ratings[book_review_rating_image3]"
                value="<?php
                  echo esc_url( $ratings['book_review_rating_image3'] ); ?>" />
            </td>
          </tr>

          <!-- 4-Star Image URL -->
          <tr class="rating" valign="top">
            <th scope="row">
              <label for="book_review_rating_image4">
                <?php
                  _e( 'Four-star Image URL', $this->plugin_slug );
                ?>:
              </label>
            </th>
            <td>
              <input id="book_review_rating_image4" class="text-input"
                type="text" name="book_review_ratings[book_review_rating_image4]"
                value="<?php
                  echo esc_url( $ratings['book_review_rating_image4'] ); ?>" />
            </td>
          </tr>

          <!-- 5-Star Image URL -->
          <tr class="rating" valign="top">
            <th scope="row">
              <label for="book_review_rating_image5">
                <?php
                  _e( 'Five-star Image URL', $this->plugin_slug );
                ?>:
              </label>
            </th>
            <td>
              <input id="book_review_rating_image5" class="text-input"
                type="text" name="book_review_ratings[book_review_rating_image5]"
                value="<?php
                  echo esc_url( $ratings['book_review_rating_image5'] ); ?>" />
            </td>
          </tr>
        </tbody>
      </table>
      <h3>
        <?php _e( 'Links', $this->plugin_slug ) ?>
      </h3>
      <p>
        <?php _e( 'Configure the links that you would like to display with every
          book review.', $this->plugin_slug ) ?>
      </p>
      <table class="custom-links form-table">
        <tbody>

          <!-- Number of Links -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_num_links">
                <?php _e( 'Number of Links', $this->plugin_slug ) ?>:
                &nbsp;&nbsp;
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Select the number of links you would like to add
                      to each book review.', $this->plugin_slug ) ?>
                  </span>
                </a>
              </label>
            </th>
            <td>
              <select id="book_review_num_links"
                name="book_review_links[book_review_num_links]"
                onChange="showLinks(parseInt(this.value));">
                <?php $this -> render_num_links_field(); ?>
              </select>
            </td>
          </tr>

          <!-- Open links in new tab -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_link_target">
                <?php _e( 'Open links in new tab', $this->plugin_slug ) ?>:
                &nbsp;&nbsp;
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Whether to open links in the same window or in a
                      new tab.', $this->plugin_slug ) ?>
                  </span>
                </a>
              </label>
            </th>
            <td>
              <input id="book_review_link_target" type="checkbox"
                name="book_review_links[book_review_link_target]" value="1"
                <?php echo checked( '1', $links['book_review_link_target'],
                  false ) . '/>' ?>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Link Text and Link Image URLs -->
      <table class="links widefat">
        <thead>
          <tr>
            <th>
              <label>
                <?php _e( 'Link Text', $this->plugin_slug ) ?>&nbsp;&nbsp;
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Enter the text for each link. For every link
                      added here, a new field will be shown in the Book Info
                      section when editing a post.', $this->plugin_slug ) ?>
                  </span>
                </a>
              </label>
            </th>
            <th>
              <label>
                <?php _e( 'Link Image URL', $this->plugin_slug ) ?>&nbsp;&nbsp;
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'If you would like to show links as images, enter
                      the URL of an image for each link below. If you leave this
                      field blank, links will be shown as text.',
                      $this->plugin_slug ) ?>
                  </span>
                </a>
              </label>
            </th>
          </tr>
        </thead>
        <tbody>

          <!--Link 1 -->
          <tr id="link1">
            <td>
              <input id="book_review_link_text1" class="text-input"
                type="text" name="book_review_links[book_review_link_text1]"
                value="<?php echo $links['book_review_link_text1']; ?>" />
            </td>
            <td>
              <input id="book_review_link_image1" class="text-input"
                type="text" name="book_review_links[book_review_link_image1]"
                value="<?php echo $links['book_review_link_image1']; ?>" />
            </td>
          </tr>

          <!--Link 2 -->
          <tr id="link2">
            <td>
              <input id="book_review_link_text2" class="text-input"
                type="text" name="book_review_links[book_review_link_text2]"
                value="<?php echo $links['book_review_link_text2']; ?>" />
            </td>
            <td>
              <input id="book_review_link_image2" class="text-input"
                type="text" name="book_review_links[book_review_link_image2]"
                value="<?php echo $links['book_review_link_image2']; ?>" />
            </td>
          </tr>

          <!--Link 3 -->
          <tr id="link3">
            <td>
              <input id="book_review_link_text3" class="text-input"
                type="text" name="book_review_links[book_review_link_text3]"
                value="<?php echo $links['book_review_link_text3']; ?>" />
            </td>
            <td>
              <input id="book_review_link_image3" class="text-input"
                type="text" name="book_review_links[book_review_link_image3]"
                value="<?php echo $links['book_review_link_image3']; ?>" />
            </td>
          </tr>

          <!--Link 4 -->
          <tr id="link4">
            <td>
              <input id="book_review_link_text4" class="text-input"
                type="text" name="book_review_links[book_review_link_text4]"
                value="<?php echo $links['book_review_link_text4']; ?>" />
            </td>
            <td>
              <input id="book_review_link_image4" class="text-input"
                type="text" name="book_review_links[book_review_link_image4]"
                value="<?php echo $links['book_review_link_image4']; ?>" />
            </td>
          </tr>

          <!--Link 5 -->
          <tr id="link5">
            <td>
              <input id="book_review_link_text5" class="text-input"
                type="text" name="book_review_links[book_review_link_text5]"
                value="<?php echo $links['book_review_link_text5']; ?>" />
            </td>
            <td>
              <input id="book_review_link_image5" class="text-input"
                type="text" name="book_review_links[book_review_link_image5]"
                value="<?php echo $links['book_review_link_image5']; ?>" />
            </td>
          </tr>
        </tbody>
      </table>
      <h3>
        <?php _e( 'Advanced', $this->plugin_slug ) ?>
      </h3>
      <p>
        <?php _e( 'This plugin uses the Google Books API to automatically
          populate the details of a book. In order to take advantage of this
          feature, you must first sign up for and enter an API key as described
          <a href="http://donnapeplinskie.com/wordpress-book-review-plugin/"
          target="_blank">here</a>.', $this->plugin_slug ) ?>
      </p>
      <table class="form-table">
        <tbody>

          <!-- Google API Key -->
          <tr valign="top">
            <th scope="row">
              <label for="book_review_api_key">
                <?php _e( 'Google API Key', $this->plugin_slug ) ?>:
                <a href="#" class="tooltip">
                  <?php echo $tooltip ?>
                  <span>
                    <?php _e( 'Your Google API key obtained from the Google
                      Developers Console.', $this->plugin_slug ) ?>
                  </span>
                </a>
              </label>
            </th>
            <td>
              <input id="book_review_api_key" class="text-input" type="text"
                name="book_review_advanced[book_review_api_key]"
                value="<?php echo $advanced['book_review_api_key']; ?>">
            </td>
          </tr>
        </tbody>
      </table>

      <script type="text/javascript">
        showRatingImages();
        showLinks();
      </script>

      <?php @submit_button(); ?>
    </form>
  </div>
</div>