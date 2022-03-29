<?php

namespace DFF\Gutenberg\Blocks;

use \DFF\Rest\Rest_Post_Feed;
use WP_Query;

class Post_Type_Archive {
	public function __construct() {
		register_block_type(
			'dff/post-type-archive',
			[
				'render_callback' => [ $this, 'render' ],
				'editor_script'   => 'dff-gutenberg-scripts',
			]
		);
	}

	public function get_items_query( array $attributes ): WP_Query {
		$post_type  = $attributes['postType'] ?? 'post';
		$parameters = $attributes['filters'] ?? [];
		$per_page   = $attributes['perPage'] ?? 4;
		$terms      = ( $attributes['taxonomies'] ?? [] ) ?: [];

		$terms = array_filter( $terms, function ( $value ) {
			return count( $value ) > 0;
		}, ARRAY_FILTER_USE_BOTH );

		$parameters['relation'] = 'AND';

		return Rest_Post_Feed::query( [ $post_type ], $terms, $per_page, $parameters );
	}

	public function get_items( WP_Query $query, array $attributes ): array {
		if ( ! $query->have_posts() ) {
			wp_reset_postdata();
			return [
				'posts' => [],
				'pages' => 1,
			];
		}

		$posts = [];

		while ( $query->have_posts() ) {
			$query->the_post();
			$category = dff_get_category( get_the_ID() );

			// $permalink_hook_name = sprintf( 'post_type_archive_get_%s_permalink', get_post_type() ); TODO - no need??
			$permalink = get_the_permalink();
			$meta_hook_name      = sprintf( 'post_type_archive_get_%s_meta', get_post_type() );

			$posts[] = [
				'id'        => get_the_ID(),
				'title'     => get_the_title(),
				'excerpt'   => dff_get_excerpt( 250 ),
				'image'     => dff_featured_image( get_the_ID(), $attributes['imageSize'] ),
				'image@2x'  => dff_featured_image( get_the_ID(), $attributes['imageSize'] . '@2x' ),
				'term'      => $category ? [
					'name' => $category->name,
					'link' => get_category_link( $category->term_id ),
				] : false,
				'permalink' => $permalink,
				'meta'      => apply_filters( $meta_hook_name, get_the_time( 'F j, Y' ), get_the_ID() ),
				'type'      => get_post_type(),
			];
		}

		wp_reset_postdata();

		return [
			'posts' => $posts,
			'pages' => $query->max_num_pages,
		];
	}

	public function render_item( array $item, array $parameters ): void {

		$has_apply_button = 'program' === $item['type'] && ! empty( $parameters['filters']['showApplyNow'] ) && $parameters['filters']['showApplyNow'];

		/** $item['permalink'] - Permalink has filter in theme - themes/dff/inc/filter-functions.php */
		// if ( 'dffmain-events' == get_post_type( $item['id'] ) ) {
		// 	$item['permalink'] = $item['permalink'] . get_post_field( 'post_name', $item['id'], 'display' );
		// }
		?>
		<li>
			<article class="archivePosts-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
				<figure>
					
					<a href="<?php echo esc_url( $item['permalink'] ); ?>">
						<img
							src="<?php echo esc_url( $item['image'] ); ?>"
							<?php
								! empty( $item['image@2x'] ) && $item['image@2x'] !== $item['image'] && printf( ' srcset="%s 2x"', esc_url( $item['image@2x'] ) );
							?>
							alt=""
						>
						</a>
					</figure>
					<div class="archivePosts-content">
						<header>
							<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="archivePosts-title">
								<a href="<?php echo esc_url( $item['permalink'] ); ?>">
									<?php echo esc_html( $item['title'] ); ?>
								</a>
							</h1>
							<?php if ( ! empty( $item['term'] ) && $parameters['showCardTaxonomy'] ) : ?>
								<span class="archivePosts-tag"><?php echo esc_html( $item['term']['name'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $item['meta'] ) && $parameters['showCardDate'] ) : ?>
								<span class="archivePosts-meta<?php $has_apply_button && print ' is-link-style'; ?>">
									<?php echo wp_kses_post( $item['meta'] ); ?>
									<?php if ( $has_apply_button ) : ?>
										| <a href="<?php echo esc_url( $item['permalink'] ); ?>"><?php _e( 'Apply Now', 'dff' ); ?> &raquo;</a>
									<?php endif; ?>

								</span>
							<?php endif; ?>
						</header>
					</div>
			</article>
		</li>
		<?php
	}

	public function get_all_terms( string $post_type, $query ): array {
		$taxonomy = apply_filters( "dff_get_{$post_type}_archive_filter_taxonomy", 'category' );

		return array_map(
			function ( $item ) {
				return [
					'id'       => $item->term_id,
					'name'     => $item->name,
					'slug'     => $item->slug,
					'taxonomy' => $item->taxonomy,
				];
			},
			get_terms( [
				'taxonomy'   => $taxonomy,
				'hide_empty' => true,
				'object_ids' => wp_list_pluck( $query->posts, 'ID' ),
			] )
		);
	}

