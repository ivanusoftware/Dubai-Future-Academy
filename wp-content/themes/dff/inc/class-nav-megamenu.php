<?php

class Nav_MegaMenu {
	public function __construct() {

		// Add new fields via hook.
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'render_custom_fields' ), 10, 4 );

		// Save the menu item meta.
		add_action( 'wp_update_nav_menu_item', array( $this, 'save_custom_fields' ), 10, 2 );

		// Add meta to menu item.
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'attach_custom_fields' ) );
	}

	public function attach_custom_fields( $menu_item ) {
		$menu_item->subtitle   = get_post_meta( $menu_item->ID, 'dff_primary_menu_title', true );
		$menu_item->subcontent = get_post_meta( $menu_item->ID, 'dff_primary_menu_content', true );

		return $menu_item;
	}

	public function render_custom_fields( int $item_id, WP_Post $item, int $depth, $args ): void {
		if ( $depth > 0 ) {
			return;
		}

		$title   = get_post_meta( $item_id, 'dff_primary_menu_title', true );
		$content = get_post_meta( $item_id, 'dff_primary_menu_content', true );

		?>
			<hr>
			<input
				type="hidden"
				name="nav-menu-megamenu-nonce"
				value="<?php echo esc_attr( wp_create_nonce( 'nav-menu-megamenu-nonce' ) ); ?>"
			/>
			<p class="description description-wide">
			<label for="edit-menu-item-megamenu-title-<?php echo esc_attr( $item_id ); ?>">
				<?php _e( 'Submenu Title', 'dff' ); ?><br>
				<input
					type="text"
					id="edit-menu-item-megamenu-title-<?php echo esc_attr( $item_id ); ?>"
					class="widefat edit-menu-item-megamenu-title"
					name="menu-item-megamenu-title[<?php echo esc_attr( $item_id ); ?>]"
					value="<?php echo esc_attr( $title ); ?>"
				>
			</label>
			</p>
			<p class="description description-wide">
			<label for="edit-menu-item-megamenu-content-<?php echo esc_attr( $item_id ); ?>">
				<?php _e( 'Submenu content', 'dff' ); ?><br>
				<textarea
					id="edit-menu-item-megamenu-content-<?php echo esc_attr( $item_id ); ?>"
					class="widefat edit-menu-item-megamenu-content"
					name="menu-item-megamenu-content[<?php echo esc_attr( $item_id ); ?>]"
				><?php echo esc_html( $content ); ?></textarea>
			</label>
			</p>
		<?php
	}

	public function save_custom_fields( int $menu_id, int $menu_item_db_id ): void {
		if ( ! isset( $_POST['nav-menu-megamenu-nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['nav-menu-megamenu-nonce'] ) ), 'nav-menu-megamenu-nonce' ) ) {
			return;
		}

		if ( isset( $_POST['menu-item-megamenu-title'][ $menu_item_db_id ] ) ) {
			$sanitized_data = sanitize_text_field( $_POST['menu-item-megamenu-title'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, 'dff_primary_menu_title', $sanitized_data );
		} else {
			delete_post_meta( $menu_item_db_id, 'dff_primary_menu_title' );
		}

		if ( isset( $_POST['menu-item-megamenu-content'][ $menu_item_db_id ] ) ) {
			$sanitized_data = sanitize_text_field( $_POST['menu-item-megamenu-content'][ $menu_item_db_id ] );
			update_post_meta( $menu_item_db_id, 'dff_primary_menu_content', $sanitized_data );
		} else {
			delete_post_meta( $menu_item_db_id, 'dff_primary_menu_content' );
		}
	}
}

new Nav_MegaMenu();
