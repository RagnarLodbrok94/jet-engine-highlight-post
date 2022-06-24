<?php
/**
 * Plugin Name: JetEngine - Highlight post by date
 * Plugin URI: #
 * Description: Among all the query items, finds posts whose date in the meta field is greater than the current one and makes it possible to style.
 * Version:     1.0.0
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

use Elementor\Controls_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Jet_Engine_Highlight_Post {

	public function __construct() {

		add_action( 'init', array( $this, 'setup' ) );
		add_filter( 'jet-engine/listing/item-classes', array(
			$this,
			'get_classes'
		), 10, 2 );

		add_action( 'elementor/element/jet-listing-grid/section_caption_style/after_section_end', [
			$this,
			'register_jet_engine_highlight_post_controls'
		], 10, 2 );

	}

	public function setup() {

		if ( ! defined( 'JET_ENGINE_HIGHLIGHT_POST_SCHEDULED_DATE' ) ) {
			define( 'JET_ENGINE_HIGHLIGHT_POST_SCHEDULED_DATE', 'scheduled_post_date' );
		}

		if ( ! defined( 'JET_ENGINE_HIGHLIGHT_POST_CLASS' ) ) {
			define( 'JET_ENGINE_HIGHLIGHT_POST_CLASS', 'jet-listing-grid__item-highlight' );
		}
	}

	public function get_classes( $classes, $post_obj ) {
		$current_time = current_time( 'U' );

		switch ( get_class( $post_obj ) ) {
			case \WP_Post::class:
			case \WP_Term::class:
			case \WP_User::class:
				$scheduled_post_date = jet_engine()->listings->data->get_meta( JET_ENGINE_HIGHLIGHT_POST_SCHEDULED_DATE, $post_obj );
				break;
			default:
				$scheduled_post_date = jet_engine()->listings->data->get_prop( JET_ENGINE_HIGHLIGHT_POST_SCHEDULED_DATE, $post_obj );
		}

		if ( ! Jet_Engine_Tools::is_valid_timestamp( $scheduled_post_date ) ) {
			$scheduled_post_date = strtotime( $scheduled_post_date );
		}

		if ( $current_time < $scheduled_post_date ) {
			$classes[0] = $classes[0] . ' ' . JET_ENGINE_HIGHLIGHT_POST_CLASS;
		}

		return $classes;
	}

	public function register_jet_engine_highlight_post_controls( $obj ) {
		$obj->start_controls_section(
			'section_scheduled_post_styles',
			[
				'label' => esc_html__( 'Scheduled Post', 'jet-engine' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$obj->add_control(
			'scheduled_post_color',
			array(
				'label'     => __( 'Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' . ' .' . JET_ENGINE_HIGHLIGHT_POST_CLASS . ' .elementor-element .jet-listing-dynamic-field .jet-listing-dynamic-field__content' => 'color: {{VALUE}}',
				),
			)
		);

		$obj->add_control(
			'scheduled_post_bg_color',
			array(
				'label'     => __( 'Background Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' . ' .' . JET_ENGINE_HIGHLIGHT_POST_CLASS . ' .elementor-section' => 'background-color: {{VALUE}}',
				),
			)
		);

		$obj->end_controls_section();
	}
}

new Jet_Engine_Highlight_Post();