<?php
namespace DFF\CLI\Models\PostTypes;

use DFF\CLI\UrlMapper;
use WP_Error;

class AttachmentModel extends TypeModel {
	protected $type = 'attachment';

	public function create() {
		$post = $this->get_post_data();

		$remote_url = ! empty( $post['attachment_url'] ) ? $post['attachment_url'] : $post['guid'];

		if ( preg_match( '|^/[\w\W]+$|', $remote_url ) ) {
			$remote_url = rtrim( DFF_IMPORT_BASE_URL, '/' ) . $remote_url;
		}

		$original_url = $remote_url;

		if ( false !== strpos( 'storage.googleapis.com/dffopusgroup.com', $remote_url ) ) {
			$remote_url = str_replace( 'storage.googleapis.com/dffopusgroup.com', rtrim( DFF_IMPORT_BASE_URL, '/' ) . 'wp-content/uploads/' . date( 'Y/m', strtotime( $post['post_date'] ) ), $remote_url );
		}

		dff_debug( $remote_url );

		$upload = $this->handle_upload( $remote_url, $post );

		if ( is_wp_error( $upload ) ) {
			return $upload;
		}

		$info = wp_check_filetype( $upload['file'] );

		if ( $info ) {
			$post['post_mime_type'] = $info['type'];
		} else {
			return new WP_Error( 'attachment_processing_error', __( 'Invalid file type', 'wordpress-importer' ) );
		}

		$post['guid'] = $upload['url'];

		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		// Set dff_id;
		update_post_meta( $post_id, self::$meta_field, $this->dff_id );

		$meta = $this->get_meta();
		foreach ( $meta as $m ) {
			update_post_meta( $post_id, $m['key'], $m['value'] );
		}

		// remap resized image URLs, works by stripping the extension and remapping the URL stub.
		if ( preg_match( '!^image/!', $info['type'] ) ) {
			$parts = pathinfo( $remote_url );
			$name  = basename( $parts['basename'], ".{$parts['extension']}" ); // PATHINFO_FILENAME in PHP 5.2

			$parts_new = pathinfo( $upload['url'] );
			$name_new = basename( $parts_new['basename'], ".{$parts_new['extension']}" );
			UrlMapper::add( $parts['dirname'] . '/' . $name, $parts_new['dirname'] . '/' . $name_new );
		}
	}

	public function get_meta() {
		if ( empty( $this->data['postmeta'] ) ) {
			return [];
		}

		return array_filter( $this->data['postmeta'], function ( $meta ) {
			return '_wp_attached_file' !== $meta['key'] && '_wp_attachment_metadata' !== $meta['key'];
		});
	}

	public function get_taxonomies() {
		return [];
	}

	public function handle_upload( $url, $post ) {
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, null, '', $post['upload_date'] );

		if ( $upload['error'] ) {
			return new WP_Error( 'upload_dir_error', $upload['error'] );
		}

		// fetch the remote url and write it to the placeholder file
		$remote_response = wp_safe_remote_get( $url, array(
			'timeout'  => 300,
			'stream'   => true,
			'filename' => $upload['file'],
		) );

		$headers = wp_remote_retrieve_headers( $remote_response );

		// request failed
		if ( ! $headers ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', 'Remote server did not respond' );
		}

		$remote_response_code = wp_remote_retrieve_response_code( $remote_response );

		// make sure the fetch was successful
		if ( 200 !== (int) $remote_response_code ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', sprintf( 'Remote server returned error response %1$d %2$s', esc_html( $remote_response_code ), get_status_header_desc( $remote_response_code ) ) );
		}

		$filesize = filesize( $upload['file'] );

		if ( isset( $headers['content-length'] ) && (int) $filesize !== (int) $headers['content-length'] ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', 'Remote file is incorrect size' );
		}

		if ( 0 === $filesize ) {
			@unlink( $upload['file'] );
			return new WP_Error( 'import_file_error', 'Zero size file downloaded' );
		}

		// keep track of the old and new urls so we can substitute them later
		UrlMapper::add( $url, $upload['url'] );
		UrlMapper::add( $post['guid'], $upload['url'] );
		if ( isset( $headers['x-final-location'] ) && $headers['x-final-location'] !== $url ) {
			UrlMapper::add( $headers['x-final-location'], $upload['url'] );
		}

		return $upload;
	}

	public function get_post_data() {
		$post                   = parent::get_post_data();
		$post['upload_date']    = $post['post_date'];
		$post['attachment_url'] = $this->data['attachment_url'];

		return $post;
	}
}
