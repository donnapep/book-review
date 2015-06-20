<p>
  <?php
    printf( __( 'This plugin uses the Google Books API to automatically populate the details of a book. In order to take advantage of this feature, you must first sign up for and enter an API key as described <a href="%s" target="_blank">here</a>.', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/book-review/#advanced' ) );
  ?>
</p>

<form action="options.php" method="post">
  <?php
    @settings_fields( 'advanced_options' );
    @do_settings_fields( 'advanced_options' );
  ?>

  <table class="form-table">
    <tbody>
      <!-- Google API Key -->
      <tr>
        <th scope="row">
          <label for="book_review_api_key">
            <?php esc_html_e( 'Google API Key', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_api_key" class="text-input" type="text"
            name="book_review_advanced[book_review_api_key]"
            value="<?php echo esc_attr( $advanced['book_review_api_key'] ); ?>">
          <p class="description">
            <?php
              printf( __( 'Your Google API key obtained from the <a href="%s" target="_blank">Google Developers Console</a>.', $this->plugin_name ), esc_url( 'https://console.developers.google.com/' ) );
            ?>
            <?php esc_html_e( '', $this->plugin_name ); ?>
          </p>
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>