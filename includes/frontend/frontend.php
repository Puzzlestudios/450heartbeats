<?php
namespace HeartbeatsChild\Frontend;

use HeartbeatsChild\Frontend\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure parent class is loaded before extending
require_once get_template_directory() . '/includes/frontend/frontend.php';

class Frontend extends \Heartbeats\Frontend\Frontend {

	function __construct() {
		parent::__construct();
		$this->Shortcodes = new Shortcodes();
	}
}
