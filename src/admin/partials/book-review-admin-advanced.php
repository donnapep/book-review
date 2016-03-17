<form action="options.php" method="post">
  <?php
    @settings_fields( 'advanced_options' );
    @do_settings_fields( 'advanced_options' );
  ?>

  <div>
    <h3><?php esc_html_e( 'Google API', $this->plugin_name ); ?></h3>
    <p>
      <?php printf( __( 'This plugin uses the Google Books API to automatically populate the details of a book. In order to take advantage of this feature, you must first sign up for and enter an API key as described <a href="%s" target="_blank">here</a>.', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/documentation/settings-advanced/' ) ); ?>
    </p>
  </div>

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
          <input id="book_review_api_key" class="regular-text text-input" type="text"
            name="book_review_advanced[book_review_api_key]"
            value="<?php echo esc_attr( $advanced_option['book_review_api_key'] ); ?>">
          <p class="description">
            <?php
              printf( __( 'Your Google API key obtained from the <a href="%s" target="_blank">Google Developers Console</a>.', $this->plugin_name ), esc_url( 'https://console.developers.google.com/' ) );
            ?>
            <?php esc_html_e( '', $this->plugin_name ); ?>
          </p>
        </td>
      </tr>
      <!-- Country -->
      <tr>
        <th scope="row">
          <label for="book_review_country">
            <?php esc_html_e( 'Country', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <select id="book_review_country" name="book_review_advanced[book_review_country]" >
            <?php $this->add_countries(); ?>
          </select>
          <p class="description">
            <?php esc_html_e( 'The Google Books API requires your geographic location in order to return content.', $this->plugin_name ); ?>
          </p>
        </td>
      </tr>
    </tbody>
  </table>

  <?php @submit_button(); ?>
</form>