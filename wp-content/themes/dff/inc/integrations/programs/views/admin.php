<div class="wrap">
	<h1><?php _e( 'Synchronise Programmes', 'dff' ); ?></h1>
	<p><?php _e( 'The website synchronises programmes every six hours without user interaction.', 'dff' ); ?></p>
	<button id="sync-programs" class="button button-primary">Synchronise</button>
	<p id="message" style="display: none;"></p>
</div>

<script>
	const button = document.querySelector('#sync-programs');
	const message = document.querySelector('#message');
	button.addEventListener('click', (event) => {
		event.preventDefault();
		button.disabled = true;
		message.style.display = 'block';
		message.innerText = 'Loading...';

		fetch( '<?php echo esc_js( get_rest_url() ); ?>dff/v1/programs/sync', {
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>',
			},
		} )
			.then((response) => response.json())
			.then((response) => {
				message.innerText = response.message;
				button.disabled = false;
			})
			.catch((error) => {
				message.innerText = '<?php _e( 'Something went wrong whilst trying to synchronise programmes, hit synchronise to try again', 'dff' ); ?>';
				button.disabled = false;
			})
	})
</script>
