<?php

use DFF\Rest\REST_Post_Feed;

get_header();
global $wp_query;

// phpcs:ignore
$active_term = ! empty( $_GET['article-type'] ) ? $_GET['article-type'] : false;
// phpcs:ignore
$active_filter = ! empty( $_GET['filter'] ) ? $_GET['filter'] : false;

$q = REST_Post_Feed::query( [ 'post' ], [], -1, [ 'fields' => 'ids' ] );
// $the_query->posts is an array of all found post IDs

$terms        = [];
$filter_terms = [];

if ( $q->have_posts() ) {
	$terms        = wp_get_object_terms( $q->posts, 'article-type' );
	$filter_terms = wp_get_object_terms( $q->posts, dff_get_posttype_category_name( 'post' ) );
}
	// get all terms for the given array of post IDs


	$terms = array_map(
		function ( $item ) {
			return [
				'id'       => $item->term_id,
				'name'     => $item->name,
				'slug'     => $item->slug,
				'taxonomy' => $item->taxonomy,
			];
		},
		$terms
	);

	$active_term = array_find( $terms, function ( $term ) use ( $active_term ) {
		return $term['slug'] === $active_term;
	}, (object) [] );

	$filter_terms = array_map(
		function ( $item ) {
			return [
				'term_id'  => $item->term_id,
				'name'     => $item->name,
				'slug'     => $item->slug,
				'taxonomy' => $item->taxonomy,
			];
		},
		$filter_terms
	);

	$active_filter = array_find( $filter_terms, function ( $term ) use ( $active_filter ) {
		return $term['slug'] === $active_filter;
	}, (object) [] );

	$parameters = [
		'postType'         => 'post',
		'perPage'          => get_option( 'posts_per_page', 12 ),
		'title'            => __( 'Latest insights', 'dff' ),
		'showSortBy'       => true,
		'showFilters'      => true,
		'endpoint'         => rest_url( 'dff/v1/post-feed' ),
		'orderby'          => 'date',
		'order'            => 'desc',
		'terms'            => array_values( $terms ),
		'filterTerms'      => array_values( $filter_terms ),
		'showCardDate'     => true,
		'imageSize'        => 'post-feed-large',
		'showCardTaxonomy' => true,
		'filterTitle'      => __( 'Filter by content type', 'dff' ),
		'activeTerm'       => $active_term,
		'termFilter'       => $active_filter,
		'activeTermBase'   => 'article-type',
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

	$page_content = get_the_content( null, null, get_option( 'page_for_posts' ) );

	echo wp_kses_post( apply_filters( 'the_content', $page_content ) );
	?>

<section class="wp-block-dff-section section">
	<div class="container">
		<div class="archivePosts" data-posts-archive>
			<input type="hidden" value="<?php echo esc_attr( wp_json_encode( $parameters ) ); ?>">
			<div class="archivePosts-inner">
				<header class="archivePosts-header">
						<h1><?php echo esc_html( $parameters['title'] ); ?></h1>

						<div className="archivePosts-sortBy">
							<label><?php _e( 'Sort By', 'dff' ); ?></label>
							<select>
								<option value="date-asc"><?php _e( 'Date (OLDEST)', 'dff' ); ?></option>
								<option value="date-desc"><?php _e( 'Date (NEWEST)', 'dff' ); ?></option>
							</select>
						</div>
				</header>
				<?php if ( ! empty( $parameters['showFilters'] ) ) : ?>
					<nav class="archivePosts-filters">
						<span><?php echo esc_html( $parameters['filterTitle'] ); ?></span>
						<ul>
							<?php foreach ( $terms as $item ) : ?>
								<li><button><?php echo esc_html( $item['name'] ); ?></button></li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>
				<ul class="archivePosts-list">
				<?php foreach ( $items as $item ) : ?>
					<li>
						<article class="archivePosts-item" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
							<a href="<?php echo esc_url( $item['permalink'] ); ?>">
								<figure>
									<img
										src="<?php echo esc_url( $item['image'] ); ?>"
										<?php
											! empty( $item['image@2x'] ) && $item['image@2x'] !== $item['image'] && printf( ' srcset="%s 2x"', esc_url( $item['image@2x'] ) );
										?>
										alt=""
									>
								</figure>
								<div class="archivePosts-content">
									<header>
										<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="archivePosts-title"><?php echo esc_html( $item['title'] ); ?></h1>
										<?php if ( ! empty( $item['term'] ) && $parameters['showCardTaxonomy'] ) : ?>
											<span class="archivePosts-tag"><?php echo esc_html( $item['term']['name'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $item['meta'] ) && $parameters['showCardDate'] ) : ?>
											<span class="archivePosts-meta"><?php echo esc_html( $item['meta'] ); ?></span>
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
</section>


<?php

get_footer();
