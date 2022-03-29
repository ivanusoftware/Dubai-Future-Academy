<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wpforms-builder-provider-connection" data-connection_id="{{ data.connection.id }}">
	<input type="hidden" class="wpforms-builder-provider-connection-id"
			name="providers[{{ data.provider }}][{{ data.connection.id }}][id]"
			value="{{ data.connection.id }}">

	<div class="wpforms-builder-provider-connection-title">
		{{ data.connection.name }}
		<button class="wpforms-builder-provider-connection-delete js-wpforms-builder-provider-connection-delete" type="button">
			<i class="fa fa-times-circle"></i>
		</button>
		<input type="hidden"
				id="wpforms-builder-salesforce-provider-{{ data.connection.id }}-name"
				name="providers[{{ data.provider }}][{{ data.connection.id }}][name]"
				value="{{ data.connection.name }}">
	</div>

	<div class="wpforms-builder-provider-connection-block wpforms-builder-salesforce-provider-accounts">
		<h4><?php esc_html_e( 'Select Account', 'wpforms-salesforce' ); ?><span class="required">*</span></h4>

		<select class="js-wpforms-builder-salesforce-provider-connection-account wpforms-required" name="providers[{{ data.provider }}][{{ data.connection.id }}][account_id]"<# if ( _.isEmpty( data.accounts ) ) { #> disabled<# } #>>
			<option value="" selected disabled><?php esc_html_e( '--- Select ---', 'wpforms-salesforce' ); ?></option>

			<# _.each( data.accounts, function( account, account_id ) { #>
				<option value="{{ account_id }}" data-option_id="{{ account['option_id'] }}"<# if ( account_id === data.connection.account_id ) { #> selected<# } #>>
					{{ account['email'] }}
				</option>
			<# } ); #>
		</select>
	</div>

	<div class="wpforms-builder-provider-connection-block wpforms-builder-salesforce-provider-objects">
		<h4><?php esc_html_e( 'Select Salesforce Object', 'wpforms-salesforce' ); ?><span class="required">*</span></h4>

		<select class="js-wpforms-builder-salesforce-provider-connection-object wpforms-required" name="providers[{{ data.provider }}][{{ data.connection.id }}][object]"<# if ( _.isEmpty( data.connection.account_id ) ) { #> disabled<# } #>>
			<option value="" selected disabled><?php esc_html_e( '--- Select ---', 'wpforms-salesforce' ); ?></option>

			<# _.each( data.objects, function( label, name ) { #>
				<option value="{{ name }}"<# if ( name === data.connection.object ) { #> selected<# } #>>
					{{ label }}
				</option>
			<# } ); #>
		</select>
	</div>

	<!-- Here is where sub-templates will put its compiled HTML. -->
	<div class="wpforms-builder-salesforce-provider-actions-data"></div>

	<# if ( ! _.isEmpty( data.accounts ) ) { #>
		{{{ data.conditional }}}
	<# } #>
</div>
