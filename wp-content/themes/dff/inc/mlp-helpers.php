<?php

function multilingualpress_get_translations() {
	if ( ! class_exists( '\Inpsyde\MultilingualPress\Framework\Api\TranslationSearchArgs' ) ) {
		return [];
	}

	$code_to_initial = [
		'ar' => 'ع',
		'en' => 'EN',
	];

	$args = \Inpsyde\MultilingualPress\Framework\Api\TranslationSearchArgs::forContext(
		new \Inpsyde\MultilingualPress\Framework\WordpressContext()
		)
		->forSiteId( get_current_blog_id() )
		->includeBase();

	$translations = \Inpsyde\MultilingualPress\resolve(
		\Inpsyde\MultilingualPress\Framework\Api\Translations::class
	)->searchTranslations( $args );

	$languages = array_map(
		function ( $translation ) use ( $code_to_initial ) {
			$language = $translation->language();
			$url      = $translation->remoteUrl();

			return [
				'name'       => $code_to_initial[ $language->isoCode() ],
				'code'       => $language->isoCode(),
				'site'       => $url,
				'is_current' => (int) ( $translation->remoteSiteId() === get_current_blog_id() ),
			];
		},
		$translations
	);

	usort( $languages, function ( $a, $b ) {
		if ( $a['is_current'] === $b['is_current'] ) {
			return 0;
		}

		return $a['is_current'] > $b['is_current'] ? -1 : 1;
	} );
	return $languages;
}

function multilingualpress_get_current_site_lang() {
	$translations = multilingualpress_get_translations();

	return array_find( $translations, function ( $item ) {
		return $item['is_current'];
	} );
}
