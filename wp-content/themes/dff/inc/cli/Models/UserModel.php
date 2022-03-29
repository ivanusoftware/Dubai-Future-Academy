<?php

namespace DFF\CLI\Models;

class UserModel extends BaseModel {
	public function find() {
		! empty( $this->data ) ? $this->get_user_by_email() : $this->get_user_by_dff_id();

		if ( ! $this->id || is_wp_error( $this->id ) ) {
			return false;
		}

		return $this->id;
	}

	public function create() {
		$data = $this->data;

		$user = wp_insert_user( [
			'user_login'   => $data['author_login'],
			'user_email'   => $data['author_email'],
			'user_pass'    => openssl_random_pseudo_bytes( 32 ),
			'display_name' => $data['author_display_name'],
			'first_name'   => $data['author_first_name'],
			'last_name'    => $data['author_last_name'],
		] );

		if ( is_wp_error( $user ) ) {
			return $user;
		}

		dff_debug( 'Created User' . $this->dff_id );

		$this->id = $user;
		update_user_meta( $this->id, self::$meta_field, $this->dff_id );
		update_user_meta( $this->id, '_dff_import_user', $data['author_login'] );
	}

	public function update() {
		// TODO: Implement update() method.
	}

	private function get_user_by_dff_id() {
		return $this->get_user_by_meta( self::$meta_field, $this->dff_id );
	}

	private function get_user_by_meta( $key, $value ) {
		$args = [
			'meta_key'   => $key,
			'meta_value' => $value,
			'fields'     => [ 'ID' ],
		];

		$query = new \WP_User_Query( $args );

		if ( $query->get_total() < 1 ) {
			return false;
		}

		$users = $query->get_results();

		$this->id = $users[0]->ID;

		return $this->id;
	}

	private function get_user_by_email() {
		$user = get_user_by( 'email', $this->data['author_email'] );

		if ( ! $user ) {
			return false;
		}

		$this->id = $user->ID;

		return $this->id;
	}

	public function get_user_by_user_login( $login ) {
	    return $this->get_user_by_meta( '_dff_import_user', $login );
	}
}
