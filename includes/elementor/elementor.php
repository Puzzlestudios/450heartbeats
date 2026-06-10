<?php
namespace HeartbeatsChild\Elementor;

use HeartbeatsChild\Elementor\Widgets\Space_Animation\Space_Animation;
use HeartbeatsChild\Elementor\Widgets\Animated_Scroll_Text\Animated_Scroll_Text;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Ensure parent class is loaded before extending
require_once get_template_directory() . '/includes/elementor/elementor.php';

class Elementor extends \Heartbeats\Elementor\Elementor {

	function __construct() {
		parent::__construct();

		$this->initElementorModules();
	}

	function initElementorModules() {
		new Space_Animation();
		new Animated_Scroll_Text();
	}
}
