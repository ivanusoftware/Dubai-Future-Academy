<?php

/**
 * Registers primary nav.
 *
 */
if ( ! function_exists( 'register_primary_nav' ) ) {
	function register_primary_nav() {
		register_nav_menu( 'primary', __( 'Primary Nav', 'dff' ) );
		register_nav_menu( 'future-id', __( 'Future ID', 'dff' ) );
		register_nav_menu( 'footer-1', __( 'Footer #1', 'dff' ) );
		register_nav_menu( 'footer-2', __( 'Footer #2', 'dff' ) );
		register_nav_menu( 'footer-3', __( 'Footer #3', 'dff' ) );
		register_nav_menu( 'footer-bottom', __( 'Footer Bottom', 'dff' ) );
		register_nav_menu( 'footer-bottom-2', __( 'Footer Bottom #2', 'dff' ) );
	}
}

add_action( 'after_setup_theme', 'register_primary_nav' );


if ( ! function_exists( 'dff_get_menu' ) ) {
	function dff_get_menu( $location = false ) {
		if ( ! $location ) {
			return false;
		}

		$locations = get_nav_menu_locations();

		if ( empty( $locations[ $location ] ) ) {
			return false;
		}

		$menu = get_term( $locations[ $location ], 'nav_menu' );
		return $menu;
	}
}

if ( ! function_exists( 'dff_get_breadcrumbs' ) ) {
	function dff_get_breadcrumbs( $post_id = null ) {
		$crumbs = [];
		$object = get_queried_object();

		if ( is_front_page() ) {
			return $crumbs;
		}

		if ( is_single() || is_page() ) {
			$crumbs[] = get_the_title();

			$post_type = get_post_type( $post_id );

			if ( ! is_post_type_hierarchical( $post_type ) ) {
				$post_type_object = get_post_type_object( $post_type );
				$crumbs[]         = sprintf(
					'<a href="%s">%s</a>',
					get_post_type_archive_link( $post_type ),
					$post_type_object->labels->name
				);
			} else {
				$parent = $post_id;
				while ( $parent = wp_get_post_parent_id( $parent ) ) { // phpcs:ignore
					$crumbs[] = sprintf(
						'<a href="%s">%s</a>',
						get_the_permalink( $parent ),
						get_the_title( $parent )
					);
				}
			}
		}

		if ( is_tax() ) {
			$crumbs[] = $object->name;
		}

		$crumbs[] = sprintf(
			'<a href="%s">%s <span class="u-hiddenVisually">%s</span></a>',
			home_url( '/' ),
			dff_asset( 'img/ico-home.svg', false ),
			esc_html( __( 'Homepage', '' ) )
		);

		return array_reverse( $crumbs );

	}
}

if ( ! function_exists( 'dff_breadcrumbs' ) ) {
	function dff_breadcrumbs( $post_id = null ) {
		$crumbs = dff_get_breadcrumbs( $post_id );

		if ( count( $crumbs ) < 1 ) {
			return;
		}

		?>
		<ul class="breadcrumbs" aria-label="<?php _e( 'Site breadcrumbs', 'dff' ); ?>">
			<?php foreach ( $crumbs as $crumb ) : ?>
				<li class="breadcrumb">
					<?php echo $crumb; // phpcs:ignore ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}

function dff_breadcrumbs_shortcode() {
	ob_start();
	dff_breadcrumbs();
	return ob_get_clean();
}

add_shortcode( 'breadcrumbs', 'dff_breadcrumbs_shortcode' );
