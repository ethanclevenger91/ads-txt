<?php

namespace AdsTxt;

/**
 * Add admin menu page
 * @return void
 */
function admin_menu() {
	add_options_page( __( 'Ads.txt', 'adstxt' ), __( 'Ads.txt', 'adstxt' ), 'manage_options', 'adstxt-settings', __NAMESPACE__ . '\settings_screen' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\admin_menu' );

/**
 * Output the settings screen
 * @return void
 */
function settings_screen() {
	$post_id = get_option( 'adstxt_post' );

	if ( $post_id ) {
		$post = get_post( $post_id );
	}

	$content = isset( $post->post_content ) ? $post->post_content : '';

	$errors = get_post_meta( $post->ID, 'adstxt_errors', true );

// Also need to display errors based on meta key
// It's okay if they display again if they leave and come back, I think
?>

<div class="wrap">
<?php if ( ! empty( $errors ) ) : ?>
	<div class="notice notice-error adstxt-errors">
		<p><strong>Your Ads.txt contains the following issues:</strong></p>
		<ul>
			<?php foreach( $errors as $error ) {
				echo '<li class="' . $error['type'] . '">';

				/* translators: Error message output. 1: Line number, 2: Error message */
				printf(
					__( 'Line %1$s: %2$s', 'adstxt' ),
					$error['line'],
					$error['message']
				);

				echo '</li>';
			} ?>
		</ul>
	</div>
<?php endif; ?>

	<h2><?php _e( 'Ads.txt', 'adstxt' ); ?></h2>

	<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" class="adstxt-settings-form">
		<input type="hidden" name="post_id" value="<?php echo esc_attr( $post->ID ); ?>" />
		<input type="hidden" name="action" value="adstxt-save" />
		<?php wp_nonce_field( 'adstxt_save' ); ?>

		<textarea class="widefat code" rows="25" name="adstxt"><?php echo esc_textarea( $content ); ?></textarea>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>">
		</p>
	</form>
</div>

<?php
}