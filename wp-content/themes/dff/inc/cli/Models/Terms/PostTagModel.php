<?php
namespace DFF\CLI\Models\Terms;

class PostTagModel extends TermModel {
	protected $type = 'post_tag';

	public function getData() {
		return [
			'term_name'        => $this->data['tag_name'],
			'slug'             => $this->data['tag_slug'],
			'term_description' => $this->data['tag_description'],
			'term_parent'      => 0,
		];
	}
}
