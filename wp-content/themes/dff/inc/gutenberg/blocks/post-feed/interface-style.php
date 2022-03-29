<?php

namespace DFF\Gutenberg\Blocks\PostFeed;

interface Style {
	public function get_items( array $attributes ): array;
	public function render( array $attributes, string $content ): ?string;
}
