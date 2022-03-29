<?php

namespace DFF\CLI;


use DFF\CLI\Models\PostTypes\NewsroomNewsModel;
use DFF\CLI\Models\PostTypes\PostModel;
use \WP_CLI;

class WP_CLI_Content_Sync {
	public function __construct() {
		WP_CLI::add_command( 'dff', [ $this, 'handle_command' ], [] );
	}

	public function handle_command( $args, $assoc_args ) {
		if ( 'import' === $args[0] ) {
			return $this->handle_sync( $args, $assoc_args );
		}
	}

	public function handle_sync( $args, $assoc_args ) {
		if ( isset( $assoc_args['dff-debug'] ) && $assoc_args['dff-debug'] ) {
			define( 'DFF_DEBUG', true );
		}

		if ( is_multisite() ) {
			$current_blog = ! empty( $assoc_args['dff-blog'] ) ? $assoc_args['dff-blog'] : get_main_site_id();
			switch_to_blog( $current_blog );
		}

		$to_skip = ! empty( $assoc_args['skip'] ) ? explode( ',', $assoc_args['skip'] ) : [];
		$to_skip = apply_filters( 'dff_importer_actions_to_skip', $to_skip );

		if ( ! isset( $assoc_args['file'] ) ) {
			WP_CLI::error( 'Missing <file> parameter', true );
		}

		if ( ! is_file( $assoc_args['file'] ) ) {
			WP_CLI::error( '<file> parameter is invalid', true );
		}

		if ( ! class_exists( 'WXR_Parser' ) ) {
			WP_CLI::error( 'WordPress importer needs to be enabled', true );
		}

		$file = $assoc_args['file'];

		$parser = new \WXR_Parser();
		dff_info( 'Parsing XML File' );
		$data = $parser->parse( $file );
		dff_success( 'XML File parsed' );

		define( 'DFF_IMPORT_BASE_URL', $data['base_url'] );

		$exclude = apply_filters( 'dff_import_exclude_post_type', [ 'wpcf7_contact_form', 'team-members', 'acf-field-group', 'page', 'slider', 'custom_css', 'humans-of-business', 'nav_menu_item', 'acf-field', "client","collaborate","elementor_library","our-initiatives", "podcasts","postman_sent_mail","pricing","teams","testimonials" ] );

		$posts = array_filter( $data['posts'], function ( $post ) use ( $exclude ) {
			return ! in_array( $post['post_type'], $exclude, true );
		} );

		if ( ! in_array( 'authors', $to_skip, true ) ) {
			if ( ! empty( $data['authors'] ) ) {
				$this->sync_users( $data['authors'] );
			} else {
				dff_info( 'No authors to import.' );
			}
		} else {
			dff_warning( 'Skipping Authors' );
		}

		if ( ! in_array( 'tags', $to_skip, true ) && ! in_array( 'terms', $to_skip, true ) ) {
			if ( ! empty( $data['tags'] ) ) {
				$this->sync_tags( $data['tags'] );
			} else {
				dff_info( 'No tags to import.' );
			}
		} else {
			dff_warning( 'Skipping Tags' );
		}

		if ( ! in_array( 'categories', $to_skip, true ) && ! in_array( 'terms', $to_skip, true ) ) {
			if ( ! empty( $data['categories'] ) ) {
				$this->sync_categories( $data['categories'] );
			} else {
				dff_info( 'No categories to import.' );
			}
		} else {
			dff_warning( 'Skipping categories' );
		}

		$items = [];

		foreach ( $posts as $post ) {
			if ( ! isset( $items[ $post['post_type'] ] ) ) {
				$items[ $post['post_type'] ] = [];
			}
			$items[ $post['post_type'] ][] = $post;
		}

		if ( ! in_array( 'attachment', $to_skip, true ) ) {
			if ( ! empty( $items['attachment'] ) ) {
				$this->sync_attachments( $items['attachment'] );
			} else {
				dff_info( 'No attachments to import.' );
			}
		} else {
			dff_warning( 'Skipping attachments' );
		}

		if ( ! in_array( 'post', $to_skip, true ) ) {
			if ( ! empty( $items['post'] ) ) {
				$this->sync_posts( $items['post'] );
			} else {
				dff_info( 'No posts to import.' );
			}
		} else {
			dff_warning( 'Skipping posts' );
		}

		$external_importers = apply_filters( 'dff_importer_register_types', [] );

		foreach ( $external_importers as $type => $handler ) {
			if ( ! empty( $items[ $type ] ) && ! in_array( $type, $to_skip, true ) ) {
				call_user_func_array( $handler, [ $items[ $type ] ] );
			}
		}

		dff_info( 'Remapping URLs in the content.' );
		$this->remap_urls_in_content();
		dff_success( 'Finished remapping urls' );
		$this->update_term_count();
		dff_success( 'Import complete.' );
	}

