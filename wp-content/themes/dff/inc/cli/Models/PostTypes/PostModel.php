<?php

namespace DFF\CLI\Models\PostTypes;

use DFF\CLI\Models\Terms\BusinessCatModel;
use DFF\CLI\Models\Terms\CategoryModel;

class PostModel extends TypeModel {
	protected $type = 'post';

	public function get_meta() {
		$meta = parent::get_meta();

		$img_ids = [
			'_thumbnail_id',
			'_yoast_wpseo_opengraph-image-id',
			'_yoast_wpseo_twitter-image-id',
		];

		$url_ids = [
			'_yoast_wpseo_opengraph-image' => '_yoast_wpseo_opengraph-image-id',
			'_yoast_wpseo_twitter-image'   => '_yoast_wpseo_twitter-image-id',
		];

		foreach ( $img_ids as $key ) {
			if ( ! isset( $meta[ $key ] ) ) {
				continue;
			}

			$attachment    = new AttachmentModel( $meta[ $key ] );
			$attachment_id = $attachment->find();

			if ( ! $attachment_id || is_wp_error( $attachment_id ) ) {
				unset( $meta[ $key ] );
			} else {
				$meta[ $key ] = $attachment_id;
			}
		}

		foreach ( $url_ids as $key => $id_key ) {
			if ( ! isset( $meta[ $key ] ) ) {
				continue;
			}

			if ( ! isset( $meta[ $id_key ] ) ) {
				unset( $meta[ $key ] );
				continue;
			}

			$meta[ $key ] = wp_get_attachment_image_url( $meta[ $id_key ], 'full' );
		}

		if ( isset( $meta['_yoast_wpseo_primary_category'] ) ) {
			$category = new CategoryModel( $meta['_yoast_wpseo_primary_category'] );
			$cat_id   = $category->find();

			if ( ! $cat_id || is_wp_error( $cat_id ) ) {
				unset( $meta['_yoast_wpseo_primary_category'] );
			} else {
				$meta['_yoast_wpseo_primary_category'] = $cat_id;
			}
		}

		if ( isset( $meta['_yoast_wpseo_primary_business_cat'] ) ) {
			$category = new BusinessCatModel( $meta['_yoast_wpseo_primary_business_cat'] );
			$cat_id   = $category->find();

			if ( ! $cat_id || is_wp_error( $cat_id ) ) {
				unset( $meta['_yoast_wpseo_primary_business_cat'] );
			} else {
				$meta['_yoast_wpseo_primary_business_cat'] = $cat_id;
			}
		}

		return $meta;
	}

	public function get_taxonomies() {
		$taxonomies = parent::get_taxonomies();

		return array_merge( $taxonomies, [
			'article-type' => ['news'],
		] );
	}
}
