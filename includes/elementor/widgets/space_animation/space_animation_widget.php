<?php
namespace HeartbeatsChild\Elementor\Widgets\Space_Animation;


use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Repeater;
use \Elementor\Group_Control_Typography;

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

		/* ── Akt 1 ── */
		$this->start_controls_section( 'section_akt1', [
			'label' => esc_html__( 'Akt 1 — Einstieg', 'heartbeats' ),
		] );

		$this->add_control( 't1_line1', [
			'label'   => esc_html__( 'Zeile 1', 'heartbeats' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Lost in',
		] );

		$this->add_control( 't1_line2', [
			'label'       => esc_html__( 'Zeile 2 (outline)', 'heartbeats' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Digital Space?',
		] );

		$this->add_control( 't1_hint', [
			'label'   => esc_html__( 'Scroll-Hinweis', 'heartbeats' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Scroll, um das Signal zu finden',
		] );

		$this->end_controls_section();

		/* ── Akt 2 ── */
		$this->start_controls_section( 'section_akt2', [
			'label' => esc_html__( 'Akt 2 — Der Fang', 'heartbeats' ),
		] );

		$this->add_control( 't2_line1', [
			'label'   => esc_html__( 'Zeile 1', 'heartbeats' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Wir fangen',
		] );

		$this->add_control( 't2_line2', [
			'label'       => esc_html__( 'Zeile 2', 'heartbeats' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'dich auf',
		] );

		$this->end_controls_section();

		/* ── Akt 3 ── */
		$this->start_controls_section( 'section_akt3', [
			'label' => esc_html__( 'Akt 3 — Marke', 'heartbeats' ),
		] );

		$this->add_control( 't3_eyebrow', [
			'label'   => esc_html__( 'Eyebrow', 'heartbeats' ),
			'type'    => Controls_Manager::TEXT,
			'default' => 'Digitalagentur München — Beyond 360°',
		] );

		$this->add_control( 't3_h1_line1', [
			'label'   => esc_html__( 'H1 Zeile 1', 'heartbeats' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '450',
		] );

		$this->add_control( 't3_h1_line2', [
			'label'       => esc_html__( 'H1 Zeile 2 (outline)', 'heartbeats' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'Heartbeats',
		] );

		$this->add_control( 't3_subtext', [
			'label'   => esc_html__( 'Subtext', 'heartbeats' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => 'Design, Technologie und Kommunikation im Takt deines Business. Senior-Expertise statt Agentur-Standard — seit 2013.',
			'rows'    => 3,
		] );

		$this->add_control( 't3_btn1_text', [
			'label'     => esc_html__( 'Button 1 Text', 'heartbeats' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Projekte entdecken',
			'separator' => 'before',
		] );

		$this->add_control( 't3_btn1_url', [
			'label'   => esc_html__( 'Button 1 URL', 'heartbeats' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#work' ],
		] );

		$this->add_control( 't3_btn2_text', [
			'label'     => esc_html__( 'Button 2 Text', 'heartbeats' ),
			'type'      => Controls_Manager::TEXT,
			'default'   => 'Intro Call vereinbaren',
			'separator' => 'before',
		] );

		$this->add_control( 't3_btn2_url', [
			'label'   => esc_html__( 'Button 2 URL', 'heartbeats' ),
			'type'    => Controls_Manager::URL,
			'default' => [ 'url' => '#landing' ],
		] );

		$this->end_controls_section();

		/* ── Typografie ── */
		$this->start_controls_section( 'section_typography', [
			'label' => esc_html__( 'Typografie', 'heartbeats' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'typography_headings',
			'label'    => esc_html__( 'Überschriften', 'heartbeats' ),
			'selector' => '{{WRAPPER}} h2, {{WRAPPER}} h1',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'typography_body',
			'label'    => esc_html__( 'Fließtext', 'heartbeats' ),
			'selector' => '{{WRAPPER}} .sub, {{WRAPPER}} .eyebrow, {{WRAPPER}} .hint',
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$btn1_url = ! empty( $s['t3_btn1_url']['url'] ) ? esc_url( $s['t3_btn1_url']['url'] ) : '#';
		$btn2_url = ! empty( $s['t3_btn2_url']['url'] ) ? esc_url( $s['t3_btn2_url']['url'] ) : '#';
		?>
        <header id="story" aria-label="Intro">
			<canvas id="stars"></canvas>
			<div class="glow red"></div><div class="glow violet"></div>
			<div id="catchGlow"></div>

			<svg id="lifeline" viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true">
				<defs>
					<linearGradient id="lifeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
						<stop offset="0%"   stop-color="#FF2442"/>
						<stop offset="55%"  stop-color="#A82BCE"/>
						<stop offset="100%" stop-color="#6E3BFF"/>
					</linearGradient>
				</defs>
				<path id="lifePath" d="M0,60 H440 L470,60 L488,18 L508,98 L526,60 H720"/>
			</svg>

			<div id="astroWrap" aria-hidden="true">
				<img src="https://www.450heartbeats.com/wp-content/uploads/2021/12/astronaut_03.png"
					alt="" loading="eager" decoding="async"
					onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
				<svg viewBox="0 0 120 160" style="display:none"><use href="#astroShape"/></svg>
			</div>

			<!-- Akt 1 -->
			<div class="stage" id="t1">
				<div>
					<h2><?php echo esc_html( $s['t1_line1'] ); ?><br><span class="outline"><?php echo esc_html( $s['t1_line2'] ); ?></span></h2>
					<span class="hint"><?php echo esc_html( $s['t1_hint'] ); ?></span>
				</div>
			</div>

			<!-- Akt 2 -->
			<div class="stage" id="t2">
				<h2><?php echo esc_html( $s['t2_line1'] ); ?><br><?php echo esc_html( $s['t2_line2'] ); ?><span class="dot">.</span></h2>
			</div>

			<!-- Akt 3: Marke -->
			<div id="brand">
				<div class="inner">
					<span class="eyebrow"><?php echo esc_html( $s['t3_eyebrow'] ); ?></span>
					<h1><?php echo esc_html( $s['t3_h1_line1'] ); ?><br><span class="outline"><?php echo esc_html( $s['t3_h1_line2'] ); ?><span style="-webkit-text-stroke:0;color:var(--signal)">.</span></span></h1>
					<p class="sub"><?php echo esc_html( $s['t3_subtext'] ); ?></p>
					<div class="actions">
						<a class="btn btn-primary magnetic" href="<?php echo $btn1_url; ?>"><?php echo esc_html( $s['t3_btn1_text'] ); ?> <span class="arrow">→</span></a>
						<a class="btn btn-ghost magnetic" href="<?php echo $btn2_url; ?>"><?php echo esc_html( $s['t3_btn2_text'] ); ?></a>
					</div>
				</div>
			</div>
		</header>
		<?php
	}

	
}