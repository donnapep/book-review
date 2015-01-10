<form action="options.php" method="post">
  <?php
    @settings_fields( 'links_options' );
    @do_settings_fields( 'links_options' );
  ?>

  <?php do_action( 'book_review_before_links_tab_content', $links_option ); ?>

  <table class="target-container form-table">
    <tbody>
      <!-- Open links in new tab -->
      <tr>
        <th>
          <label for="book_review_target">
            <?php _e( 'Open links in new tab', $this->plugin_slug ) ?>:
            &nbsp;&nbsp;
            <a href="#" class="tooltip">
              <?php echo $tooltip ?>
              <span>
                <?php _e( 'Whether to open links in the same window or ' .
                  'in a new tab.', $this->plugin_slug ) ?>
              </span>
            </a>
          </label>
        </th>
        <td>
          <input id="book_review_target" type="checkbox"
            name="book_review_links[book_review_target]" value="1"
            <?php echo checked( '1', $links_option['book_review_target'],
              false ) . '/>' ?>
        </td>
      </tr>
    </tbody>
  </table>

  <h3><?php _e( 'Custom Links', $this->plugin_slug ) ?></h3>
  <p>
    <?php _e( 'Configure the links that you would like to display with ' .
      'every book review.', $this->plugin_slug ) ?>
  </p>

  <!-- Link Text and Link Image URLs -->
  <table id="custom-links" class="links widefat">
    <thead>
      <tr>
        <th>
          <label>
            <?php _e( 'Link Text', $this->plugin_slug ) ?>&nbsp;&nbsp;
            <a href="#" class="tooltip">
              <?php echo $tooltip ?>
              <span>
                <?php _e( 'Enter the text for each link. For every link ' .
                  'added here, a new field will be shown in the Book ' .
                  'Info section when editing a post.',
                  $this->plugin_slug ) ?>
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
                <?php _e( 'If you would like to show links as images, ' .
                  'enter the URL of an image for each link below. If you ' .
                  'leave this field blank, links will be shown as text.',
                  $this->plugin_slug ) ?>
              </span>
            </a>
          </label>
        </th>
        <th class="active">
          <?php _e( 'Active', $this->plugin_slug ) ?>
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
            name='<?php echo "book_review_links[{$index}][id]" ?>'
            value="<?php echo esc_attr( $result->custom_link_id ); ?>" />
          <input type="text"
            name='<?php echo "book_review_links[{$index}][text]" ?>'
            value="<?php echo esc_attr( $result->text ); ?>" />
        </td>
        <td>
          <input class="text-input" type="text"
            name='<?php echo "book_review_links[{$index}][image]" ?>'
            value="<?php echo esc_attr( $result->image_url ); ?>" />
        </td>
        <td class="active">
          <input type="checkbox"
            name='<?php echo "book_review_links[{$index}][active]" ?>'
            value="1"
            <?php echo checked( '1', $result->active, false ) . '/>' ?>
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
  <input class="add-link button" type="button" value="<?php _e( 'Add Link', $this->plugin_slug ) ?>" onclick="addLink();" />

  <?php do_action( 'book_review_after_links_tab_content', $links_option ); ?>
  <?php @submit_button(); ?>
</form>