<form action="options.php" method="post">
  <?php
    @settings_fields( 'links_options' );
    @do_settings_fields( 'links_options' );
  ?>

  <!-- Standard Links -->
  <h3><?php esc_html_e( 'Site Links', $this->plugin_name ); ?></h3>
  <p>
    <?php esc_html_e( 'Which site(s) would you like to link to in your reviews?', $this->plugin_name ); ?>
  </p>

  <div class="site-links">
    <label for="goodreads">
      <input id="goodreads" class="site-link" type="checkbox" data-site-link="goodreads"
        name="book_review_links[sites][book_review_goodreads][active]" value="1"
        <?php checked( '1', $links_option['sites']['book_review_goodreads']['active'] ); ?>>
      <?php esc_html_e( 'Goodreads', $this->plugin_name ); ?>
    </label>

    <label for="barnes-noble">
      <input id="barnes-noble" class="site-link" type="checkbox" data-site-link="barnes-noble"
        name="book_review_links[sites][book_review_barnes_noble][active]" value="1"
        <?php checked( '1', $links_option['sites']['book_review_barnes_noble']['active'] ); ?>>
      <?php esc_html_e( 'Barnes & Noble', $this->plugin_name ); ?>
    </label>
  </div>

  <div class="links">
    <!-- Link Style -->
    <section class="goodreads postbox hide">
      <h4 class="hndle">
        <span>
          <?php esc_html_e( 'Goodreads', $this->plugin_name ); ?>
        </span>
      </h4>

      <div class="inside">
        <p>
          <?php esc_html_e( 'Which type of link would you like to use?', $this->plugin_name ); ?>
        </p>

        <!-- Button -->
        <div class="link-type">
          <input id="goodreads-button" type="radio" name="book_review_links[sites][book_review_goodreads][type]" value="button"
            <?php echo checked( 'button', $links_option['sites']['book_review_goodreads']['type'], false ); ?>>
          <label for="goodreads-button">
            <img src="<?php echo esc_url( plugins_url( '/includes/images/goodreads.png', dirname( __DIR__ ) ) ); ?>" alt="Goodreads">
          </label>
        </div>

        <!-- Text -->
        <div class="link-type">
          <input id="goodreads-text" type="radio" name="book_review_links[sites][book_review_goodreads][type]" value="text"
            <?php echo checked( 'text', $links_option['sites']['book_review_goodreads']['type'], false ); ?>>
          <label for="goodreads-text">
            <span class="link">
              <?php esc_html_e( 'Goodreads', $this->plugin_name ); ?>
            </span>
          </label>
          <input type="hidden" name="book_review_links[sites][book_review_goodreads][text]"
            value="<?php echo esc_attr( $links_option['sites']['book_review_goodreads']['text'] ); ?>">
        </div>

        <!-- Custom Image -->
        <div class="link-type">
          <input id="goodreads-custom" class="custom-image" type="radio" name="book_review_links[sites][book_review_goodreads][type]" value="custom"
            <?php echo checked( 'custom', $links_option['sites']['book_review_goodreads']['type'], false ); ?>>
          <label for="goodreads-custom">
            <?php esc_html_e( 'Custom Image', $this->plugin_name ); ?>
          </label>

          <div class="url-container">
            <input class="url text-input" type="text" name="book_review_links[sites][book_review_goodreads][url]"
              value="<?php echo esc_url( $links_option['sites']['book_review_goodreads']['url'] ); ?>">
            <a class="set-custom-image button-secondary" href="#">
              <?php esc_html_e( 'Set Custom Image', $this->plugin_name ) ?>
            </a>
          </div>
        </div><!-- End .link-type -->
      </div><!-- End .inside -->
    </section><!-- End .goodreads -->

    <section class="barnes-noble postbox hide">
      <h4 class="hndle">
        <span>
          <?php esc_html_e( 'Barnes & Noble', $this->plugin_name ); ?>
        </span>
      </h4>

      <div class="inside">
        <p>
          <?php esc_html_e( 'Which type of link would you like to use?', $this->plugin_name ); ?>
        </p>

        <!-- Button -->
        <div class="link-type">
          <input id="barnes-noble-button" type="radio" name="book_review_links[sites][book_review_barnes_noble][type]" value="button"
            <?php echo checked( 'button', $links_option['sites']['book_review_barnes_noble']['type'], false ); ?>>
          <label for="barnes-noble-button">
            <img src="<?php echo esc_url( plugins_url( '/includes/images/barnes-noble.png', dirname( __DIR__ ) ) ); ?>" alt="Barnes & Noble">
          </label>
        </div>

        <!-- Text -->
        <div class="link-type">
          <input id="barnes-noble-text" type="radio" name="book_review_links[sites][book_review_barnes_noble][type]" value="text"
            <?php echo checked( 'text', $links_option['sites']['book_review_barnes_noble']['type'], false ); ?>>
          <label for="barnes-noble-text">
            <span class="link">
              <?php esc_html_e( 'Barnes & Noble', $this->plugin_name ); ?>
            </span>
            <input type="hidden" name="book_review_links[sites][book_review_barnes_noble][text]"
              value="<?php echo esc_attr( $links_option['sites']['book_review_barnes_noble']['text'] ); ?>">
          </label>
        </div>

        <!-- Custom Image -->
        <div class="link-type">
          <input id="barnes-noble-custom" class="custom-image" type="radio" name="book_review_links[sites][book_review_barnes_noble][type]" value="custom"
            <?php echo checked( 'custom', $links_option['sites']['book_review_barnes_noble']['type'], false ); ?>>
          <label for="barnes-noble-custom">
            <?php esc_html_e( 'Custom Image', $this->plugin_name ); ?>
          </label>

          <div class="url-container">
            <input class="url text-input" type="text" name="book_review_links[sites][book_review_barnes_noble][url]"
              value="<?php echo esc_url( $links_option['sites']['book_review_barnes_noble']['url'] ); ?>">
            <a class="set-custom-image button-secondary" href="#">
              <?php esc_html_e( 'Set Custom Image', $this->plugin_name ) ?>
            </a>
          </div>
        </div><!-- End .link-type -->
      </div><!-- End .inside -->
    </section><!-- End .barnes-noble -->
  </div>

  <hr>

  <h3><?php esc_html_e( 'Custom Links', $this->plugin_name ); ?></h3>
  <p>
    <?php
      printf( __( 'Configure the links that you would like to display with every book review. For every link added here, a new field will be shown in the <em>Book Info</em> section when editing a post. If you leave the <em>Link Image URL</em> field blank, links will be shown as text by default. Please see the <a href="%s" target="_blank">documentation</a> for more information.', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/documentation/settings-links/' ) );
    ?>
  </p>

  <!-- Custom Links -->
  <table id="custom-links" class="links widefat striped">
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
      </tr>
      <?php
          $index++;
        }
      ?>
    </tbody>
  </table>

  <input class="add-link button" type="button" value="<?php esc_html_e( 'Add Link', $this->plugin_name ); ?>">

  <hr>

  <!-- General -->
  <div class="link-target">
    <h3><?php esc_html_e( 'General', $this->plugin_name ); ?></h3>
    <p>
      <?php esc_html_e( 'Would you like the links to open in a new tab?', $this->plugin_name ); ?>
    </p>
  </div>

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

  <?php @submit_button(); ?>
</form>