<form action="options.php" method="post">
  <?php
    @settings_fields( 'links_options' );
    @do_settings_fields( 'links_options' );
  ?>

  <table class="target-container form-table">
    <tbody>
      <!-- Open links in new tab -->
      <tr>
        <th scope="row">
          <label for="book_review_target">
            <?php esc_html_e( 'Link Target', $this->plugin_name ); ?>:
          </label>
        </th>
        <td>
          <input id="book_review_target" type="checkbox"
            name="book_review_links[book_review_target]" value="1"
            <?php echo checked( '1', $links_option['book_review_target'], false ) . '>'; ?>
          <?php esc_html_e( 'Open links in a new tab', $this->plugin_name ); ?>
        </td>
      </tr>
    </tbody>
  </table>

  <h3><?php esc_html_e( 'Custom Links', $this->plugin_name ); ?></h3>
  <p>
    <?php
      printf( __( 'Configure the links that you would like to display with every book review. For every link added here, a new field will be shown in the <em>Book Info</em> section when editing a post. If you leave the <em>Link Image URL</em> field blank, links will be shown as text by default. Please see the <a href="%s" target="_blank">documentation</a> for additional details.', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/book-review/#links' ) );
    ?>
  </p>

  <!-- Link Text and Link Image URLs -->
  <table id="custom-links" class="links widefat">
    <thead>
      <tr>
        <th class="text">
          <label>
            <?php esc_html_e( 'Link Text (required)', $this->plugin_name ); ?>
          </label>
        </th>
        <th class="url">
          <label>
            <?php esc_html_e( 'Link Image URL (optional)', $this->plugin_name ); ?>
          </label>
        </th>
        <th class="active">
          <?php esc_html_e( 'Active', $this->plugin_name ); ?>
        </th>
        <!-- <th></th> -->
      </tr>
    </thead>
    <tbody>
      <?php
        // Need to assign a unique value for the first array index so that the
        // Settings API will pass all custom links to the validation function.
        $index = 1;

        foreach ( $results as $result ) { ?>
      <tr>
        <td>
          <?php //wp_nonce_field( "book_review_delete_link", "book_review_delete_link_{$result->custom_link_id}_nonce" ); ?>
          <input class="id" type="hidden"
            name='<?php echo esc_attr( "book_review_links[$index][id]" ); ?>'
            value="<?php echo esc_attr( $result->custom_link_id ); ?>">
          <input type="text"
            name='<?php echo esc_attr( "book_review_links[$index][text]" ); ?>'
            value="<?php echo esc_attr( $result->text ); ?>">
        </td>
        <td>
          <input type="text"
            name='<?php echo esc_attr( "book_review_links[$index][image]" ); ?>'
            value="<?php echo esc_url( $result->image_url ); ?>">
        </td>
        <td class="active">
          <input type="checkbox"
            name='<?php echo esc_attr( "book_review_links[$index][active]" ); ?>' value="1"
            <?php checked( '1', $result->active ) . '>'; ?>
        </td>
        <!-- <td>
          <a class="delete" href="#" data-id="<?php //echo esc_attr( $result->custom_link_id ); ?>">Delete</a>
        </td> -->
      </tr>
      <?php
          $index++;
        }
      ?>
    </tbody>
  </table>
  <input class="add-link button" type="button" value="<?php esc_html_e( 'Add Link', $this->plugin_name ); ?>"
    onclick="addLink();">

  <!-- TODO: Remove this. -->
  <?php do_action( 'book_review_after_links_options', $links_option ); ?>
  <?php @submit_button(); ?>
</form>