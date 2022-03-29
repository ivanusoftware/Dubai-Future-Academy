<?php

namespace DFF\Integrations\Programs;

class Menu {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
	}

	public function register_menu() {
		add_submenu_page(
			'options-general.php',
			'Sync Programmes',
			'Sync Programmes',
			'manage_options',
			'sync-programs',
			[ $this, 'render_page' ]
		);
	}

	public function render_page() {
		require_once __DIR__ . '/views/admin.php';
	}
}

new Menu();
