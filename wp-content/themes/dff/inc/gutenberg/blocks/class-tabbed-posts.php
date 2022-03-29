<?php

use \DFF\Rest\Rest_Post_Feed;

class Tabbed_Posts {
	public function __construct() {
		register_block_type(
			'dff/tabbed-posts',
			[
				'render_callback' => [ $this, 'render' ],
				'editor_script'   => 'dff-gutenberg-scripts',
			]
		);
	}

	private function get_post_type_archive_link_custom( string $post_type ) {
		switch ( $post_type ) {
			case 'program':
				return get_site_url() . '/programs/';

			case 'event':
				return get_site_url() . '/events/';

			case 'post':
				return get_site_url() . '/insights/';

			case 'future-talk':
				return get_site_url() . '/future-talk/';

			case 'page':
			default:
				return get_site_url();
		}
	}

	public function render( array $attributes ): ?string {
		$tabs = [];

		$uniq = uniqid();

		$is_tabbed_single  = ( count( $attributes['postTypes'] ?? [] ) <= 1 ? true : false );
		$mode              = $attributes['mode'] ?? 'feed';
		$is_only_cards     = $attributes['isOnlyCards'] ?? false;
		$is_meta           = $attributes['isMeta'] ?? true;
		$is_call_to_action = ( ! $is_meta && ( $attributes['isCallToAction'] ?? false ) ? true : false );

		// build extra classes for style and also custom data alignment
		if ( $is_tabbed_single ) {
			$class_extras = 'is-tabbedSingle';

			if ( $is_only_cards ) {
				$class_extras  .= ' is-sidebarDisabled';
				$item_alignment = $attributes['itemAlignment'] ?? 'left';
			}
		}

		// select mode
		if ( 'select' === $mode ) {

			$tabs['multi']['id']        = uniqid();
			$posts_to_select            = $attributes['posts'];
			$tabs['multi']['title']     = $attributes['selectedModeTitle'] ?? 'Selection';
			$tabs['multi']['permalink'] = $attributes['customURL'] ?? '#';
			$tabs['multi']['content']   = $attributes['postTypesContent']['text'] ?? false;

			$options = [
				'post__in' => $posts_to_select,
				'orderby'  => 'post__in',
			];

			// get posts
				$query = Rest_Post_Feed::query( [ 'page', 'post', 'program', 'event', 'future-talk' ], [], 9, $options );

			$posts = [];

			while ( $query->have_posts() ) {
				$query->the_post();

				$time      = get_the_time( 'F j, Y' );
				$post_type = get_post_type( get_the_ID() );

				$posts[] = [
					'id'        => get_the_ID(),
					'title'     => get_the_title(),
					'permalink' => get_the_permalink(),
					'image'     => dff_featured_image( get_the_ID(), 'post-card@2x' ),
					'meta'      => apply_filters( 'dff_post_card_' . $post_type . '_meta', $time, get_the_ID() ),
				];
			}

			wp_reset_postdata();

			$tabs['multi']['posts'] = $posts;
		}


		// feed mode
		if ( 'feed' === $mode ) {

			foreach ( $attributes['postTypes'] as $post_type ) {

				$tabs[ $post_type ]['id'] = uniqid();

				// get post type details
				$post_type_object                = get_post_type_object( $post_type );
				$tabs[ $post_type ]['title']     = $post_type_object->label;
				$tabs[ $post_type ]['permalink'] = $this->get_post_type_archive_link_custom( $post_type );
				$tabs[ $post_type ]['content']   = $attributes['postTypesContent'][ $post_type ]['text'] ?? false;

				// get extra details and filtering options
				$restrict_to_page_parent = $attributes['restrictToPageParent'] ?? false;
				$page_id                 = $attributes['pageId'] ?? '';

				// add default options
				$options = [
					'posts__not_in' => [ get_the_ID() ],
				];

				if ( true === $restrict_to_page_parent && ! empty ( $page_id ) && 'page' === $post_type ) {
					$order_by               = $attributes['orderBy'] ?? 'menu_order';
					$order                  = $attributes['order'] ?? 'asc';
					$options['orderby']     = $order_by;
					$options['order']       = $order;
					$options['post_parent'] = $page_id;

				}

				// get posts
				// $query = new WP_Query( $options );
				$query = Rest_Post_Feed::query( [ $post_type ], [], 9, $options );



				if ( ! $query->have_posts() ) {
					unset( $tabs[ $post_type ] );
					wp_reset_postdata();
					continue;
				}

				$posts = [];

				while ( $query->have_posts() ) {
					$query->the_post();

					$time = get_the_time( 'F j, Y' );

					$posts[] = [
						'id'        => get_the_ID(),
						'title'     => get_the_title(),
						'permalink' => get_the_permalink(),
						'image'     => dff_featured_image( get_the_ID(), 'post-card@2x' ),
						'meta'      => apply_filters( 'dff_post_card_' . $post_type . '_meta', $time, get_the_ID() ),
					];
				}

				wp_reset_postdata();

				$tabs[ $post_type ]['posts'] = $posts;
			}

			if ( empty( $tabs ) ) {
				return null;
			}
		}

		ob_start();

		?>

		<div class="tabbedPosts <?php echo ( $is_tabbed_single ) ? esc_attr( $class_extras ) : ''; ?>" data-tabbed-posts="<?php echo esc_attr( $uniq ); ?>" data-alignment="<?php echo ( $is_tabbed_single && $is_only_cards ) ? esc_attr( $item_alignment ) : 'left'; ?>">

			<div class="tabbedPosts-aside">
				<?php
				$i = 0;

				if ( count( $tabs ) > 1 ) :
					foreach ( $tabs as $tab ) :
						?>
						<button
							class="tabbedPost-action <?php 0 === $i && print ' is-selected'; ?>"
							data-tabbed-posts-show="<?php echo esc_attr( $tab['id'] ); ?>"
							data-tabbed-posts-collection="<?php echo esc_attr( $uniq ); ?>"
						>
							<?php
							if ( 'feed' === $mode && true === $restrict_to_page_parent && ! empty ( $page_id ) && 'page' === $post_type ) {
								echo esc_html( get_the_title( $page_id ) );
							} else {
								echo esc_html( $tab['title'] );
							}
							?>
						</button>
						<?php
						$i++;
					endforeach;
				else :
					$tab = array_values( $tabs )[0];

					?>
						<span
							class="tabbedPost-action is-selected"
							data-tabbed-posts-show="<?php echo esc_attr( $tab['id'] ); ?>"
							data-tabbed-posts-collection="<?php echo esc_attr( $uniq ); ?>"
						>
							<?php
							if ( 'feed' === $mode && true === $restrict_to_page_parent && ! empty ( $page_id ) && 'page' === $post_type ) {
								echo esc_html( get_the_title( $page_id ) );
							} else {
								echo esc_html( $tab['title'] );
							}
							?>
						</span>
					<?php
				endif;
				?>

				<div class="tabbedPosts-introContainer">
					<?php
					$i = 0;
					foreach ( $tabs as $tab ) :

						if ( ! $tab['content'] ) {
							$i++;
							continue;
						}
						?>
						<div
							class="tabbedPost-intro <?php 0 === $i && print ' is-selected'; ?>"
							data-tabbed-posts-id="<?php echo esc_attr( $tab['id'] ); ?>"
							data-tabbed-posts-collection="<?php echo esc_attr( $uniq ); ?>"
						>
							<p><?php echo esc_html( $tab['content'] ); ?></p>
							<a class="button button--ghost hide-mobile" href="
							<?php
							if ( 'feed' === $mode && true === $restrict_to_page_parent && ! empty ( $page_id ) && 'page' === $post_type ) {
								echo esc_url( get_permalink( $page_id ) );
							} else {
								echo esc_url( $tab['permalink'] );
							}
							?>
							"><?php _e( 'View All', 'dff' ); ?></a>
						</div>
						<?php
						$i++;
					endforeach;
					?>
				</div>

			</div>
			<div class="tabbedPosts-listContainer">
				<button class="slider-arrow prev" data-tabbed-posts-prev="<?php echo esc_attr( $uniq ); ?>">
					<span class="u-hiddenVisually"><?php _e( 'Previous Posts', 'dff' ); ?></span>
					<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
			<?php
			$i = 0;
			foreach ( $tabs as $tab ) :

				if ( empty( $tab['posts'] ) ) {
					$i++;
					continue;
				}
				?>

				<div
					class="tabbedPosts-list <?php 0 === $i && print ' is-selected'; ?>"
					data-tabbed-posts-id="<?php echo esc_attr( $tab['id'] ); ?>"
					data-tabbed-posts-collection="<?php echo esc_attr( $uniq ); ?>"
				>
				<?php
				foreach ( $tab['posts'] as $item ) :
					?>
					<div class="tabbedPost-listItem">
						<article aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
							<a href="<?php echo esc_url( $item['permalink'] ); ?>" class="tabbedPost-item" style="background-image: url(<?php echo esc_attr( $item['image'] ); ?>)" aria-label="<?php echo esc_attr( $item['title'] ); ?>"  title="<?php echo esc_attr( $item['title'] ); ?>">
							<div class="tabbedPost-content">
								<header>
									<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="tabbedPost-title"><?php echo esc_html( $item['title'] ); ?></h1>
								</header>
								<?php if ( $is_meta && $item['meta'] ) : ?>
									<span class="tabbedPost-tag"><?php echo esc_html( $item['meta'] ); ?></span>
								<?php endif; ?>

								<?php if ( $is_call_to_action ) : ?>
									<span class="tabbedPost-arrow"><?php dff_asset( 'img/arrow-right.svg' ); ?></span>
								<?php endif; ?>
								</div>
							</a>
						</article>
					</div>
					<?php endforeach; ?>
				</div>
				<?php
				$i++;
			endforeach;
			?>
				<button class="slider-arrow next" data-tabbed-posts-next="<?php echo esc_attr( $uniq ); ?>">
					<span class="u-hiddenVisually"><?php _e( 'Next Posts', 'dff' ); ?></span>
					<?php dff_asset( 'img/arrow.svg' ); ?>
				</button>
			</div>
			<div class="tabbedPost-buttonContainer">
				<?php
				$i = 0;
				foreach ( $tabs as $tab ) :

					if ( ! $tab['content'] ) {
						$i++;
						continue;
					}
					?>
						<a
							class="button button--ghost button--block show-mobile <?php 0 === $i && print 'is-selected'; ?>"
							data-tabbed-posts-id="<?php echo esc_attr( $tab['id'] ); ?>"
							data-tabbed-posts-collection="<?php echo esc_attr( $uniq ); ?>"
							href="
							<?php
							if ( 'feed' === $mode && true === $restrict_to_page_parent && ! empty ( $page_id ) && 'page' === $post_type ) {
								echo esc_html( get_permalink( $page_id ) );
							} else {
								echo esc_url( $tab['permalink'] );
							}
							?>
							"
						>
							<?php _e( 'View All', 'dff' ); ?>
						</a>
					<?php
					$i++;
				endforeach;
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}

new Tabbed_Posts();
