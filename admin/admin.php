<?php
/*
Class Name: VI_WOO_PRODUCT_COMPARE_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_PRODUCT_COMPARE_Admin_Admin {
	protected $settings;

	function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		add_filter(
			'plugin_action_links_compe-woo-compare-products/compe-woo-compare-products.php', array(
				$this,
				'settings_link'
			)
		);
		$this->settings = new VI_WOO_PRODUCT_COMPARE_DATA();
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=compe-woo-compare-products" title="' . esc_html__( 'Compe', 'compe-woo-compare-products' ) . '">' . esc_html__( 'Compe', 'compe-woo-compare-products' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		If ( version_compare( $wp_version, "2.9", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 2.9 or higher." );
		}
	}

	/**
	 * Function init when run plugin+
	 */
	function init() {
		load_plugin_textdomain( 'compe-woo-compare-products' );
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/compe/',
					'docs'      => 'http://docs.villatheme.com/?item=compe',
					'review'    => 'https://wordpress.org/support/plugin/compe-woo-compare-products/reviews/?rate=5#rate-response',
//					'pro_url'   => 'https://1.envato.market/DzJ12',
					'css'       => VI_WOO_PRODUCT_COMPARE_CSS,
					'image'     => VI_WOO_PRODUCT_COMPARE_IMAGES,
					'slug'      => 'compe-woo-compare-products',
					'menu_slug' => 'compe-woo-compare-products',
					'survey_url' => 'https://script.google.com/macros/s/AKfycbxf4YtS23qJqBkdqZv5o_g2HhdLz4cHgoj3RoAqIJYV9_KutSB_Hd9TjRegJKB5dT1qZQ/exec',
					'version'   => VI_WOO_PRODUCT_COMPARE_VERSION
				)
			);
		}
	}

	/**
	 * load Language translate
	 */
	function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'compe-woo-compare-products' );
		// Admin Locale
		if ( is_admin() ) {
			load_textdomain( 'compe-woo-compare-products', VI_WOO_PRODUCT_COMPARE_LANGUAGES . "compe-woo-compare-products-$locale.mo" );
		}

		// Global + Frontend Locale
		load_textdomain( 'compe-woo-compare-products', VI_WOO_PRODUCT_COMPARE_LANGUAGES . "compe-woo-compare-products-$locale.mo" );
		load_plugin_textdomain( 'compe-woo-compare-products', false, VI_WOO_PRODUCT_COMPARE_LANGUAGES );
	}

}