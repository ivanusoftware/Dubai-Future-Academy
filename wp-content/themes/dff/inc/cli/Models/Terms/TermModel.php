<?php

namespace DFF\CLI\Models\Terms;

use DFF\CLI\Models\BaseModel;

abstract class TermModel extends BaseModel {
	public function find() {
		if ( ! $this->type ) {
			return new \WP_Error( 'invalid_term', 'Taxonomy is a required field' );
		}

		$args = [
			'taxonomy'   => $this->type,
			'meta_query' => [
				[
					'key'     => self::$meta_field,
					'value'   => $this->dff_id,
					'compare' => '=',
				],
			],
			'hide_empty' => false,
		];

		$term_query = new \WP_Term_Query( $args );
		$terms      = $term_query->get_terms();

		if ( 0 === count( $terms ) ) {
			return false;
		}

		$this->id        = $terms[0]->term_id;
		$this->item_data = $terms[0];

		return $this->id;
	}

	public function create() {
		$data = $this->getData();

		$term = wp_insert_term( $data['term_name'], $this->type, [
			'slug'        => $data['slug'],
			'description' => $data['term_description'],
			'parent'      => $data['term_parent'],
		] );

		if ( is_wp_error( $term ) ) {
			dff_debug( $term->get_error_message() );
			return $term;
		}

		dff_debug( 'Created Term' . $this->dff_id );

		$this->id = $term['term_id'];
		add_term_meta( $this->id, self::$meta_field, $this->dff_id );
	}

	public function find_by_slug( $slug ) {
		return get_term_by( 'slug', $slug, $this->type );
	}

	public function exists() {
		if ( false === $this->data ) {
			$this->find();
		}

		return $this->id;
	}

	public function getData() {
		return $this->data;
	}

	public function update() {}
}
