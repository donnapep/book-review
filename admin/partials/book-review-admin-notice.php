<div id="book-review-notice" class="updated">
  <img class="logo" src="http://wpreviewplugins.com/wp-content/uploads/2015/06/icon-orange.png" />
  <div class="content">
    <span class="thank-you">
      <?php esc_html_e( 'Thank you!', $this->plugin_name ) ?>
    </span>
    <div class="message">
      <?php
        printf( __( 'Want to monetize your book reviews? <a href="%s" target="_blank">Enter to win</a> the <span class="linkalizer">Affiliate Linkalizer for Amazon</span> plugin free for 12 months!', $this->plugin_name ), esc_url( 'http://wpreviewplugins.com/affiliate-linkalizer/' ) );
      ?>
    </div>
  </div>
  <?php printf( __( '<a href="%1$s" class="dismiss"><span class="screen-reader-text">Dismiss this notice.</span></a>', $this->plugin_name ), esc_url( add_query_arg( 'book_review_dismiss_notice', wp_create_nonce( 'book_review_dismiss_notice' ) ) ) ); ?>
</div>