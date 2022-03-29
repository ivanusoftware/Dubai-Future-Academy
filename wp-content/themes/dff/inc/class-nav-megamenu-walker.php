<?php

class Nav_MegaMenu_Walker extends Walker_Nav_Menu {
	private function check_for_mega_menu_level_2( array $elements, array $children_elements ) {
		foreach ( $elements as $sub_menu ) {
			if ( ! empty ( $children_elements[ $sub_menu->ID ] ) ) {
				return true;
			};
			return false;
		}
	}
	public function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		if ( $depth > 0 ) {
			return;
		}

		if ( ! empty( $children_elements[ $element->ID ] ) ) {
			$element->classes[] = 'has-children';
		}


		$this->start_el( $output, $element, 0, ...array_values( $args ) );

		if ( ! empty( $children_elements[ $element->ID ] ) ) {
			$is_level_three = $this->check_for_mega_menu_level_2( $children_elements[ $element->ID ], $children_elements );
			ob_start();
			?>
				<button data-toggle-children aria-label="Open sub menu">
					<?php dff_asset( 'img/ico-chevron-down.svg' ); ?>
				</button>
				<div class="megamenu<?php echo ( $is_level_three ? ' megamenu--columns' : '' ); ?>">
					<?php if ( $is_level_three ) : ?>
						<!-- 2 level menu -->
						<div class="container container-columns container-columns--<?php echo ( count ( $children_elements[ $element->ID ] ) > 5 ? '6' : count ( $children_elements[ $element->ID ] ) ); ?>">
					<?php endif; ?>

					<?php if ( ! $is_level_three ) : ?>
						<!-- 1 level menu -->
						<div class="container">
						<div class="megamenu-intro">
							<h1><?php echo esc_html( $element->subtitle ?: $element->title ); ?></h1>
							<?php if ( $element->subcontent ) : ?>
								<p><?php echo esc_html( $element->subcontent ); ?></p>
							<?php endif; ?>
						</div>
						<div class="megamenu-links">
					<?php endif; ?>
							<ul>
			<?php
			$output .= ob_get_clean();

			foreach ( $children_elements[ $element->ID ] as $child ) {
				$this->start_el( $output, $child, 1, ...array_values( $args ) );
				if ( ! empty( $children_elements[ $child->ID ] ) || $is_level_three ) {
					ob_start();
					?>
					<button data-toggle-children aria-label="Open sub menu">
						<?php dff_asset( 'img/ico-chevron-down.svg' ); ?>
					</button>
					<?php
					printf(
						'<ul class="subSubMenu" aria-label="%s">',
						esc_attr( sprintf(
							// translators: Subnavigation for "menu item title"
							esc_attr( __( 'Subnavigation for %s', 'dff' ) ),
							esc_attr( $child->title )
						) )
					);
					$output .= ob_get_clean();

					foreach ( $children_elements[ $child->ID ] as $sub_item ) {
						$this->start_el( $output, $sub_item, 2, ...array_values( $args ) );
						$this->end_el( $output, $sub_item, 2, ...array_values( $args ) );
						$children_elements[ $sub_item->ID ] = [];
					}

					ob_start();
					echo '</ul>';
					$output .= ob_get_clean();
				}

				if ( ! $is_level_three ) {
					ob_start();
					dff_asset( 'img/arrow-right.svg' );
					$output .= ob_get_clean();
				}

				$children_elements[ $child->ID ] = [];
				$this->end_el( $output, $child, 1, ...array_values( $args ) );
			}

			ob_start();

			?>
							</ul>
						<?php if ( ! $is_level_three ) : ?>
						<?php endif; ?>
					</div>

					<?php dff_asset( 'img/megamenu-triangles.svg' ); ?>
				</div>
			<?php
			$output .= ob_get_clean();

			$this->end_el( $output, $element, 0, ...array_values( $args ) );
			$children_elements[ $element->ID ] = [];
		}
	}
}
