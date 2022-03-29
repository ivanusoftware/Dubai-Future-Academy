<?php

namespace DFF\Gutenberg\Blocks;

use WP_Query;

class Featured_Posts {
	public $attributes = [];

	public function __construct() {
		register_block_type(
			'dff/featured-posts',
			[
				'render_callback' => [ $this, 'render' ],
				'editor_script'   => 'dff-gutenberg-scripts',
			]
		);
	}

	public function process_query( $args ) {
		$query = new WP_Query( $args );
		$posts = [];

		if ( ! $query->have_posts() ) {
			return $posts;
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			$category = dff_get_category( get_the_ID() );

			if ( 'uncategorized' === $category ) {
				$category = false;
			}

			$posts[] = [
				'id'             => get_the_ID(),
				'title'          => get_the_title(),
				'link'           => dff_get_permalink(),
				'featured_image' => dff_featured_image( get_the_ID(), 'featured-post' ),
				'excerpt'        => dff_get_excerpt( 140 ),
				'date'           => get_the_date( 'j M Y' ),
				'category'       => $category,
				'alt'            => dff_get_alt_tag( get_the_ID() ),
				'type'           => get_post_type(),
			];
		}

		return $posts;
	}

	public function handle_select() {
		$post_types = get_post_types( [
			'public' => true,
		] );

		return $this->process_query( [
			'post__in'            => $this->attr( 'posts', [] ),
			'post_type'           => $post_types,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => 1,
			'orderby'             => 'post__in',
			'posts_per_page'      => -1,
		] );
	}

	public function handle_feed() {
		return $this->process_query( [
			'post_type'           => $this->attr( 'postType', 'post' ),
			'posts_per_page'      => $this->attr( 'perPage', 3 ),
			'post__not_in'        => [ get_the_ID() ],
			'ignore_sticky_posts' => 1,
		] );
	}

	public function handle_category() {
		return $this->process_query( [
			'post__not_in'        => [ get_the_ID() ],
			'posts_per_page'      => $this->attr( 'perPage', 3 ),
			'no_found_rows'       => true,
			'post_type'           => $this->attr( 'postType', 'post' ),
			'ignore_sticky_posts' => 1,
			'cat'                 => $this->attr( 'category', 1 ),
		] );
	}

	public function get_items() {
		$type = $this->attr( 'type', 'dynamic' );

		switch ( $type ) {
			case 'selected':
				return $this->handle_select();
			case 'category':
				return $this->handle_category();
			case 'dynamic':
			default:
				return $this->handle_feed();
		}
	}

	public function render_item( $item ) {
		?>
		<article class="featured-post" aria-labelledby="article-<?php echo esc_attr( $item['id'] ); ?>">
			<figure class="featured-postFigure">
				<img src="<?php echo esc_url( $item['featured_image'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" />
			</figure>
			<div class="featured-postContent">
				<header class="featured-postHeader">
					<?php
					if ( $item['category'] ) :
						$term_link = dff_get_posttype_category_link( $item['type'], $item['category']->slug, dff_get_posttype_category_name( $item['type'] ) );
						if ( ! is_wp_error( $term_link ) ) :
							?>
					<a href="<?php echo esc_url( $term_link ); ?>" class="featured-postTag"><?php echo esc_html( $item['category']->name ); ?></a>
							<?php
						endif;
					endif;
					?>
					<h1 id="article-<?php echo esc_attr( $item['id'] ); ?>" class="featured-postTitle"><a href="<?php echo esc_url( $item['link'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h1>
				</header>
				<p><?php echo esc_html( $item['excerpt'] ); ?></p>
			</div>
		</article>
		<?php
	}

	public function render( $attributes ) {
		$this->attributes = $attributes;
		$items            = $this->get_items();

		if ( empty( $items ) ) {
			return '';
		}
		ob_start();
		?>
		<div class="featured-posts has-<?php echo esc_attr( count( $items ) ); ?>-posts">
		<?php
		foreach ( $items as $item ) {
			$this->render_item( $item );
		}
		?>
		</div>
		<?php
		return ob_get_clean();
	}

	public function attr( $name, $default = null ) {
		return ! empty( $this->attributes[ $name ] ) ? $this->attributes[ $name ] : $default;
	}
}

new Featured_Posts();
