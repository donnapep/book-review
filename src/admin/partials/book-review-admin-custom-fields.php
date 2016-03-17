<form action="options.php" method="post">
  <?php
    // Trying to save more options here results in an error.
    @settings_fields( 'fields_options' );
    @do_settings_fields( 'fields_options' );
  ?>

  <div>
    <p>
      <?php  printf( __( 'Custom fields provide the ability to show additional details about a book. Every custom field entered here will be shown in the <em>Book Info</em> section when editing a post. Click the <em>Add Field</em> button to add a new field, or drag and drop existing fields to reorder them. Please see the <a href="%s" target="_blank">documentation</a> for more information.', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/documentation/settings-custom-fields/' ) ); ?>
    </p>
    <p>
      <?php _e( '<em>Please note that custom fields are not automatically populated by the Google Books API.</em>', $this->plugin_name ); ?>
    </p>
  </div>

  <ul id="fields">
    <?php foreach ( $fields_option['fields'] as $field_id => $field_values ): ?>
      <li class="field">
        <input class="label" type="text" placeholder="Field Name (e.g. Illustrator)"
          name='<?php echo esc_attr( "book_review_fields[fields][$field_id][label]" ); ?>'
          value="<?php echo esc_attr( $field_values['label'] ); ?>">
        <div class="dashicons dashicons-sort"></div>
      </li>
    <?php endforeach; ?>
  </ul>

  <input class="add-field button" type="button" value="<?php esc_html_e( 'Add Field', $this->plugin_name ); ?>">

  <?php @submit_button(); ?>
</form>