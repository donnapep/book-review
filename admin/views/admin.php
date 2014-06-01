<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
?>

<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<div id="book_review_settings" class="postbox-container" style="width: 70%;">
		<form action="options.php" method="post">
			<?php @settings_fields( 'book_review_options' ); ?>
			<?php @do_settings_fields( 'book_review_options' ); ?>
			<h3><?php _e( 'Appearance', $this->plugin_slug ) ?></h3>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<?php _e( 'Review Box Position', $this->plugin_slug ) ?>:&nbsp;&nbsp
							<a href="#" class="tooltip">
								<?php echo $tooltip ?>
								<span><?php _e( 'Whether to show the review box at the top or bottom of a post.', $this->plugin_slug ) ?></span>
							</a>
						</th>
						<td>
							<input id="book_review_box_position_top" type="radio" name="book_review_general[book_review_box_position]" value="top" <?php echo checked( 'top', $general['book_review_box_position'], false ); ?>><label for="book_review_box_position_top"><?php _e( 'Top', $this->plugin_slug ) ?></label>
							<input id="book_review_box_position_bottom" type="radio" name="book_review_general[book_review_box_position]" value="bottom" <?php echo checked( 'bottom', $general['book_review_box_position'], false ); ?>><label for="book_review_box_position_bottom"><?php _e( 'Bottom', $this->plugin_slug ) ?></label>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_bg_color"><?php _e( 'Review Box Background Color', $this->plugin_slug ) ?>:</label>
						</th>
						<td>
							<input id="book_review_bg_color" class="color-picker" type="text" name="book_review_general[book_review_bg_color]" value="<?php echo $general['book_review_bg_color']; ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_border_color"><?php _e( 'Review Box Border Color', $this->plugin_slug ) ?>:</label>
						</th>
						<td>
							<input id="book_review_border_color" class="color-picker" type="text" name="book_review_general[book_review_border_color]" value="<?php echo $general['book_review_border_color']; ?>">
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_date_format">
								<?php _e( 'Release Date Format', $this->plugin_slug ) ?>:
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Format that the Release Date will be shown in.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<td>
							<?php $this -> create_date_format_field(); ?>
						</td>
					</tr>
				</tbody>
			</table>
			<h3><?php _e( 'Rating Images', $this->plugin_slug ) ?></h3>
			<p><?php _e( 'Configure the images to use for displaying ratings.', $this->plugin_slug ) ?></p>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_rating_home">
								<?php _e( 'Show rating on home page', $this->plugin_slug ) ?>:&nbsp;&nbsp
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Whether to show the rating image on your home page when summary text is used.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<td>
							<input id="book_review_rating_home" type="checkbox" name="book_review_ratings[book_review_rating_home]" value="1" <?php echo checked( 1, $ratings['book_review_rating_home'], false ); ?>>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_rating_default">
								<?php _e( 'Use default rating images', $this->plugin_slug ) ?>:&nbsp;&nbsp
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Whether to use the default rating images or your own.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<td>
							<input id="book_review_rating_default" type="checkbox" name="book_review_ratings[book_review_rating_default]" value="1" <?php echo checked( 1, $ratings['book_review_rating_default'], false ); ?> onchange="showRatingImages();">
						</td>
					</tr>
					<tr class="rating">
						<th scope="row">
							<h4>
								<label>
									<?php _e( 'Rating Image URLs', $this->plugin_slug ) ?>&nbsp;&nbsp
									<a href="#" class="tooltip">
										<?php echo $tooltip ?>
										<span><?php _e( 'To use your own rating images, enter the URL of an image for each rating below (1-5).', $this->plugin_slug ) ?></span>
									</a>
								</label>
							</h4>
						</th>
						<td></td>
					</tr>
					<?php
						for ($i = 1; $i <= 5; $i++) { ?>
							<tr class="rating" valign="top">
								<th scope="row">
									<label for="<?php echo 'book_review_rating_image' . $i; ?>">
										<?php
											if ($i == 1) {
												_e( 'One-star Image URL', $this->plugin_slug );
												echo ':';
											}
											else if ($i == 2) {
												_e( 'Two-star Image URL', $this->plugin_slug );
												echo ':';
											}
											else if ($i == 3) {
												_e( 'Three-star Image URL', $this->plugin_slug );
												echo ':';
											}
											else if ($i == 4) {
												_e( 'Four-star Image URL', $this->plugin_slug );
												echo ':';
											}
											else if ($i == 5) {
												_e( 'Five-star Image URL', $this->plugin_slug );
												echo ':';
											}
										?>
									</label>
								</th>
								<td>
									<?php $this -> create_rating_image_url_field( array( 'id' => 'book_review_rating_image' . $i ) ); ?>
								</td>
							</tr>
					<?php } ?>
				</tbody>
			</table>
			<h3><?php _e( 'Links', $this->plugin_slug ) ?></h3>
			<p><?php _e( 'Configure the links that you would like to display with every book review.', $this->plugin_slug ) ?></p>
			<table class="custom-links form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_num_links">
								<?php _e( 'Number of Links', $this->plugin_slug ) ?>:&nbsp;&nbsp
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Select the number of links you would like to add to each book review.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<td>
							<?php $this -> create_num_links_field(); ?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_link_target">
								<?php _e( 'Open links in new tab', $this->plugin_slug ) ?>:&nbsp;&nbsp
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Whether to open links in the same window or in a new tab.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<td>
							<?php $this -> create_link_target_field(); ?>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="links widefat">
				<thead>
					<tr>
						<th>
							<label>
								<?php _e( 'Link Text', $this->plugin_slug ) ?>&nbsp;&nbsp
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Enter the text for each link. For every link added here, a new field will be shown in the Book Info section when editing a post.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<th>
							<label>
								<?php _e( 'Link Image URL', $this->plugin_slug ) ?>&nbsp;&nbsp
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'If you would like to show links as images, enter the URL of an image for each link below. If you leave this field blank, links will be shown as text.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
						for ($i = 1; $i <= 5; $i++) { ?>
							<tr id="<?php echo 'link' . $i; ?>">
							  <td><?php $this -> create_link_text_field( array( 'id' => 'book_review_link_text' . $i, 'value' => $i, 'label_for' => 'book_review_link_text' . $i ) ); ?></td>
							  <td><?php $this -> create_link_image_field( array( 'id' => 'book_review_link_image' . $i, 'value' => $i, 'label_for' => 'book_review_link_image' . $i ) ); ?></td>
							</tr>
					<?php } ?>
				</tbody>
			</table>
			<h3><?php _e( 'Advanced', $this->plugin_slug ) ?></h3>
			<p><?php _e( 'This plugin uses the Google Books API to automatically populate the details of a book.
				In order to take advantage of this feature, you must first sign up for and enter an API key as described <a href="http://donnapeplinskie.com/wordpress-book-review-plugin/" target="_blank">here</a>.',
				$this->plugin_slug ) ?></p>
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="book_review_api_key">
								<?php _e( 'Google API Key', $this->plugin_slug ) ?>:
								<a href="#" class="tooltip">
									<?php echo $tooltip ?>
									<span><?php _e( 'Your Google API key obtained from the Google Developers Console.', $this->plugin_slug ) ?></span>
								</a>
							</label>
						</th>
						<td>
							<input id="book_review_api_key" class="text-input" type="text" name="book_review_advanced[book_review_api_key]" value="<?php echo $advanced['book_review_api_key']; ?>">
						</td>
					</tr>
				</tbody>
			</table>

			<script type="text/javascript">
				showRatingImages();
				showLinks();
			</script>

			<?php @submit_button(); ?>
		</form>
	</div>
</div>