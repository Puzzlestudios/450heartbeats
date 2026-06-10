<?php
namespace HeartbeatsChild\Posttypes;

use HeartbeatsChild\Posttypes\Showcases\Showcases;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure parent class is loaded before extending
require_once get_template_directory() . '/includes/posttypes/posttypes.php';

class Posttypes extends \Heartbeats\Posttypes\Posttypes {

	function __construct() {
		parent::__construct();
		$this->run_child();
	}

	function run_child() {
		new Showcases();
	}
}
