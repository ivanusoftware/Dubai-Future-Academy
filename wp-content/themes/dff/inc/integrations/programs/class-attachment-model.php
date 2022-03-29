<?php

namespace DFF\Integrations\Programs;

use Exception;
use WP_Query;
use WP_Error;

class AttachmentModel {
	private $exists = false;

	private $external_id;
	private $internal_id;

	private static $meta_key = '_external_url';
	private static $posttype = 'attachment';

	public function __construct( $id ) {
		$this->external_id = $id;

		if ( ! $this->find() ) {
			$this->create();
		}
	}


	private function find() {
		$query = new WP_Query( [
			'post_type'   => self::$posttype,
			'meta_key'    => self::$meta_key,
			'meta_value'  => $this->external_id, // phpcs:ignore
			'post_status' => [ 'publish' ],
		] );

		if ( ! $query->have_posts() ) {
			return false;
		}

		$query->the_post();
		$this->internal_id = get_the_ID();
		$this->exists      = true;
		wp_reset_postdata();

		return true;
	}

	private function create() {
		$remote_url = $this->external_id;

		$upload = $this->handle_upload( $remote_url );
		$post   = [];

		if ( is_wp_error( $upload ) ) {
			$this->exists = false;
			return $upload;
		}

		$info = wp_check_filetype( $upload['file'] );

		if ( $info ) {
			$post['post_mime_type'] = $info['type'];
		} else {
			return new WP_Error( 'attachment_processing_error', __( 'Invalid file type', 'wordpress-importer' ) );
		}

		$post['guid'] = $upload['url'];

		require_once ABSPATH . 'wp-admin/includes/image.php';
		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		update_post_meta( $post_id, self::$meta_key, $this->external_id );

		$this->internal_id = $post_id;
		$this->exists      = true;
	}

	public function handle_upload( $url ) {
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, null, '' );

		if ( $upload['error'] ) {
			return new WP_Error( 'upload_dir_error', $upload['error'] );
		}

		// fetch the remote url and write it to the placeholder file
		$remote_response = wp_safe_remote_get( $url, array(
			'timeout'  => 300, // phpcs:ignore
			'stream'   => true,
			'filename' => $upload['file'],
		) );

		$headers = wp_remote_retrieve_headers( $remote_response );

		// request failed
		if ( ! $headers ) {
			return new WP_Error( 'import_file_error', 'Remote server did not respond' );
		}

		$remote_response_code = wp_remote_retrieve_response_code( $remote_response );

		// make sure the fetch was successful
		if ( 200 !== (int) $remote_response_code ) {
			return new WP_Error( 'import_file_error', sprintf( 'Remote server returned error response %1$d %2$s', esc_html( $remote_response_code ), get_status_header_desc( $remote_response_code ) ) );
		}

		$filesize = filesize( $upload['file'] );

		if ( isset( $headers['content-length'] ) && (int) $filesize !== (int) $headers['content-length'] ) {
			return new WP_Error( 'import_file_error', 'Remote file is incorrect size' );
		}

		if ( 0 === $filesize ) {
			return new WP_Error( 'import_file_error', 'Zero size file downloaded' );
		}

		return $upload;
	}

	public function exists(): bool {
		return $this->exists;
	}

	public function id(): int {
		return $this->internal_id;
	}
}
