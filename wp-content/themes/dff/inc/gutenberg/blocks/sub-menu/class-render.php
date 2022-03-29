<?php

namespace DFF\Gutenberg\Blocks\SubMenu;

class Sub_Menu {

	// register block type on construct
	public function __construct() {
		register_block_type( 'dff/sub-menu', [
			'render_callback' => [ $this, 'render' ],
			'editor_script'   => 'dff-gutenberg-scripts',
		] );
	}

	// amend parameters
	private function new_sort_order_values( string $order_by ) {
		switch ( $order_by ) {
			case 'title':
				return 'post_title';

			case 'date':
				return 'post_date';

			case 'modified':
				return 'post_modified';

			case 'id':
			case 'menu_order':
			default:
				return $order_by;
		}
	}

	// display pages
	public function render( array $attributes ) {

		$page_id         = $attributes['pageId'] ?? '';
		$order_by        = $attributes['orderBy'] ?? 'menu_order';
		$order           = $attributes['order'] ?? 'asc';
		$current_page_id = get_the_id();

		// check if page_id is set
		if ( ! empty( $page_id ) ) {

			$options = array(
				'parent'      => $page_id,
				'sort_order'  => $order,
				'sort_column' => $this->new_sort_order_values( $order_by ),
				'post_status' => [ 'publish', 'private' ],
			);

			$sub_pages = get_pages( $options );

			// check for sub pages
			if ( $sub_pages ) {

				ob_start();
				?>
				<nav class="subMenu">
					<ul class="subMenu-ul">
						<?php
						printf(
							'<li><a class="%s" href="%s" title="%s">%s</a></li>',
							( intval( $page_id, 10 ) === $current_page_id ? 'active' : '' ),
							esc_url( get_permalink( $page_id ) ),
							esc_attr( __( 'Overview', 'dff' ) ),
							esc_html( __( 'Overview', 'dff' ) )
						);

						foreach ( $sub_pages as $sub_page ) {
							printf(
								'<li><a class="%s" href="%s" title="%s">%s</a></li>',
								( $current_page_id === $sub_page->ID ? 'active' : '' ),
								esc_url( dff_get_permalink( $sub_page->ID ) ),
								esc_attr( $sub_page->post_title ),
								esc_html( $sub_page->post_title )
							);
						}
						?>
					</ul>
					</nav>
				<?php
				return ob_get_clean();
			}
		}

	}

}

new Sub_Menu();
