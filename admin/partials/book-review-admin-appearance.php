<h3><?php esc_html_e( 'Review Box', $this->plugin_name ); ?></h3>
<form action="options.php" method="post">
  <?php
    @settings_fields( 'general_options' );
    @do_settings_fields( 'general_options' );
  ?>

  <table class="form-table">
    <tbody>
      <!-- Position -->
      <tr>
        <th scope="row">
          <label for="book_review_box_position_top">
            <?php esc_html_e( 'Position', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <!-- Top -->
          <input id="book_review_box_position_top" type="radio"
            name="book_review_general[book_review_box_position]" value="top"
            <?php echo checked( 'top', $general_option['book_review_box_position'], false ); ?>>
          <label for="book_review_box_position_top">
            <?php esc_html_e( 'Top', $this->plugin_name ); ?>
          </label>

          <!-- Bottom -->
          <input id="book_review_box_position_bottom" type="radio"
            name="book_review_general[book_review_box_position]"
            value="bottom"
            <?php echo checked( 'bottom', $general_option['book_review_box_position'], false ); ?>>
          <label for="book_review_box_position_bottom">
            <?php esc_html_e( 'Bottom', $this->plugin_name ); ?>
          </label>

          <p class="description">
            <?php esc_html_e( 'Whether to show the review box at the top or bottom of a post.', $this->plugin_name ); ?>
          </p>
        </td>
      </tr>

      <!-- Background Color -->
      <tr>
        <th scope="row">
          <label for="book_review_bg_color">
            <?php esc_html_e( 'Background Color', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_bg_color" class="color-picker" type="text"
            name="book_review_general[book_review_bg_color]"
            value="<?php echo esc_attr( $general_option['book_review_bg_color'] ); ?>">
        </td>
      </tr>

      <!-- Border Color -->
      <tr>
        <th scope="row">
          <label for="book_review_border_color">
            <?php esc_html_e( 'Border Color', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_border_color" class="color-picker"
            type="text" name="book_review_general[book_review_border_color]"
            value="<?php echo esc_attr( $general_option['book_review_border_color'] ); ?>">
        </td>
      </tr>

      <!-- Border Width -->
      <tr>
        <th scope="row">
          <label for="book_review_border_width">
            <?php esc_html_e( 'Border Width', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_border_width" class="small-text numeric-input"
            type="text" step="1" min="0" name="book_review_general[book_review_border_width]"
            value="<?php echo esc_attr( $general_option['book_review_border_width'] ); ?>">
            <?php esc_html_e( 'pixel(s)', $this->plugin_name ); ?>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Post Types -->
  <div>
    <h3><?php esc_html_e( 'Post Types', $this->plugin_name ); ?></h3>
    <p>
      <?php esc_html_e( 'Select the post types to show book information for.',
        $this->plugin_name ); ?>
    </p>
  </div>

  <table class="form-table">
    <tbody>
      <tr>
        <th scope="row">
          <?php esc_html_e( 'Post Types', $this->plugin_name ); ?>:
        </th>
        <td>
          <fieldset>
            <legend class="screen-reader-text">
              <span><?php esc_html_e( 'Post Types', $this->plugin_name ); ?></span>
            </legend>

            <?php foreach ( $keys as $key ) {
              if ( !empty( $post_types[$key]->label ) ) { ?>
            <label for="book_review_<?php echo esc_attr( $key ); ?>">
               <input id="book_review_<?php echo esc_attr( $key ); ?>" type="checkbox"
               name="book_review_general[book_review_post_types][<?php echo esc_attr( $key ); ?>]"
               value="1"
               <?php checked( '1', isset( $general_option['book_review_post_types'][$key] ) ?
                $general_option['book_review_post_types'][$key] : '' ); ?>>
               <?php esc_html_e( $post_types[$key]->label ); ?>
            </label>
            <br>
            <?php
              }
            } ?>
          </fieldset>
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>