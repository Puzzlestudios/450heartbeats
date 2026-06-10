<?php
namespace HeartbeatsChild\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure parent class is loaded before extending
require_once get_template_directory() . '/includes/frontend/shortcodes.php';

class Shortcodes extends \Heartbeats\Frontend\Shortcodes {

	function __construct() {
		parent::__construct();
		$this->add_shortcodes();
	}

	function add_shortcodes() {
		// Register child-specific shortcodes here
	}
}
