<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form action="<?php echo esc_url( $args['authorization_url'] ); ?>" id="wpforms-salesforce-new-account-connection-form" method="post">
	<input type="hidden" name="response_type" value="code">
	<input type="hidden" name="scope" value="<?php echo esc_attr( $args['scope'] ); ?>">
	<input type="hidden" name="prompt" value="<?php echo esc_attr( $args['prompt'] ); ?>">
	<input type="hidden" name="state" value="">
	<p class="wpforms-settings-provider-accounts-connect-fields">
		<input type="text" name="client_id" class="wpforms-required" placeholder="<?php esc_attr_e( 'Consumer Key', 'wpforms-salesforce' ); ?>">
		<input type="text" name="client_secret" class="wpforms-required" placeholder="<?php esc_attr_e( 'Consumer Secret', 'wpforms-salesforce' ); ?>">
	</p>
	<div class="wpforms-salesforce-setting-field-redirect">
		<label for="wpforms-salesforce-redirect-uri"><?php esc_html_e( 'Callback URL:', 'wpforms-salesforce' ); ?></label>
		<input type="url" name="redirect_uri" id="wpforms-salesforce-redirect-uri" class="wpforms-salesforce-setting-field-redirect-input" value="<?php echo esc_attr( $args['redirect_uri'] ); ?>" readonly>
		<button type="button" class="wpforms-salesforce-setting-field-redirect-copy" data-source_id="wpforms-salesforce-redirect-uri">
			<span class="dashicons dashicons-admin-page"></span>
			<span class="dashicons dashicons-yes"></span>
		</button>
	</div>
	<p class="description">
		<?php
		printf(
			wp_kses( /* translators: %s - URL to the Salesforce documentation page on WPForms.com. */
				__( 'Click <a href="%s" target="_blank" rel="noopener noreferrer">here</a> to learn how to connect WPForms with Salesforce.', 'wpforms-salesforce' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
				]
			),
			'https://wpforms.com/docs/how-to-install-and-use-the-salesforce-addon-with-wpforms'
		);
		?>
	</p>
	<p class="error hidden">
		<?php esc_html_e( 'Something went wrong while performing an AJAX request.', 'wpforms-salesforce' ); ?>
	</p>
</form>
