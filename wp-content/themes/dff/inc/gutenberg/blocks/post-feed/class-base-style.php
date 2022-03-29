<?php
declare(strict_types = 1);
namespace DFF\Gutenberg\Blocks\PostFeed;

use \DFF\Rest\Rest_Post_Feed;

abstract class Base_Style implements Style {
	public $name          = 'Unamed Style';
	public $no_of_posts   = 5;
	public $image_size    = 'full';
	public $image_size_2x = 'full';

	protected function tagGenerator( string $heading_tag ) {
		switch ( $heading_tag ) {

			case 'h4':
				return 'h4';

			case 'h3':
				return 'h3';

			case 'h2':
				return 'h2';

			case 'h1':
			default:
				return 'h1';
		}
	}

	public function get_no_of_posts( array $attributes ): int {
		return $this->no_of_posts;
	}

	public function get_items( array $attributes = [] ): array {
		$taxonomies        = [];
		$is_selection_mode = $attributes['isSelectionMode'] ?? false;
		$selected_posts    = $attributes['selectedPosts'] ?? [];

		if ( ! empty( $attributes['taxonomies'] ) ) {
			$taxonomies = array_filter( $attributes['taxonomies'], function ( $value ) {
				return ! empty( $value );
			} );
		}

		if ( $is_selection_mode ) {
			$query = Rest_Post_Feed::query(
				[ 'page', 'post', 'event', 'program', 'future-talk' ],
				$taxonomies,
				$this->get_no_of_posts( $attributes ),
				[
					'post__in' => $selected_posts,
					'orderby'  => 'post__in',
				]
			);
		} else {
			$query = Rest_Post_Feed::query( $attributes['postTypes'], $taxonomies, $this->get_no_of_posts( $attributes ) );
		}

		if ( ! $query->have_posts() ) {
			wp_reset_postdata();
			return [];
		}

		$posts = [];

		while ( $query->have_posts() ) {
			$query->the_post();
			$category  = dff_get_category( get_the_ID() );
			$post_type = get_post_type( get_the_ID() );

			$posts[] = [
				'id'        => get_the_ID(),
				'title'     => get_the_title(),
				'excerpt'   => dff_get_excerpt( 250 ),
				'alt'       => dff_get_alt_tag( get_the_ID() ),
				'image'     => dff_featured_image( get_the_ID(), $this->image_size ),
				'image@2x'  => ! empty( $this->image_size_2x ) ? dff_featured_image( get_the_ID(), $this->image_size_2x ) : false,
				'term'      => $category ? [
					'name' => $category->name,
					'link' => dff_get_posttype_category_link( $post_type, $category->slug, dff_get_posttype_category_name( $post_type ) ),
				] : false,
				'permalink' => get_the_permalink(),
				'type'      => $post_type,
			];
		}

		wp_reset_postdata();

		return $posts;
	}
}