	public function get_filter_terms( string $post_type, $query ): array {
		return array_map(
			function ( $item ) {
				return [
					'term_id'  => $item->term_id,
					'name'     => $item->name,
					'slug'     => $item->slug,
					'taxonomy' => $item->taxonomy,
				];
			},
			get_terms( [
				'taxonomy'   => dff_get_posttype_category_name( $post_type ),
				'hide_empty' => true,
				'object_ids' => wp_list_pluck( $query->posts, 'ID' ),
			] )
		);
	}

	public function get_parameters( array $attributes ): array {
		$post_type = $attributes['postType'] ?? 'post';

		return array_merge( $attributes, [
			'postType'         => $post_type,
			'perPage'          => $attributes['perPage'] ?? 4,
			'endpoint'         => rest_url( 'dff/v1/post-feed' ),
			'orderby'          => 'date',
			'order'            => 'dffmain-events' !== $post_type || ( isset( $attributes['filters']['showPast'] ) && 1 === (int) $attributes['filters']['showPast'] ) ? 'desc' : 'asc',
			'terms'            => [],
			'showCardTaxonomy' => $attributes['showCardTaxonomy'] ?? true,
			'showCardDate'     => $attributes['showCardDate'] ?? true,
			'imageSize'        => apply_filters( "dff_get_{$post_type}_archive_image_size", 'post-feed-large' ),
		] );
	}

	public function render( array $attributes ): string {
		$parameters = $this->get_parameters( $attributes );
		$query      = $this->get_items_query( $parameters );
		$posts      = $this->get_items( $query, $parameters );
		$terms      = $this->get_all_terms( $parameters['postType'], $query );

		$parameters['terms'] = $terms;

		if ( isset( $parameters['showFilters'] ) && $parameters['showFilters'] ) {
			// phpcs:ignore
			$active_term = ! empty( $_GET['category'] ) ? $_GET['category'] : false;

			$active_term = array_find( $terms, function ( $term ) use ( $active_term ) {
				return $term['slug'] === $active_term;
			}, (object) [] );

			// phpcs:ignore
			$active_filter = ! empty( $_GET['filter'] ) ? $_GET['filter'] : false;

			$filter_terms = $this->get_filter_terms( $parameters['postType'], $query );

			$active_filter = array_find( $filter_terms, function ( $term ) use ( $active_filter ) {
				return $term['slug'] === $active_filter;
			}, (object) [] );

			$parameters['activeTerm']  = $active_term;
			$parameters['termFilter']  = $active_filter;
			$parameters['filterTerms'] = $filter_terms;
		}

		$items = $posts['posts'];

		if ( count( $items ) === 0 ) {
			$no_posts_text = $parameters['noPostsText'] ?? '';

			if ( ! $no_posts_text ) {
				$post_type        = get_post_type_object( $parameters['postType'] );
				$post_type_labels = get_post_type_labels( $post_type );
				// translators: e.g No Posts found, where %s = Posts.
				$no_posts_text = sprintf( __( 'No %s Found', 'dff' ), $post_type_labels->name );
			}

			ob_start();

			?>
			<div class="archivePosts">
				<div class="archivePosts-inner">
					<header class="archivePosts-header">
						<h1><?php echo esc_html( $no_posts_text ); ?></h1>
					</header>
				</div>
			</div>

			<?php

			return ob_get_clean();
		}

		$should_ajax_load = array_reduce( array_keys( $parameters ), function ( $carry, $key ) use ( $parameters ) {
			if ( $carry ) {
				return $carry;
			}

			return in_array( $key, [ 'showFilters', 'showSortBy', 'showPagination' ] ) && $parameters[ $key ];
		}, false );

		$terms = $parameters['terms'];

		ob_start();

		?>
		<div class="archivePosts" <?php $should_ajax_load && print 'data-posts-archive'; ?>>
		<?php if ( $should_ajax_load ) : ?>
			<input type="hidden" value="<?php echo esc_attr( wp_json_encode( $parameters ) ); ?>">
		<?php endif; ?>
			<div class="archivePosts-inner">
				<header class="archivePosts-header">
					<?php if ( ! empty( $parameters['title'] ) ) : ?>
						<h1><?php echo esc_html( $parameters['title'] ); ?></h1>
						<?php if ( ! empty( $parameters['showSortBy'] ) ) : ?>
							<div className="archivePosts-sortBy">
								<label>Sort By</label>
								<select>
									<option value="date-asc">Date (OLDEST)</option>
									<option value="date-desc">Date (NEWEST)</option>
									<!-- <option value="title-asc">Title (ASC)</option> -->
									<!-- <option value="title-desc">Title (DESC)</option> -->
								</select>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $parameters['showViewAllButton'] ) && ! empty( $parameters['viewAllHref'] ) ) : ?>
							<a class="button button--ghost" href="<?php echo esc_url( $parameters['viewAllHref'] ); ?>"><?php _e( 'View All', 'dff' ); ?></a>
						<?php endif; ?>
					<?php endif; ?>
				</header>
				<?php if ( ! empty( $parameters['showFilters'] ) ) : ?>
					<nav class="archivePosts-filters">
						<ul>
							<?php foreach ( $terms as $term ) : ?>
								<li><button><?php echo esc_html( $term['name'] ); ?></button></li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>
				<ul class="archivePosts-list">
			<?php

			foreach ( $items as $item ) {
				$this->render_item( $item, $parameters );
			}

			?>
				</ul>
			</div>
		</div>

		<?php

		return ob_get_clean();
	}
}

new Post_Type_Archive();
