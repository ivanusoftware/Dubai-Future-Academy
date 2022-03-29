<?php

namespace DFF\Rest;

use WP_Query;
use WP_REST_Server;
use WP_REST_Request;
use WP_REST_Posts_Controller;

class REST_Post_Feed {
	private $allowed_single_parameters = [
		'error',
		'm',
		'p',
		'post_parent',
		'subpost',
		'subpost_id',
		'attachment',
		'attachment_id',
		'name',
		'pagename',
		'page_id',
		'second',
		'minute',
		'hour',
		'day',
		'monthnum',
		'year',
		'w',
		'category_name',
		'tag',
		'cat',
		'tag_id',
		'author',
		'author_name',
		'feed',
		'tb',
		'paged',
		'meta_key',
		'meta_value',
		'preview',
		's',
		'sentence',
		'title',
		'fields',
		'menu_order',
		'order',
		'orderby',
		'embed',
		'order',
		'orderby',
	];

	private $allowed_array_parameters = [
		'category__in',
		'category__not_in',
		'category__and',
		'post__in',
		'post__not_in',
		'post_name__in',
		'tag__in',
		'tag__not_in',
		'tag__and',
		'tag_slug__in',
		'tag_slug__and',
		'post_parent__in',
		'post_parent__not_in',
		'author__in',
		'author__not_in',
		'post_status',
	];

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
		add_filter( 'dff_rest_post_feed_get_query', [ $this, 'get_query_args' ], 10, 2 );
	}

	public function register_routes() {
		register_rest_route( 'dff/v1/post-feed', 'types', [
			'methods'             => [ WP_REST_Server::READABLE ],
			'callback'            => [ $this, 'get_post_types' ],
			// FIXME: Add correct permissions here.
			'permission_callback' => '__return_true',
		] );

		register_rest_route( 'dff/v1/post-feed', 'fetch', [
			'methods'             => [ WP_REST_Server::READABLE ],
			'callback'            => [ $this, 'get_feed' ],
			// FIXME: Add correct permissions here.
			'permission_callback' => '__return_true',
		] );

		register_rest_field( get_post_types(), 'dateString', [
			'get_callback' => [ $this, 'get_date_string' ],
		] );
	}

	public function get_date_string( $object ) {
		$meta_hook_name = sprintf( 'post_type_archive_get_%s_meta', get_post_type( $object['id'] ) );
		return apply_filters( $meta_hook_name, get_the_time( 'F j, Y', $object['id'] ), $object['id'] );
	}

	public function handle_get_post_types() {
		return $this->get_post_types( true );
	}

	public function get_post_types( $add_taxonomies = false ) {
		$available_types = apply_filters( 'dff_get_feed_post_types', $this->filter_post_types( get_post_types() ) );

		if ( ! $add_taxonomies ) {
			return $available_types;
		}

		$types = [];

		foreach ( $available_types as $type ) {
			$post_type_object = get_post_type_object( $type );

			$types[ $type ] = [
				'name'     => $post_type_object->label,
				'supports' => apply_filters( 'dff_get_feed_allowed_taxonomies', $this->filter_taxonomies( get_object_taxonomies( $type ) ) ),
			];
		}

		return $types;
	}

	public function get_all_taxonomies( $post_types = [] ) {
		$taxonomies = [];

		foreach ( $post_types as $type ) {
			$taxonomies = array_merge( $taxonomies, apply_filters( 'dff_get_feed_allowed_taxonomies', $this->filter_taxonomies( get_object_taxonomies( $type ) ) ) );
		}

		return array_unique( $taxonomies );
	}

	public function get_feed( WP_REST_Request $request ) {
		$post_types = $request->get_param( 'types' );
		$terms      = $request->get_params();
		$per_page   = $request->get_param( 'per_page' ) ?: 5;

		if ( $post_types ) {
			$post_types = explode( ',', $post_types );
			$post_types = apply_filters( 'dff_get_feed_post_types', $this->filter_post_types( $post_types ) );
		}

		if ( ! $post_types ) {
			$post_types = $this->get_post_types();
		}

		unset( $terms['types'] );
		unset( $terms['per_page'] );

		if ( ! $terms ) {
			$terms = [];
		}

		$available_taxonomies = $this->get_all_taxonomies( $post_types );

		$parameters = array_filter( $terms, function ( $taxonomy ) use ( $available_taxonomies ) {
			return ! in_array( $taxonomy, $available_taxonomies, true );
		}, ARRAY_FILTER_USE_KEY );

		$terms = array_filter( $terms, function ( $taxonomy ) use ( $available_taxonomies ) {
			return in_array( $taxonomy, $available_taxonomies, true );
		}, ARRAY_FILTER_USE_KEY );

		$terms = array_map( function( $ids ) {
			return explode( ',', $ids );
		}, $terms );

		$query = $this->query( $post_types, $terms, $per_page, $parameters );

		if ( ! $query->have_posts() ) {
			return rest_ensure_response( [
				'status' => 'success',
				'posts'  => [],
			] );
		}

		$posts = [];

		while ( $query->have_posts() ) {
			$query->the_post();
			$controller = new WP_REST_Posts_Controller( get_post_type() );
			$data       = $controller->prepare_item_for_response( get_post( get_the_ID() ), $request );
			$posts[]    = $controller->prepare_response_for_collection( $data );
		}

		wp_reset_postdata();

		return rest_ensure_response( [
			'total'  => (int) $query->found_posts,
			'status' => 'success',
			'posts'  => $posts,
		] );
	}

	public function get_query_args( $query_args, $parameters ) {
		foreach ( $parameters as $key => $value ) {
			if ( in_array( strtolower( $key ), $this->allowed_single_parameters, true ) ) {
				$query_args[ $key ] = $value;
				continue;
			}
			if ( in_array( strtolower( $key ), $this->allowed_array_parameters, true ) ) {
				if ( is_array( $value ) ) {
					$query_args[ $key ] = $value;
					continue;
				}
				$query_args[ $key ] = explode( ',', $value );
				continue;
			}
		}
		return $query_args;
	}

	public function filter_post_types( $types ) {
		$exclusions = [ 'attachment', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'wp_block' ];
		return array_values( array_filter( $types, function ( $type ) use ( $exclusions ) {
			return ! in_array( $type, $exclusions, true );
		} ) );
	}

	public function filter_taxonomies( $taxonomies ) {
		$exclusions = [ 'post_format' ];
		return array_values( array_filter( $taxonomies, function ( $taxonomy ) use ( $exclusions ) {
			return ! in_array( $taxonomy, $exclusions, true );
		} ) );
	}

	public static function query( $post_types, $terms, $per_page = 5, $parameters = [] ) {
		$query_args = [
			'post_type'      => $post_types,
			'posts_per_page' => $per_page,
			'order'          => 'DESC',
		];

		if ( ! empty( $terms ) ) {
			$query_args['tax_query'] = array_map( function ( $ids, $name ) { // phpcs:ignore
				return [
					'taxonomy' => $name,
					'terms'    => $ids,
				];
			}, $terms, array_keys( $terms ) );

			$query_args['tax_query']['relation'] = $parameters['relation'] ?? 'OR';
		}

		$query_args = apply_filters( 'dff_rest_post_feed_get_query', $query_args, $parameters );

		if ( 1 === count( $post_types ) ) {
			$query_args = apply_filters( "dff_rest_post_feed_get_{$post_types[0]}_query", $query_args, $parameters );
		}

		return new WP_Query( $query_args );
	}
}

new REST_Post_Feed();
