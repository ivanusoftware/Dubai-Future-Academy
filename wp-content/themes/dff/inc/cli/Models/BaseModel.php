<?php

namespace DFF\CLI\Models;

abstract class BaseModel {
	protected static $meta_field = '_dff_id';
	protected $type;
	protected $data = false;
	protected $id   = false;

	public function __construct( $item_id, $item = false ) {
		$this->dff_id = $item_id;

		if ( $item ) {
			$this->data = $item;
			$this->create_if_not_exist();
			return;
		}

		$this->find();
	}


	public function create_if_not_exist() {
		$id = $this->find();

		if ( ! $id ) {
			$id = $this->create();
		}

		if ( is_wp_error( $id ) ) {
			dff_debug( $id->get_error_message() );
		}

		return $id;
	}


	public function exists() {
		if ( false === $this->data ) {
			$this->find();
		}

		return $this->id;
	}

	abstract public function create();
	abstract public function update();
	abstract public function find();
}
