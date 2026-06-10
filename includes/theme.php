<?php
namespace HeartbeatsChild;

use HeartbeatsChild\Posttypes\Posttypes;

use HeartbeatsChild\Elementor\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Theme {

	public static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			do_action( 'HeartbeatsChild/loaded' );
		}

		return self::$instance;
	}

	private function __construct() {
		$this->register_autoloader();

		/**
		 * Hook init to after_setup_theme priority 11.
		 * Child functions.php loads BEFORE parent → parent autoloader not yet
		 * registered at construct time. after_setup_theme fires after both
		 * functions.php files are loaded, so parent classes are available.
		 */
		add_action( 'after_setup_theme', [ $this, 'init' ], 11 );
	}

	private function register_autoloader() {
		require_once HEARTBEATS_CHILD_BASE . '/includes/autoloader.php';
		Autoloader::run();
	}

	public function init() {
		$this->override_acf_json();
		$this->init_cpt_components();

		do_action( 'HeartbeatsChild/init' );

		$elementor_functions 	= new Elementor;
	}

	/**
	 * Override parent ACF JSON paths.
	 * Priority 20 > parent priority 10 → child wins for save_json.
	 */
	private function override_acf_json() {
		add_filter( 'acf/settings/save_json', function() {
			return HEARTBEATS_CHILD_BASE . '/acf-json';
		}, 20 );

		add_filter( 'acf/settings/load_json', function( $paths ) {
			$paths[] = HEARTBEATS_CHILD_BASE . '/acf-json';
			return $paths;
		}, 20 );
	}

	/**
	 * Child-specific post types.
	 * Parent Admin_Interface, Frontend, Elementor already instantiated by parent
	 * theme → do NOT re-instantiate here (would double-register WP hooks).
	 * Extend those via hooks in functions.php or their respective child classes
	 * loaded separately.
	 */
	function init_cpt_components() {
		new Posttypes();
	}
}

Theme::instance();
