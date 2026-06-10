<?php
namespace HeartbeatsChild\Elementor\Widgets\Space_Animation;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Space_Animation {   

    function __construct(){

        add_action( 'elementor/widgets/widgets_registered', function() {
		
			$Space_Animation_Widget =	new Space_Animation_Widget();
		
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( $Space_Animation_Widget );
		}); 
    }
}

 