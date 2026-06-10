<?php
namespace HeartbeatsChild\Elementor\Widgets\Animated_Scroll_Text;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Animated_Scroll_Text {

	function __construct() {
		add_action( 'elementor/widgets/widgets_registered', function () {
			$widget = new Animated_Scroll_Text_Widget();
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( $widget );
		} );
	}
}
