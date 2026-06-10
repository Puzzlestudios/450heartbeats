<?php
namespace HeartbeatsChild\Elementor\Widgets\Animated_Scroll_Text;

use \Elementor\Controls_Manager;
use \Elementor\Widget_Base;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Animated_Scroll_Text_Widget extends Widget_Base {

	public function get_name() {
		return 'animated_scroll_text';
	}

	public function get_title() {
		return 'Animated Scroll Text';
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

	public function get_keywords() {
		return [ 'text', 'animation', 'scroll', 'manifest', 'animated' ];
	}

	public function get_script_depends() {
		wp_register_script(
			'hb-animated-scroll-text',
			HEARTBEATS_CHILD_ELEMENTOR_URL . '/animated_scroll_text/assets/animated-scroll-text.js',
			[ 'gsap-scrolltrigger', 'jquery' ],
			'1.0.0',
			true
		);
		return [ 'hb-animated-scroll-text' ];
	}

	public function get_style_depends() {
		wp_register_style(
			'hb-animated-scroll-text-styles',
			HEARTBEATS_CHILD_ELEMENTOR_URL . '/animated_scroll_text/assets/animated-scroll-text.css',
			[],
			'1.0.0'
		);
		return [ 'hb-animated-scroll-text-styles' ];
	}

	protected function register_controls() {

		// ── Content Tab ─────────────────────────────────────────────────────

		$this->start_controls_section( 'section_editor', [
			'label' => esc_html__( 'Animated Scroll Text', 'heartbeats' ),
		] );

		$this->add_control( 'editor', [
			'label'   => '',
			'type'    => Controls_Manager::WYSIWYG,
			'default' => '<p>' . esc_html__( 'Andere machen Marketing. Wir bauen Signale, die einschlagen. Strategie, Design und Code aus einer Hand — kompromisslos auf Wirkung gebaut.', 'heartbeats' ) . '</p>',
		] );

		$this->end_controls_section();

		$this->start_controls_section( 'section_animation', [
			'label' => esc_html__( 'Animation', 'heartbeats' ),
		] );

		$this->add_control( 'scroll_start', [
			'label'       => esc_html__( 'Scroll Start', 'heartbeats' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'top 75%',
			'description' => esc_html__( 'GSAP ScrollTrigger start. Z.B. "top 75%"', 'heartbeats' ),
		] );

		$this->add_control( 'scroll_end', [
			'label'       => esc_html__( 'Scroll End', 'heartbeats' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => 'bottom 60%',
			'description' => esc_html__( 'GSAP ScrollTrigger end. Z.B. "bottom 60%"', 'heartbeats' ),
		] );

		$this->add_control( 'scrub', [
			'label'      => esc_html__( 'Scrub', 'heartbeats' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'default'    => [ 'size' => 0.4 ],
			'range'      => [ 'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ] ],
			'description' => esc_html__( 'Scroll-Smoothing. 0 = direkt, höher = weicher.', 'heartbeats' ),
		] );

		$this->end_controls_section();

		// ── Style Tab ────────────────────────────────────────────────────────

		$this->start_controls_section( 'section_style', [
			'label' => esc_html__( 'Text', 'heartbeats' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'align', [
			'label'     => esc_html__( 'Alignment', 'elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'start'   => [ 'title' => esc_html__( 'Start', 'elementor' ),     'icon' => 'eicon-text-align-left' ],
				'center'  => [ 'title' => esc_html__( 'Center', 'elementor' ),    'icon' => 'eicon-text-align-center' ],
				'end'     => [ 'title' => esc_html__( 'End', 'elementor' ),       'icon' => 'eicon-text-align-right' ],
				'justify' => [ 'title' => esc_html__( 'Justified', 'elementor' ), 'icon' => 'eicon-text-align-justify' ],
			],
			'selectors' => [ '{{WRAPPER}}' => 'text-align: {{VALUE}};' ],
			'separator' => 'after',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'   => 'typography',
			'global' => [ 'default' => Global_Typography::TYPOGRAPHY_TEXT ],
		] );

		$this->add_group_control( Group_Control_Text_Shadow::get_type(), [
			'name'     => 'text_shadow',
			'selector' => '{{WRAPPER}}',
		] );

		$this->add_responsive_control( 'paragraph_spacing', [
			'label'      => esc_html__( 'Paragraph Spacing', 'elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem', 'vh', 'custom' ],
			'range'      => [ 'px' => [ 'max' => 100 ], 'em' => [ 'min' => 0.1, 'max' => 20 ] ],
			'selectors'  => [ '{{WRAPPER}} p' => 'margin-block-end: {{SIZE}}{{UNIT}}' ],
		] );

		$this->add_control( 'divider_colors', [ 'type' => Controls_Manager::DIVIDER ] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Text Color', 'elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => 'color: {{VALUE}}; --hb-ast-active: {{VALUE}};',
			],
			'global' => [ 'default' => Global_Colors::COLOR_TEXT ],
		] );

		$this->add_control( 'inactive_color', [
			'label'       => esc_html__( 'Inactive Color', 'heartbeats' ),
			'type'        => Controls_Manager::COLOR,
			'selectors'   => [ '{{WRAPPER}}' => '--hb-ast-dim: {{VALUE}};' ],
			'description' => esc_html__( 'Farbe der noch nicht enthüllten Wörter (z.B. rgba mit niedriger Deckkraft).', 'heartbeats' ),
		] );

		$this->add_control( 'accent_color', [
			'label'     => esc_html__( 'Accent Color (Fett)', 'heartbeats' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ff2442',
			'selectors' => [ '{{WRAPPER}}' => '--hb-ast-accent: {{VALUE}};' ],
		] );

		$this->add_control( 'transition_duration', [
			'label'      => esc_html__( 'Transition Duration', 'elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 's', 'ms', 'custom' ],
			'default'    => [ 'unit' => 's', 'size' => 0.4 ],
			'range'      => [ 's' => [ 'min' => 0, 'max' => 2, 'step' => 0.05 ] ],
			'selectors'  => [ '{{WRAPPER}}' => '--hb-ast-duration: {{SIZE}}{{UNIT}};' ],
		] );

		$this->end_controls_section();
	}

	protected function render() {
		$s = $this->get_settings_for_display();

		$content = $this->get_settings_for_display( 'editor' );
		$content = $this->parse_text_editor( $content );

		if ( empty( $content ) ) {
			return;
		}

		$scrub = isset( $s['scrub']['size'] ) ? (float) $s['scrub']['size'] : 0.4;

		$this->add_render_attribute( 'ast-wrap', [
			'class'             => 'hb-animated-scroll-text',
			'data-scroll-start' => esc_attr( $s['scroll_start'] ?? 'top 75%' ),
			'data-scroll-end'   => esc_attr( $s['scroll_end'] ?? 'bottom 60%' ),
			'data-scrub'        => esc_attr( $scrub ),
		] );
		?>
		<div <?php $this->print_render_attribute_string( 'ast-wrap' ); ?>>
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		if ( '' === settings.editor ) { return; }
		var scrub = ( settings.scrub && settings.scrub.size ) ? settings.scrub.size : 0.4;
		#>
		<div class="hb-animated-scroll-text"
			 data-scroll-start="{{ settings.scroll_start }}"
			 data-scroll-end="{{ settings.scroll_end }}"
			 data-scrub="{{ scrub }}">
			{{{ settings.editor }}}
		</div>
		<?php
	}
}
