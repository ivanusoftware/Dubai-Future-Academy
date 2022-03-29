<?php

namespace DFF\CLI\Models\PostTypes;

use DFF\CLI\Models\BaseModel;
use DFF\CLI\Models\UserModel;
use DFF\CLI\UrlMapper;

abstract class TypeModel extends BaseModel {
	public function find() {
		if ( ! $this->type ) {
			return new \WP_Error( 'invalid_type', 'Type is a required field' );
		}

		$args = [
			'post_type'   => $this->type,
			'post_status' => 'any',
			'meta_query'  => [
				[
					'key'     => self::$meta_field,
					'value'   => $this->dff_id,
					'compare' => '=',
				],
			],
		];

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return false;
		}

		$query->the_post();

		$this->id = get_the_ID();

		return $this->id;
	}

	public function create() {
		$post = wp_insert_post( $this->get_post_data(), true );

		if ( is_wp_error( $post ) ) {
			return $post;
		}

		$taxonomies = $this->get_taxonomies();
		foreach ( $taxonomies as $taxonomy => $terms ) {
			wp_set_object_terms( $post, $terms, $taxonomy );
		}

		dff_debug( 'Created ' . $this->type . ' ' . $this->dff_id );

		UrlMapper::add( $this->data['link'], get_permalink( $post ) );

		$this->id = $post;
		update_post_meta( $this->id, self::$meta_field, $this->dff_id );
	}

	public function get_post_data() {
		$data = $this->data;

		$author = new UserModel( $data['post_author'] ?? 1 );
		$author = $author->get_user_by_user_login( $data['post_author'] ?? false );

		if ( ! $author ) {
			$author = 1;
		}

		// TODO: Replace this with the newly imported post parent id.
		$post_parent = 0;

		return [
			'post_author'    => $author,
			'post_date'      => $data['post_date'] ?? '',
			'post_date_gmt'  => $data['post_date_gmt'] ?? '',
			'post_content'   => $data['post_content'] ?? '',
			'post_excerpt'   => $data['post_excerpt'] ?? '',
			'post_title'     => $data['post_title'] ?? '',
			'post_status'    => $data['status'] ?? 'draft',
			'post_name'      => $data['post_name'] ?? '',
			'comment_status' => $data['comment_status'] ?? '',
			'ping_status'    => $data['ping_status'] ?? '',
			'guid'           => $data['guid'] ?? '',
			'post_parent'    => $post_parent,
			'menu_order'     => $data['menu_order'] ?? 0,
			'post_type'      => $this->type,
			'post_password'  => $data['post_password'] ?? '',
			'meta_input'     => $this->get_meta(),
		];
	}

	public function update() {
		// TODO: Implement update() method.
	}

	public function get_meta() {
		if ( empty( $this->data['postmeta'] ) ) {
			return [];
		}

		$post_meta = $this->data['postmeta'];

		return array_reduce( $post_meta, function ( $carry, $meta ) {
			if ( ! $meta['value'] ) {
				return $carry;
			}

			$carry[ $meta['key'] ] = $meta['value'];
			return $carry;
		}, []);
	}

	public function get_taxonomies() {
		$tagmap = [
			'category' => 'category',
			'post_tag' => 'post_tag',
		];

		$terms = $this->data['terms'] ?? [];


		return array_reduce( $terms, function ( $carry, $term ) use ( $tagmap ) {
			if ( ! isset( $tagmap[ $term['domain'] ] ) ) {
				dff_warning( $term['domain'] . ' isn\'t a valid taxonomy term within this importer' );
				return $carry;
			}

			$taxonomy = $tagmap[ $term['domain'] ];

			if ( ! isset( $carry[ $taxonomy ] ) ) {
				$carry[ $taxonomy ] = [];
			}

			$carry[ $taxonomy ][] = $term['slug'];
			return $carry;
		}, [] );
	}
}
