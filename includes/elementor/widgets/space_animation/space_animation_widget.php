<?php
namespace HeartbeatsChild\Elementor\Widgets\Space_Animation;


use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Repeater;

use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Space_Animation_Widget extends Widget_Base {
	protected $posttypes = array();
	protected $categories = array();
	
	public function __construct($data = [], $args = null) {
       	parent::__construct($data, $args);	
    }
	
	public function get_name() {
		return 'space_animation';
	}

	public function get_title() {
		return 'Space Animation Header';
	}

	public function get_icon() {
		return 'eicon-slides';
	}

	public function get_keywords() {
		return [ 'header', 'space' ];
	}

	public function get_script_depends() {

		wp_register_script('space-animation-functions', HEARTBEATS_CHILD_ELEMENTOR_URL .'/space_animation/assets/space-animation.js', array('gsap-scrolltrigger') );

		return array('space-animation-functions');
	}

    public function get_style_depends() {

		wp_register_style( 'space-animation-styles', HEARTBEATS_CHILD_ELEMENTOR_URL .'/space_animation/assets/space-animation.css' );

		return array('space-animation-styles');
    }
	
	protected function register_controls() {
        $this->start_controls_section(
			'section_loopfilter_settings',
			[
				'label' => esc_html__( 'Settings', 'heartbeats' ), 
			]
		);

		$this->add_control(
			'empty_settings_placeholder',
			[
				'label' => 'No settings yet',
				'type' => Controls_Manager::TEXT,
				'default' => 'nothing',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {	
		$settings = $this->get_settings_for_display();

		ob_start();

		?>
        <header id="story" aria-label="Intro">
			<canvas id="stars"></canvas>
			<div class="glow red"></div><div class="glow violet"></div>
			<div id="catchGlow"></div>

			<!-- Rettungsleine -->
			<svg id="lifeline" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
				<path id="lifePath" d="M0,60 H440 L470,60 L488,18 L508,98 L526,60 H720"/>
			</svg>

			<!-- Astronaut: euer Original-Asset, Fallback auf SVG falls offline -->
			<div id="astroWrap" aria-hidden="true">
				<img src="https://www.450heartbeats.com/wp-content/uploads/2021/12/astronaut_03.png"
					alt="" loading="eager" decoding="async"
					onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
				<svg viewBox="0 0 120 160" style="display:none"><use href="#astroShape"/></svg>
			</div>

			<!-- Akt 1 -->
			<div class="stage" id="t1">
				<div>
				<h2>Lost in<br><span class="outline">Digital Space?</span></h2>
				<span class="hint">Scroll, um das Signal zu finden</span>
				</div>
			</div>

			<!-- Akt 2 -->
			<div class="stage" id="t2">
				<h2>Wir fangen<br>dich auf<span class="dot">.</span></h2>
			</div>

			<!-- Akt 3: Marke -->
			<div id="brand">
				<div class="inner">
				<span class="eyebrow">Digitalagentur München — Beyond 360°</span>
				<h1>450<br><span class="outline">Heartbeats<span style="-webkit-text-stroke:0;color:var(--signal)">.</span></span></h1>
				<p class="sub">Design, Technologie und Kommunikation im Takt deines Business. Senior-Expertise statt Agentur-Standard — seit 2013.</p>
				<div class="actions">
					<a class="btn btn-primary magnetic" href="#work">Projekte entdecken <span class="arrow">→</span></a>
					<a class="btn btn-ghost magnetic" href="#landing">Intro Call vereinbaren</a>
				</div>
				</div>
			</div>
			</header>
		<?php

		$content = ob_get_clean();
		
        echo $content;

    }

	
}