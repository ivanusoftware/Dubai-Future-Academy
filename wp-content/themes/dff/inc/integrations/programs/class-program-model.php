<?php

namespace DFF\Integrations\Programs;

use WP_Query;

class ProgramModel {
	private $exists = false;

	private $model_version = '1.0.1';

	private $external_id;
	private $data;

	private static $meta_key = '_external_id';
	private static $posttype = 'program';

	private static $meta_map = [
		'updatedAt'           => '_updated_at',
		'isPublic'            => '_is_public',
		'programOwner'        => '_program_owner',
		'applicationDeadline' => '_application_deadline',
	];

	public function __construct( $id, $data = false ) {
		$this->external_id = $id;

		$this->find();

		if ( $data ) {
			$this->data = $data;
			$this->create_or_update();
		}
	}

	private function create_or_update() {
		if ( $this->exists() ) {
			return $this->update();
		}

		$this->create();
	}

	private function find() {
		$query = new WP_Query( [
			'post_type'   => self::$posttype,
			'meta_key'    => self::$meta_key,
			'meta_value'  => $this->external_id, // phpcs:ignore
			'post_status' => [ 'publish', 'draft' ],
		] );

		if ( ! $query->have_posts() ) {
			return false;
		}

		$query->the_post();
		$this->internal_id = get_the_ID();
		$this->exists      = true;
		wp_reset_postdata();

		if ( ! $this->data ) {
			$this->set_data_from_meta();
		}

		return true;
	}

	private function create() {
		$time = date( 'Y-m-d H:i:s', strtotime( $this->get( 'createdAt' ) ?: $this->get( 'updatedAt' ) ) );

		$post_id = wp_insert_post( [
			'ID'            => 0,
			'post_title'    => $this->get( 'name' ),
			'post_type'     => self::$posttype,
			'post_status'   => $this->get_poststatus(),
			'meta_input'    => $this->get_meta(),
			'post_content'  => $this->get( 'description' ),
			'post_date'     => $time,
			'post_date_gmt' => $time,
		] );

		if ( is_wp_error( $post_id ) ) {
			return false;
		}

		$taxonomies = $this->get_taxonomies();

		foreach ( $taxonomies as $taxonomy => $terms ) {
			wp_set_post_terms( $post_id, $terms, $taxonomy, false );
		}


		$this->internal_id = $post_id;
		$this->exists      = true;

		$this->sync_image();

		return true;
	}

	private function update() {
		if ( $this->should_remove() ) {
			return $this->remove();
		}

		if ( ! $this->should_update() ) {
			return true;
		}

		$post_id = wp_update_post( [
			'ID'           => $this->id(),
			'post_title'   => $this->get( 'name' ),
			'post_type'    => self::$posttype,
			'post_status'  => $this->get_poststatus(),
			'meta_input'   => $this->get_meta(),
			'post_content' => $this->get( 'description' ),
		] );

		if ( is_wp_error( $post_id ) ) {
			return false;
		}

		$taxonomies = $this->get_taxonomies();

		foreach ( $taxonomies as $taxonomy => $terms ) {
			wp_set_post_terms( $this->id(), $terms, $taxonomy, false );
		}

		$this->sync_image();

		return true;
	}

	private function remove() {
	}

	private function should_update(): bool {
		$last_updated     = get_post_meta( $this->id(), '_updated_at', true );
		$model_version    = get_post_meta( $this->id(), '_model_version', true );
		$external_updated = $this->get( 'updatedAt', false );

		if ( $model_version !== $this->model_version || ! $last_updated || ! $external_updated ) {
			return true;
		}

		return strtotime( $last_updated ) < strtotime( $external_updated );
	}

	private function should_remove(): bool {
		return ! $this->get( 'isPublic', false );
	}

	private function set_data_from_meta() {
		$this->data = array_reduce( array_keys( self::$meta_map ), function( $carry, $meta_key ) {
			$carry[ ProgramModel::$meta_map[ $meta_key ] ] = json_decode( get_post_meta( $this->internal_id, $meta_key, true ), true );
			return $carry;
		}, [] );
	}

	private function get_poststatus(): string {
		if ( $this->exists() ) {
			return get_post_status( $this->id() );
		}

		return 'publish';
	}

	private function get_meta() {
		$meta                    = [
			'_model_version' => $this->model_version,
		];
		$meta[ self::$meta_key ] = $this->external_id;

		foreach ( self::$meta_map as $key => $meta_key ) {
			$field = $this->get( $key );

			if ( is_null( $field ) ) {
				continue;
			}

			if ( is_array( $field ) ) {
				$field = wp_json_encode( $field );
			}

			$meta[ $meta_key ] = $field;
		}

		return $meta;
	}

	private function get_facilitator_taxonomies() {
		$facilitator = $this->get( 'programOwner' );

		if ( ! $facilitator['name'] ) {
			return [];
		}

		$term = get_term_by( 'name', $facilitator['name'], 'facilitator', ARRAY_A );

		if ( ! $term ) {
			$term = wp_insert_term( $facilitator['name'], 'facilitator' );
		}

		if ( is_wp_error( $term ) ) {
			return [];
		}

		return [ (int) $term['term_id'] ];
	}

	private function get_challenge_taxonomies() {
		$facilitator = $this->get( 'challengeOwner' );

		if ( ! isset( $facilitator['name'] ) ) {
			return [];
		}

		$term = get_term_by( 'name', $facilitator['name'], 'challenge-owners', ARRAY_A );

		if ( ! $term ) {
			$term = wp_insert_term( $facilitator['name'], 'challenge-owners' );
		}

		if ( is_wp_error( $term ) ) {
			return [];
		}

		return [ (int) $term['term_id'] ];
	}

	private function get_taxonomies() {
		return [
			'facilitator'      => $this->get_facilitator_taxonomies(),
			'challenge-owners' => $this->get_challenge_taxonomies(),
		];
	}

	private function should_sync_image() {
		$thumbnail_id = get_post_thumbnail_id( $this->id() );

		if ( ! $thumbnail_id ) {
			return true;
		}

		$new_image_url = $this->get( 'coverImage', false );

		if ( ! $new_image_url ) {
			$images        = $this->get( 'detailImages', [] );
			$new_image_url = $images[0] ?? '';
		}

		$current_image_url = get_post_meta( $thumbnail_id, '_external_url', true );

		return $new_image_url !== $current_image_url;
	}

	public function exists(): bool {
		return $this->exists;
	}

	public function id(): int {
		return $this->internal_id;
	}

	public function get( $field, $default = null ) {
		if ( ! empty( $this->data[ $field ] ) ) {
			return $this->data[ $field ];
		}

		if ( ! empty( $this->data['programOwner'][ $field ] ) ) {
			return $this->data['programOwner'][ $field ];
		}

		return $default;
	}


	public function sync_image() {
		if ( ! $this->should_sync_image() ) {
			return;
		}

		$new_image_url = $this->get( 'coverImage', false );

		if ( ! $new_image_url ) {
			$images        = $this->get( 'detailImages', [] );
			$new_image_url = $images[0] ?? '';
		}

		if ( ! $new_image_url ) {
			delete_post_thumbnail( $this->id() );
		}

		$attachment = new AttachmentModel( $new_image_url );

		if ( $attachment->exists() ) {
			set_post_thumbnail( $this->id(), $attachment->id() );
		}
	}
}
