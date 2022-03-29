<?php
namespace DFF\CLI\Models\Terms;

class CategoryModel extends TermModel {
	protected $type = 'category';

	public function getData() {
		return [
			'term_name'        => $this->data['cat_name'],
			'slug'             => $this->data['category_nicename'],
			'term_description' => $this->data['category_description'],
			'term_parent'      => $this->data['category_parent'],
		];
	}
}
