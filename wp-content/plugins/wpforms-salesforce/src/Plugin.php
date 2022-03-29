<?php

namespace WPFormsSalesforce;

use WPForms\Providers\Loader as ProvidersLoader;

/**
 * Class Plugin that loads the whole plugin.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Provider Core instance.
	 *
	 * @since 1.0.0
	 *
	 * @var \WPFormsSalesforce\Provider\Core
	 */
	public $provider;

	/**
	 * Plugin constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {}

	/**
	 * Get a single instance of the addon.
	 *
	 * @since 1.0.0
	 *
	 * @return \WPFormsSalesforce\Plugin
	 */
	public static function get_instance() {

		static $instance = null;

		if (
			null === $instance ||
			! $instance instanceof self
		) {
			$instance = ( new self() )->init();
		}

		return $instance;
	}

	/**
	 * All the actual plugin loading is done here.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->hooks();

		return $this;
	}

	/**
	 * Hooks.
	 *
	 * @since 1.0.0
	 */
	protected function hooks() {

		add_action( 'wpforms_loaded', [ $this, 'init_components' ] );
		add_action( 'wpforms_updater', [ $this, 'updater' ] );
		add_filter( 'wpforms_helpers_templates_include_html_located', [ $this, 'templates' ], 10, 4 );
	}

	/**
	 * Init components.
	 *
	 * @since 1.0.0
	 */
	public function init_components() {

		// Available to top-tier level license only.
		if ( ! in_array( wpforms_get_license_type(), [ 'elite', 'agency', 'ultimate' ], true ) ) {
			return;
		}

		$this->provider = Provider\Core::get_instance();

		ProvidersLoader::get_instance()->register(
			$this->provider
		);
	}

	/**
	 * Load the plugin updater.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key License key.
	 */
	public function updater( $key ) {

		new \WPForms_Updater(
			[
				'plugin_name' => 'WPForms Salesforce',
				'plugin_slug' => 'wpforms-salesforce',
				'plugin_path' => plugin_basename( WPFORMS_SALESFORCE_FILE ),
				'plugin_url'  => trailingslashit( WPFORMS_SALESFORCE_URL ),
				'remote_url'  => WPFORMS_UPDATER_API,
				'version'     => WPFORMS_SALESFORCE_VERSION,
				'key'         => $key,
			]
		);
	}

	/**
	 * Change a template location.
	 *
	 * @since 1.0.0
	 *
	 * @param string $located  Template location.
	 * @param string $template Template.
	 * @param array  $args     Arguments.
	 * @param bool   $extract  Extract arguments.
	 *
	 * @return string
	 */
	public function templates( $located, $template, $args, $extract ) {

		// Checking if `$template` is an absolute path and passed from this plugin.
		if (
			( 0 === strpos( $template, WPFORMS_SALESFORCE_PATH ) ) &&
			is_readable( $template )
		) {
			return $template;
		}

		return $located;
	}
}