	public function sync_users( $users ) {
		dff_info( 'Importing Authors' );
		$progress = WP_CLI\Utils\make_progress_bar( 'Importing Authors', count( $users ) );

		foreach ( $users as $user ) {
			new Models\UserModel( $user['author_id'], $user );
			$progress->tick();
		}

		$progress->finish();
		dff_success( 'Authors imported' );
	}

	public function sync_tags( $tags ) {
		dff_info( 'Importing Tags' );
		$progress = WP_CLI\Utils\make_progress_bar( 'Importing Tags', count( $tags ) );

		foreach ( $tags as $tag ) {
			new Models\Terms\PostTagModel( $tag['term_id'], $tag );
			$progress->tick();
		}

		$progress->finish();
		dff_success( 'Tags imported' );
	}

	public function sync_categories( $tags ) {
		dff_info( 'Importing Categories' );
		$progress = WP_CLI\Utils\make_progress_bar( 'Importing Categories', count( $tags ) );

		foreach ( $tags as $tag ) {
			new Models\Terms\CategoryModel( $tag['term_id'], $tag );
			$progress->tick();
		}

		$progress->finish();
		dff_success( 'Categories imported' );
	}

	public function sync_attachments( $attachments ) {
		dff_info( 'Importing Attachments' );

		$progress = WP_CLI\Utils\make_progress_bar( 'Importing Attachments', count( $attachments ) );

		foreach ( $attachments as $attachment ) {
			new Models\PostTypes\AttachmentModel( $attachment['post_id'], $attachment );
			$progress->tick();
		}

		$progress->finish();
		dff_success( 'Attachments imported' );
	}

	public function sync_posts( $attachments ) {
		dff_info( 'Importing Posts' );

		$progress = WP_CLI\Utils\make_progress_bar( 'Importing Posts', count( $attachments ) );

		foreach ( $attachments as $attachment ) {
			$attachment['status'] = 'draft';
			new Models\PostTypes\PostModel( $attachment['post_id'], $attachment );
			$progress->tick();
		}

		$progress->finish();
		dff_success( 'Posts imported' );
	}

	public function remap_urls_in_content() {
		global $wpdb;
		$urls = UrlMapper::get();

		uksort( $urls, [$this, 'cmpr_strlen'] );

		$progress = WP_CLI\Utils\make_progress_bar( 'Remapping urls', count( $urls ) );

		foreach ( $urls as $from_url => $to_url ) {
			dff_debug( 'Remaping ' . $from_url . ' to ' . $to_url );
			$wpdb->query( $wpdb->prepare("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)", $from_url, $to_url) );
			// remap enclosure url
			$result = $wpdb->query( $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s)", $from_url, $to_url) );
			$progress->tick();
		}

		$progress->finish();
	}

	public function cmpr_strlen( $a, $b ) {
		return strlen( $b ) - strlen( $a );
	}

	public function update_term_count(): void {
		$taxonomies = get_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			$taxonomies[ $taxonomy ] = array_map( function ( $term ) {
				return $term->term_taxonomy_id;
			}, get_terms( [
				'taxonomy' => $taxonomy,
				'hide_empty' => 0,
			] ) );

			if (empty( $taxonomies[
				$taxonomy
			] )) continue;


			dff_debug( wp_update_term_count_now( $taxonomies[$taxonomy], $taxonomy) );
		}
	}
}

new WP_CLI_Content_Sync();
