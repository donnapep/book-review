<div id="book-review-notice" class="updated">
  <img class="logo" src="<?php echo BOOK_REVIEW_PLUGIN_URL; ?>admin/images/icon.png" />
  <div class="content">
    <div class="thank-you">
      <?php esc_html_e( 'Thank you!', $this->plugin_name ) ?>
    </div>
    <div>
      <?php
        printf( __( 'Now that you\'re writing amazing reviews, why not monetize them?', $this->plugin_name ) );
      ?>
    </div>
    <div>
      <?php
      printf( __( '<a class="cta" href="%s" target="_blank">Learn more</a> about Affiliate Linkalizer for Amazon and how it can help you to earn income from your book reviews.', $this->plugin_name), esc_url( 'http://wpreviewplugins.com/product/affiliate-linkalizer-amazon/#utm_source=plugins+page&utm_medium=plugin&utm_campaign=linkalizer&utm_content=book+review' ) );
      ?>
    </div>
  </div>
  <?php printf( __( '<a href="%1$s" class="dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>', $this->plugin_name ), esc_url( add_query_arg( 'book_review_dismiss_notice', wp_create_nonce( 'book_review_dismiss_notice' ) ) ) ); ?>
</div>