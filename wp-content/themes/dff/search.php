<?php
	get_header();

	$search_post_types = [
		'post'       => [ 'name' => __( 'Insights', 'dff' ) ],
		'page'       => [ 'name' => __( 'Pages', 'dff' ) ],
		'dffmain-events' => [ 'name' => __( 'Events', 'dff' ) ],
		'program'    => [ 'name' => __( 'Programmes', 'dff' ) ],
	];

	$terms = array_map(
		function ( $item ) {
			return [
				'id'       => $item->term_id,
				'name'     => $item->name,
				'slug'     => $item->slug,
				'taxonomy' => $item->taxonomy,
			];
		},
		get_terms( [
			'taxonomy'   => 'category',
			'hide_empty' => true,
		] )
	);

	$parameters = [
		'postType'         => 'post,page,dffmain-events,program,future-talk',
		'perPage'          => 6,
		/* translators: Search results for "$query_text" */
		'title'            => sprintf( __( 'Showing results for "%s"', 'dff' ), get_search_query() ),
		'showSortBy'       => true,
		'showFilters'      => true,
		'endpoint'         => rest_url( 'dff/v1/post-feed' ),
		'orderby'          => 'relevance',
		'order'            => 'desc',
		'terms'            => $terms,
		'showCardDate'     => false,
		'imageSize'        => 'post-feed-large',
		'showCardTaxonomy' => true,
		's'                => get_search_query(),
		'sortby'           => [
			'relevance-desc' => __( 'Relevance', 'dff' ),
		],
		'isSearchPage'     => true,
		'postTypes'        => $search_post_types,
	];

	$items = [];

	while ( have_posts() ) {
		the_post();
		$category = dff_get_category( get_the_ID() );

		$meta_hook_name = sprintf( 'post_type_archive_get_%s_meta', get_post_type() );

		$items[] = [
			'id'        => get_the_ID(),
			'title'     => get_the_title(),
			'excerpt'   => dff_get_excerpt( 250 ),
			'image'     => dff_featured_image( get_the_ID(), $parameters['imageSize'] ),
			'image@2x'  => dff_featured_image( get_the_ID(), $parameters['imageSize'] . '@2x' ),
			'term'      => $category ? [
				'name' => $category->name,
				'link' => get_category_link( $category->term_id ),
			] : false,
			'permalink' => get_the_permalink(),
			'meta'      => apply_filters( $meta_hook_name, get_the_time( 'F j, Y' ), get_the_ID() ),
			'type'      => get_post_type(),
		];
	}
	?>

<section class="wp-block-dff-section section section-searchResults">
	<div class="container">
		<div class="archivePosts" data-posts-archive>
			<input type="hidden" value="<?php echo esc_attr( wp_json_encode( $parameters ) ); ?>">
			<div class="archivePosts-inner archivePosts-inner--php">
			<div class="searchFilters">
				<header class="searchFilters-header">
				<h1><?php _e( 'Filter search results', 'dff' ); ?></h1>
				</header>
				<nav class="archivePosts-filters">
				<ul>
					<?php
					foreach ( $search_post_types as $key => $value ) {
						printf(
							'<li><button value="%s">%s</button></li>',
							esc_attr( $key ),
							esc_html( $value['name'] ),
						);
					}
					?>
				</ul>
				</nav>
			</div>
			<div class="archivePosts-content--php">
				<header class="archivePosts-header">
						<h1><?php echo esc_html( $parameters['title'] ); ?></h1>

						<div className="archivePosts-sortBy">
							<label><?php _e( 'Sort By', 'dff' ); ?></label>
							<select>
								<option value="relevance-desc"><?php _e( 'Relevance', 'dff' ); ?></option>
								<option value="date-asc"><?php _e( 'Date (OLDEST)', 'dff' ); ?></option>
								<option value="date-desc"><?php _e( 'Date (NEWEST)', 'dff' ); ?></option>
							</select>
						</div>
				</header>
				<ul class="archivePosts-list">
					<li class="searchLoading"><?php _e( 'Loading...', 'dff' ); ?></li>
				<?php foreach ( $items as $item ) : ?>
					<li>
						<article class="archivePosts-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
							<a href="<?php echo esc_url( $item['permalink'] ); ?>">
								<div class="archivePosts-content">
									<header>
										<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="archivePosts-title"><?php echo esc_html( $item['title'] ); ?></h1>
										<?php if ( ! empty( $item['term'] ) && $parameters['showCardTaxonomy'] ) : ?>
											<span class="archivePosts-tag"><?php echo esc_html( $item['term']['name'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $item['meta'] ) && $parameters['showCardDate'] ) : ?>
											<span class="archivePosts-meta"><?php echo esc_html( $item['meta'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $item['excerpt'] ) ) : ?>
											<p><?php echo esc_html( $item['excerpt'] ); ?></p>
										<?php endif; ?>
									</header>
								</div>
							</a>
						</article>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	</div>
</section>


<?php

get_footer();
