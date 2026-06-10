<?php
namespace HeartbeatsChild\Posttypes\Showcases;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Showcases extends \Heartbeats\Posttypes\Abstract_Posttype {

    function __construct(){
		$this->posttype = [
			'slug'               => 'showcases',
			'singular'           => 'Showcase',
			'plural'             => 'Showcases',
			'menu_name'          => 'Showcases',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'taxonomies'		 => ['post_tag'],
			'supports'           => [ 'title' ],
			'menu_icon'          => 'dashicons-admin-users',
            'rewrite'            => [
                'slug'       => 'showcases',
                'with_front' => false,
            ],
			'menu_position'      => 35,
		];

        $this->taxonomies = [
            'kunde' => [
                'singular'      => 'Kunde',
                'plural'        => 'Kunden',
                'post_types'    => [ 'showcases' ],
                'public'        => true,
                'publicly_queryable' => true,
                'query_var'     => 'kunde',
                'rewrite'       => [
                    'slug'       => 'showcases/kunde',
                    'with_front' => false,
                ],
                'custom_labels' => array(
                    'filter_title'  => 'Kunde auswählen'
                ),
            ],
            'branche' => [
                'singular'      => 'Branche',
                'plural'        => 'Branchen',
                'post_types'    => [ 'showcases' ],
                'public'        => true,
                'publicly_queryable' => true,
                'query_var'     => 'branche',
                'rewrite'       => [
                    'slug'       => 'showcases/branche',
                    'with_front' => false,
                ],
                'custom_labels' => array(
                    'filter_title'  => 'Branche auswählen'
                ),
            ],
            'leistung' => [
                'singular'      => 'Leistung',
                'plural'        => 'Leistungen',
                'public'        => true,
                'publicly_queryable' => true,
                'query_var'     => 'leistung',
                'rewrite'       => [
                    'slug'       => 'showcases/leistung',
                    'with_front' => false,
                ],
                'post_types'    => [ 'showcases' ],
                'custom_labels' => array(
                    'filter_title'  => 'Leistung auswählen'
                ),
            ],
        ];

		add_action( 'init', [ $this, 'register_post_type' ], 10, 0 );
		add_action( 'init', [ $this, 'register_taxonomies' ], 10, 0 );
        add_action( 'init', [ $this, 'register_custom_rewrite_rules' ], 20, 0 );
    }

    public function register_custom_rewrite_rules() {
        add_rewrite_rule( '^showcases/branche/([^/]+)/?$', 'index.php?taxonomy=branche&term=$matches[1]', 'top' );
        add_rewrite_rule( '^showcases/kunde/([^/]+)/?$', 'index.php?taxonomy=kunde&term=$matches[1]', 'top' );
        add_rewrite_rule( '^showcases/leistung/([^/]+)/?$', 'index.php?taxonomy=leistung&term=$matches[1]', 'top' );
    }
}