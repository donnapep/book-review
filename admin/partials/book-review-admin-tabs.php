<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * the User Interface to the end user.
 *
 * @package   Book_Review
 * @author    Donna Peplinskie <support@wpreviewplugins.com>
 * @license   GPL-2.0+
 * @link      http://wpreviewplugins.com/
 * @copyright 2014 Donna Peplinskie
 */
?>

<div class="wrap">
  <h2>
    <?php echo esc_html( get_admin_page_title() ); ?>
  </h2>

  <div class="book-review-admin">
    <h2 class="nav-tab-wrapper">
      <?php $this->render_tabs(); ?>
    </h2>

    <?php $this->render_tabbed_content(); ?>
  </div>
</div>