<?php
/**
 * Plugin Name: COMPE - WooCommerce Compare Products
 * Plugin URI: https://villatheme.com/extensions/compe-woocommerce-compare-products/
 * Description: The effective way to compare product for WooCommerce
 * Version: 1.1.1
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: compe-woo-compare-products
 * Domain Path: /languages
 * Copyright 2022-2024 VillaTheme.com. All rights reserved.
 * Requires Plugins: woocommerce
 * Requires at least: 5.0
 * Tested up to: 6.5
 * WC requires at least: 7.0
 * WC tested up to: 8.9
 * Requires PHP: 7.0
 */

defined( 'ABSPATH' ) || exit;
if ( ! defined( 'VI_WOO_PRODUCT_COMPARE_VERSION' ) ) {
	define( 'VI_WOO_PRODUCT_COMPARE_VERSION', '1.1.1' );
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Class WOO_PRODUCT_COMPARE_ACTIVE
 */
class WOO_PRODUCT_COMPARE_ACTIVE {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );
		add_action( 'plugins_loaded', array( $this, 'init' ) );

		//Compatible with High-Performance order storage (COT)
		add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
	}

	public function before_woocommerce_init() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}

	public function init() {
		if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
			require_once WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "compe-woo-compare-products" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "support.php";
		}

		$environment = new VillaTheme_Require_Environment( [
				'plugin_name'     => 'COMPE - WooCommerce Compare Products',
				'php_version'     => '7.0',
				'wp_version'      => '5.0',
				'require_plugins' => [
					[
						'slug'             => 'woocommerce',
						'name'             => 'WooCommerce',
						'required_version' => '7.0',
					],
				]
			]
		);

		if ( $environment->has_error() ) {
			return;
		}

		$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "compe-woo-compare-products" . DIRECTORY_SEPARATOR . "includes" . DIRECTORY_SEPARATOR . "define.php";
		require_once $init_file;
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		if ( version_compare( $wp_version, "5.0", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 5.0 or higher." );
		} else {

			$option_exits = get_option( 'woo_product_compare_params' );
			if ( ! isset( $option_exits['wpc_page_compare'] ) ) {
				$the_page_title = 'Product Compare';
				delete_option( "wpc_plugin_page_id" );
				add_option( "wpc_plugin_page_id", '0', '', 'yes' );
				$the_page = self::get_page_compare_by_title( $the_page_title );
				if ( ! $the_page ) {
					// Create post object
					$_p                   = array();
					$_p['post_title']     = $the_page_title;
					$_p['post_content']   = "[wpc_page_compare]";
					$_p['post_status']    = 'publish';
					$_p['post_type']      = 'page';
					$_p['comment_status'] = 'closed';
					$_p['ping_status']    = 'closed';
					$_p['post_category']  = array( 1 );
					// Insert the post into the database
					$the_page_id = wp_insert_post( $_p );
					update_post_meta( $the_page_id, '_wp_page_template', 'template-fullwidth.php' );

				} else {
					$the_page_id           = $the_page->ID;
					$the_page->post_status = 'publish';
					$the_page_id           = wp_update_post( $the_page );

				}

				delete_option( 'wpc_plugin_page_id' );
				add_option( 'wpc_plugin_page_id', $the_page_id );
			} else {
				delete_option( 'wpc_plugin_page_id' );
				add_option( 'wpc_plugin_page_id', $option_exits['wpc_page_compare'] );
			}
		}
	}

	public function get_page_compare_by_title( $title ) {
		global $wpdb;
		if ( $wpdb ) {
			$sql  = $wpdb->prepare(
				"SELECT ID
			FROM $wpdb->posts
			WHERE post_title = %s
			AND post_type = %s",
				$title,
				'page'
			);
			$page = $wpdb->get_var( $sql );// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			if ( $page ) {
				return get_post( $page );
			}
		}

		return '';
	}

	/**
	 * When deactive function will be call
	 */
	public function uninstall() {
//		delete_option('woo_product_compare_params');
	}

	function destroyCookies( $prefix ) {
		if ( isset( $_COOKIE ) ) {
			foreach ( $_COOKIE as $i => $v ) {
				if ( preg_match( "/^$prefix/", $i ) ) {
					setcookie( $i, '', 1 );
					unset( $_COOKIE[ $i ] );
				}
			}
		}
	}
}

new WOO_PRODUCT_COMPARE_ACTIVE();