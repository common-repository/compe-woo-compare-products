<?php
/*
Class Name: VI_WOO_PRODUCT_COMPARE_Frontend_Frontend
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_PRODUCT_COMPARE_Frontend_Frontend {
	protected $settings;
	protected $widget;


	public function __construct() {
		$this->settings = new VI_WOO_PRODUCT_COMPARE_DATA();
		$this->widget   = new VI_WOO_PRODUCT_COMPARE_Admin_Widget();
		add_action( 'init', array( $this, 'wpc_init_compare_frontend' ) );
		add_action( 'wp_footer', array( $this, 'wpc_footer_setting' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wpc_wp_enqueue_scripts' ), 20 );

		//shortCode
		add_shortcode( 'wpc_sc_archive', array( $this, 'wpc_sc_archive' ) );
		add_shortcode( 'wpc_sc_single', array( $this, 'wpc_sc_single' ) );
		add_shortcode( 'wpc_page_compare', array( $this, 'wpc_load_page_compare' ) );

		// ajax load bar items
		add_action( 'wp_ajax_wpc_load_compare_bar', array( $this, 'wpc_load_compare_bar' ) );
		add_action( 'wp_ajax_nopriv_wpc_load_compare_bar', array( $this, 'wpc_load_compare_bar' ) );
		// ajax load compare table
		add_action( 'wp_ajax_wpc_load_compare_table', array( $this, 'wpc_load_compare_table' ) );
		add_action( 'wp_ajax_nopriv_wpc_load_compare_table', array( $this, 'wpc_load_compare_table' ) );

		add_action( 'wp_ajax_wpc_load_data_json', array( $this, 'wpc_load_data_json' ) );
		add_action( 'wp_ajax_nopriv_wpc_load_data_json', array( $this, 'wpc_load_data_json' ) );

		//Ajax add to cart
		add_action( 'wp_ajax_wpc_variation_cart', array( $this, 'wpc_variation_cart' ) );
		add_action( 'wp_ajax_nopriv_wpc_variation_cart', array( $this, 'wpc_variation_cart' ) );
	}

	/**
	 *  Include style for wp page
	 */
	function wpc_wp_enqueue_scripts() {
		if ( is_admin() || is_customize_preview() ) {
			return;
		}
		global $post;
		$wpc_not_page_compare = 0;
		$wpc_limit_notice     = $this->settings->get_params( 'wpc_limit_notice' );
		$scripts_fields       = json_decode( $this->settings->get_params( 'wpc_blocks' ) );
		$scripts_param        = array();
		foreach ( $scripts_fields as $fields_k => $fields_v ) {
			array_push( $scripts_param, $fields_v[0] );
		}
		$scripts_param = array_map( 'esc_attr', $scripts_param );

		$wpc_attributes       = wc_get_attribute_taxonomies();
		$wpc_attributes_names = array();
		if ( is_array( $wpc_attributes ) && count( $wpc_attributes ) ) {
			foreach ( $wpc_attributes as $row_key => $row_value ) {
				array_push( $wpc_attributes_names, $row_value->attribute_name );
			}
		}
		$wpc_attributes_names = array_map( 'esc_attr', $wpc_attributes_names );

		$wpc_products_session = '';
		$wooCompare_cookie    = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );
		if ( isset( $_COOKIE[ $wooCompare_cookie ] ) && ! empty( $_COOKIE[ $wooCompare_cookie ] ) ) {
			if ( is_user_logged_in() ) {
				update_user_meta( get_current_user_id(), 'wooCompare_products', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			}
			$wpc_products_session = explode( ',', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
		}
		$wpc_session_data = [];
		ob_start();
		$this->wpc_load_table_items( $wpc_products_session );
		array_push( $wpc_session_data, ob_get_clean() );
		ob_start();
		$this->wpc_load_compare_bar_items();
		array_push( $wpc_session_data, ob_get_clean() );

		$wpc_conditional_tag = true;
		$logic_value         = $this->settings->get_params( 'wpc_conditional_tag' );
		if ( $logic_value ) {
			if ( stristr( $logic_value, "return" ) === false ) {
				$logic_value = "return (" . $logic_value . ");";
			}

			try {
				$logic_show = eval( $logic_value );// phpcs:ignore Generic.PHP.ForbiddenFunctions.Found
			} catch ( \Error $e ) {
				trigger_error( esc_html( $e->getMessage() ), E_USER_WARNING );
			} catch ( \Exception $e ) {
				trigger_error( esc_html( $e->getMessage() ), E_USER_WARNING );
			}
			if ( ! $logic_show ) {
				$wpc_conditional_tag = false;
			}
		}

		$wpc_sticky_element = $this->settings->get_params( 'wpc_sticky_elements' );
		$wpc_sticky_element = $this->wpc_recursive_sanitize_text_field( $wpc_sticky_element, 1 );

		if ( empty( $post ) ) {
			$wpc_not_page_compare = 1;
		} else {
			if ( $this->settings->get_params( 'wpc_page_compare' ) != '' ) {
				$post->ID == $this->settings->get_params( 'wpc_page_compare' ) ? $wpc_not_page_compare = 0 : $wpc_not_page_compare = 1;
			} else {
				$post->ID == get_option( 'wpc_plugin_page_id' ) ? $wpc_not_page_compare = 0 : $wpc_not_page_compare = 1;
			}
		}

		if ( empty( $wpc_limit_notice ) ) {
			$wpc_limit_notice = esc_html__( 'You can add a maximum of {limit} products to the compare table.', 'compe-woo-compare-products' );
		}
		$suffix = WP_DEBUG ? '' : 'min.';
		wp_enqueue_style( 'compe-woo-compare-products-icons-main', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc_icon_compare.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
		wp_enqueue_style( 'compe-woo-compare-products-icons-custom-main', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc_icon_custom.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
		wp_enqueue_style( 'compe-woo-compare-products-frontend', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc-frontend.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
		wp_enqueue_style( 'compe-woo-compare-products-popup-effect', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc-transition.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
		wp_enqueue_style( 'dashicons' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		wp_enqueue_script( 'compe-woo-compare-products-frontend', VI_WOO_PRODUCT_COMPARE_JS . 'wpc-frontend.' . $suffix . 'js', array( 'jquery' ), VI_WOO_PRODUCT_COMPARE_VERSION, false );
		wp_localize_script( 'compe-woo-compare-products-frontend', 'wooCompareVars', array(
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'user_id'               => md5( 'wpc' . get_current_user_id() ),
				'page_url'              => self::wpc_get_page_url(),
				'fields_arr'            => $scripts_param,
				'element_allow'         => esc_attr( $wpc_not_page_compare ),
				'slide_product_num'     => esc_attr( $this->settings->get_params( 'wpc_slide_product_num' ) ),
				'slide_product_num_mob' => esc_attr( $this->settings->get_params( 'wpc_slide_product_num_mob' ) ),
				'post_id'               => is_front_page() ? esc_attr( 1 ) : esc_attr( 0 ),
				'session_data'          => $wpc_session_data,
				'conditional_tag'       => esc_attr( $wpc_conditional_tag ),
				'is_checkout'           => esc_attr( is_checkout() ),
				'attribute_list'        => $wpc_attributes_names,

				'popup_compare'     => esc_attr( $this->settings->get_params( 'wpc_popup_compare' ) ),
				'popup_transition'  => esc_attr( $this->settings->get_params( 'wpc_popup_transition' ) ),
				'open_added_action' => esc_attr( $this->settings->get_params( 'wpc_btn_redirect' ) ),
				'open_sidebar'      => esc_attr( $this->settings->get_params( 'wpc_open_sidebar' ) ),

				'floating_icon_open'     => esc_attr( $this->settings->get_params( 'wpc_floating_icon_open' ) ),
				'floating_icon_position' => ( $this->settings->get_params( 'wpc_floating_icon_position' ) == 'bottom-left' ||
				                              $this->settings->get_params( 'wpc_floating_icon_position' ) == 'top-left' ) ? esc_attr( 'left' ) : esc_attr( 'right' ),
				'export_file'            => esc_attr( $this->settings->get_params( 'wpc_export_file' ) ),
				'sticky_elements'        => $this->settings->get_params( 'wpc_sticky_elements' ),

				'remove_mode'           => esc_attr( $this->settings->get_params( 'wpc_remove_mode' ) ),
				'remove_all_notice'     => esc_html__( 'Do you want to remove all products from the compare list?', 'compe-woo-compare-products' ),
				'remove_single_notice'  => esc_html__( 'Do you want to remove this product from the compare list?', 'compe-woo-compare-products' ),
				'hide_empty'            => esc_attr( $this->settings->get_params( 'wpc_side_bar_hide_empty' ) ),
				'click_sidebar_outside' => esc_attr( $this->settings->get_params( 'wpc_side_bar_outside_close' ) ),

				'limit'                     => esc_attr( $this->settings->get_params( 'wpc_limit_compare' ) ),
				'limit_notice'              => esc_attr( $wpc_limit_notice ),
				'button_achieve_background' => esc_attr( $this->settings->get_params( 'wpc_btn_archive_background' ) ),
				'button_achieve_hover'      => esc_attr( $this->settings->get_params( 'wpc_btn_archive_hover' ) ),
				'button_achieve_added'      => esc_attr( $this->settings->get_params( 'wpc_btn_archive_added' ) ),
				'button_achieve_color'      => esc_attr( $this->settings->get_params( 'wpc_btn_archive_color' ) ),
				'button_achieve_pos'        => $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_left' ||
				                               $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_right' ? 0 : 1,

				'alternate_type'     => esc_attr( $this->settings->get_params( 'wpc_table_alternating_type' ) ),
				'alternate_row'      => esc_attr( $this->settings->get_params( 'wpc_table_alternating_row' ) ),
				'alternate_row_odd'  => esc_attr( $this->settings->get_params( 'wpc_table_alternating_row_odd' ) ),
				'alternate_row_even' => esc_attr( $this->settings->get_params( 'wpc_table_alternating_row_even' ) ),
				'alternate_col'      => esc_attr( $this->settings->get_params( 'wpc_table_alternating_col' ) ),
				'alternate_col_odd'  => esc_attr( $this->settings->get_params( 'wpc_table_alternating_col_odd' ) ),
				'alternate_col_even' => esc_attr( $this->settings->get_params( 'wpc_table_alternating_col_even' ) ),

				'table_expand_text' => esc_attr( $this->settings->get_params( 'wpc_table_expand_text' ) ),
				'table_shrink_text' => esc_attr( $this->settings->get_params( 'wpc_table_shrink_text' ) ),

				'button_background' => esc_attr( $this->settings->get_params( 'wpc_btn_compare_background' ) ),
				'button_hover'      => esc_attr( $this->settings->get_params( 'wpc_btn_compare_hover' ) ),
				'button_added'      => esc_attr( $this->settings->get_params( 'wpc_btn_compare_added' ) ),
				'button_icon'       => esc_attr( $this->settings->get_params( 'wpc_btn_compare_icon' ) ),
				'button_text'       => esc_attr( $this->settings->get_params( 'wpc_btn_compare_text' ) ),
				'button_text_added' => $this->settings->get_params( 'wpc_remove_mode' ) != 1 ?
					esc_attr( $this->settings->get_params( 'wpc_btn_added_text' ) ) : esc_attr( $this->settings->get_params( 'wpc_text_remove' ) ),
				'nonce'             => wp_create_nonce( 'wpc-nonce' ),
			)
		);
		$css = '';
//		button single
		$css .= '.type-product .entry-summary .woo-compare-btn.woo-compare-single i {';
		$css .= '}';
		$css .= '.type-product .entry-summary .woo-compare-btn.woo-compare-single {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_btn_compare_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_btn_compare_color' ) ) . ';';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_btn_compare_font_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.type-product .entry-summary .woo-compare-btn.woo-compare-single.woo-compare-btn-added {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_btn_compare_added' ) ) . ';';
		$css .= '}';
//		button archive
		$css .= '.type-product .woo-compare-btn.woo-compare-icon {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_background' ) ) . ' ;';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_color' ) ) . ';';
		if ( ! ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_right' ) && ! ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_left' ) ) {
			$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
//			$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
		}
		$css .= '}';
		$css .= '.type-product .woo-compare-btn.woo-compare-icon i {';
		if ( ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_right' ) || ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_left' ) ) {
			$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
			$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
		}
		$css .= '}';
		$css .= '.type-product .woo-compare-btn.woo-compare-icon.woo-compare-btn-added{';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_added' ) ) . ';';
		$css .= '}';
//		floating icon
		$css .= '.woo-compare-floating-icon {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_size' ) ) . 'px;';
		$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_size' ) ) . 'px;';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-floating-icon-wrap {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_background' ) ) . ';';
		$css .= 'border-radius:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_border' ) ) . 'px;';
		$css .= '}';
//		floating icon
		$css       .= '.woo-compare-area .woo-compare-inner .woo-compare-bar {';
		$css       .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_side_bar_background_color' ) ) . ';';
		$css       .= '}';
		$css       .= '.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn {';
		$css       .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_side_bar_button_background' ) ) . ';';
		$css       .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_side_bar_button_color' ) ) . ';';
		$css       .= '}';
		$css       .= '.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn-icon-wrapper {';
		$rgb_color = $this->wpc_hexToRgb( $this->settings->get_params( 'wpc_side_bar_button_background' ), 0.5 );
		$css       .= 'background-color:' . esc_attr( 'rgba(' . $rgb_color[0] . ',' . $rgb_color[1] . ',' . $rgb_color[2] . ',' . $rgb_color[3] . ')' ) . ';';
		$css       .= '}';
		$css       .= '.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn-icon-wrapper .woo-compare-bar-btn-icon-inner span {';
		$css       .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_side_bar_button_color' ) ) . ';';
		$css       .= '}';
		//widget
		$css .= '.widget_wpc-widget .widget-title {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_size' ) ) . 'px;';
		$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_size' ) ) . 'px;';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_color' ) ) . ';';
		$css .= '}';
		$css .= '.widget_wpc-widget .widgettitle {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_size' ) ) . 'px !important;';
		$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_size' ) ) . 'px;';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_color' ) ) . ';';
		$css .= '}';
		$css .= '.widget_wpc-widget .woo-compare-widget-btn {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_widget_compare_text_size' ) ) . 'px;';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_widget_compare_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_widget_compare_text_color' ) ) . ';';
		$css .= '}';
		$css .= '.widget_wpc-widget .remove-btn {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_widget_clear_size' ) ) . 'px;';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_widget_clear_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_widget_clear_color' ) ) . ';';
		$css .= '}';

		//table buttons
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting {';
		$css .= 'width:' . esc_attr( $this->settings->get_params( 'wpc_table_select_size' ) ) . '%;';
		$css .= 'border-radius:' . esc_attr( $this->settings->get_params( 'wpc_table_select_border_radius' ) ) . 'px;';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_select_font_size' ) ) . 'px;';
		$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_table_select_font_size' ) ) . 'px;';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_select_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_select_color' ) ) . ';';
		$css .= '}';

		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_clear_font_size' ) ) . 'px;';
		$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_table_clear_font_size' ) ) . 'px;';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_clear_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_clear_color' ) ) . ';';
		$css .= 'width:' . esc_attr( $this->settings->get_params( 'wpc_table_clear_size' ) ) . '%;';
		$css .= 'border-radius:' . esc_attr( $this->settings->get_params( 'wpc_table_clear_border_radius' ) ) . 'px;';
		$css .= '}';

		//table search
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-input {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result .item-inner .item-name a {';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_btn_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button span {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_btn_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button .woo-compare-table-search-arrow {';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_search_btn_color' ) ) . ';';
		$css .= '}';

		//table fields header
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) ) . 'px;';
		$css .= 'text-align:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_align' ) ) . ';';
		$css .= 'font-weight:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_font_weight' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_background' ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title form table td select {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .add_to_cart_inline .added_to_cart {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title form .variations_button .added_to_cart {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) ) . 'px;';
		$css .= '}';

		//table inner header
		$css .= '.woo-compare-table .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart p.add_to_cart_inline {';
		$css .= 'justify-content:' . esc_attr( $this->settings->get_params( 'wpc_table_header_text_align' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title {';
		$css .= 'text-align:' . esc_attr( $this->settings->get_params( 'wpc_table_header_text_align' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-setting {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name a {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_header_title_size' ) ) . 'px;';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_title_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart p a.button {';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_color' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title form .variations_button button.single_add_to_cart_button {';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_color' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title form .variations_button .added_to_cart {';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_color' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .add_to_cart_inline .added_to_cart {';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_color' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_header_cart_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-product-stage .woo-compare-image-wrap img {';
		$css .= 'max-height:' . esc_attr( $this->settings->get_params( 'wpc_table_header_image_size' ) ) . 'px;';
		$css .= '}';

		$css .= '#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-tr-title {';
		$css .= 'border-right-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= '}';
		$css .= '#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-cell {';
		$css .= 'border-right-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= '}';
		$css .= '#vi-woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column {';
		$css .= 'border-width:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_size' ) ) . 'px;';
		$css .= 'border-right-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_color' ) ) . ';';
		$css .= '}';
		$css .= '#vi-woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header {';
		$css .= 'border-right-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header {';
		$css .= 'border-width:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_size' ) ) . 'px;';
		$css .= 'border-left-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-cell {';
		$css .= 'border-width:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_size' ) ) . 'px;';
		$css .= 'border-bottom-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_color' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header .woo-compare-cell {';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header .woo-compare-tr-title {';
		$css .= 'border-top-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-tr-title {';
		$css .= 'border-width:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_size' ) ) . 'px;';
		$css .= 'border-bottom-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-top-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_color' ) ) . ';';
		$css .= '}';

		//search border
		$css .= '#vi-woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner {';
		$css .= 'border-left-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= '}';
//		$css .= '#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner.woo-compare-open {';
//		$css .= 'border-left-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
//		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner {';
		$css .= 'border-right-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-top-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-bottom-style:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_style' ) ) . ';';
		$css .= 'border-width:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_size' ) ) . 'px;';
		$css .= 'border-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_border_color' ) ) . ';';
		$css .= '}';

		//table content
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result .item-inner .item-name {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-input {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_size' ) ) . 'px;';
		$css .= 'text-align:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_align' ) ) . ';';
		$css .= 'text-align:' . '-webkit-' . esc_attr( esc_attr( $this->settings->get_params( 'wpc_table_content_content_align' ) ) ) . ';';
		$css .= 'color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_color' ) ) . ';';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell.woo-compare-tr-rating {';
		switch ( $this->settings->get_params( 'wpc_table_content_content_align' ) ) {
			case 'left':
				$css .= 'justify-content: flex-start;';
				break;
			case 'right':
				$css .= 'justify-content: flex-end;';
				break;
			default:
				$css .= 'justify-content:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_align' ) ) . ';';
				break;
		}
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell table tr th {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell table tr td {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-header .woo-compare-table-setting .woo-compare-popup-table-setting-fields,';
		$css .= '.woo-compare-table .woo-compare-table-header .woo-compare-table-setting table th, .woo-compare-table .woo-compare-table-header .woo-compare-table-setting table td {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ';';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-setting .woo-compare-popup-table-setting-fields,';
		$css .= '.woo-compare-table .woo-compare-table-setting table th, .woo-compare-table .woo-compare-table-setting table td {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ' !important;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner {';
		$css .= 'background-color:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_background' ) ) . ';';
		$css .= '}';

		if ( in_array( $this->settings->get_params( 'wpc_btn_archive_pos' ), array(
				'on_image_left',
				'on_image_right'
			) ) && $this->settings->get_params( 'wpc_btn_archive_enable_hover' ) ) {
			$css .= '.woo-compare-btn.woo-compare-icon.woo-compare-btn-inside {';
			$css .= 'visibility: hidden';
			$css .= '}';
			$css .= '.product.type-product:hover .woo-compare-btn.woo-compare-icon.woo-compare-btn-inside {';
			$css .= 'visibility: visible';
			$css .= '}';
		}

		$css .= '@media screen and (max-width: 600px) {';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result .item-inner .item-name {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-input {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_content_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name a {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_header_title_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title form table td select {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart p a.button {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .add_to_cart_inline .added_to_cart {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title form .variations_button .added_to_cart {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_table_content_header_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '.woo-compare-floating-icon {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_size' ) - 4 ) . 'px;';
		$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_floating_icon_size' ) - 4 ) . 'px;';
		$css .= '}';
		$css .= '}';
		$css .= esc_attr( $this->settings->get_params( 'wpc_custom_css' ) );

		wp_add_inline_style( 'compe-woo-compare-products-frontend', esc_attr( $css ) );
	}

	function wpc_recursive_sanitize_text_field( $array, $type = 0 ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->wpc_recursive_sanitize_text_field( $value );
			} else {
				switch ( $type ) {
					case 1:
						$value = esc_attr( $value );
						break;
					case 2:
						$value = esc_sql( $value );
						break;
					default:
						$value = esc_html( $value );
						break;
				}
			}
		}

		return $array;
	}

	/**
	 *  Check setting and call init compare elements
	 *
	 * @param bool $product_id
	 * @param array $args
	 */
	public function wpc_init_compare_frontend( $product_id = false, $args = array() ) {
		global $product;
		$wpc_btn_archive_pos = $this->settings->get_params( 'wpc_btn_archive_pos' );
		$wpc_btn_single_pos  = $this->settings->get_params( 'wpc_btn_single_pos' );
		if ( $this->settings->get_params( 'wpc_list_product_mode' ) == 1 ) {
			switch ( $wpc_btn_archive_pos ) {
				case 'on_image_left':
				case 'on_image_right':
					add_action( 'woocommerce_before_shop_loop_item', array( $this, 'wpc_create_archive_btn' ), 7 );
					break;
				case 'after_title':
					add_action( 'woocommerce_shop_loop_item_title', array( $this, 'wpc_create_archive_btn' ), 11 );
					break;
				case 'after_rating':
					add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'wpc_create_archive_btn' ), 6 );
					break;
				case 'after_price':
					add_action( 'woocommerce_after_shop_loop_item_title', array(
						$this,
						'wpc_create_archive_btn'
					), 11 );
					break;
				case 'before_add_to_cart':
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wpc_create_archive_btn' ), 9 );
					break;
				default:
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wpc_create_archive_btn' ), 11 );
					break;
			}
		}

		if ( ! empty( $wpc_btn_single_pos ) ) {
			add_action( 'woocommerce_single_product_summary', array(
				$this,
				'wpc_create_single_btn'
			), (int) $wpc_btn_single_pos );
		}
	}

	function wpc_load_compare_table() {
		check_ajax_referer( 'wpc-nonce', 'nonce' );

		$wpc_products = array();
		$wpc_count    = 0;
		if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
			$wpc_products = explode( ',', sanitize_text_field( $_POST['products'] ) );
		} else {
			$wooCompare_cookie = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );

			if ( isset( $_COOKIE[ $wooCompare_cookie ] ) && ! empty( $_COOKIE[ $wooCompare_cookie ] ) ) {
				if ( is_user_logged_in() ) {
					update_user_meta( get_current_user_id(), 'wooCompare_products', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
				}
				$wpc_products = explode( ',', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			}
		}
		if ( isset( $_POST['stage'] ) ) {
			$wpc_count = absint( $_POST['stage'] );
		}
		if ( isset( $_POST['caller'] ) && ( sanitize_title( $_POST['caller'] ) == 'customize' ) ) {
			$this->wpc_load_table_items( $wpc_products, true );
		} else {
			$this->wpc_load_table_items( $wpc_products, false, $wpc_count );
		}
		wp_die();
	}

	function wpc_load_data_json() {
		check_ajax_referer( 'wpc-nonce', 'nonce' );
		$wpc_products = array();
		$wpc_count    = 0;
		$data_widget  = '';
		$data_bar     = '';
		$data_item    = '';
		if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
			$wpc_products = explode( ',', sanitize_text_field( $_POST['products'] ) );
		} else {
			$wooCompare_cookie = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );

			if ( isset( $_COOKIE[ $wooCompare_cookie ] ) && ! empty( $_COOKIE[ $wooCompare_cookie ] ) ) {
				if ( is_user_logged_in() ) {
					update_user_meta( get_current_user_id(), 'wooCompare_products', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
				}
				$wpc_products = explode( ',', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			}
		}
		if ( isset( $_POST['stage'] ) ) {
			$wpc_count = absint( $_POST['stage'] );
		}

		//load widget
		ob_start();
		$this->widget->list_products_html( explode( ',', sanitize_text_field( $_POST['products'] ) ) );
		$data_widget = ob_get_clean();

		//load sidebar
		if ( $this->settings->get_params( 'wpc_open_sidebar' ) ) {
			ob_start();
			$this->wpc_load_compare_bar_items();
			$data_bar = ob_get_clean();
		}

		//load table
		if ( isset( $_POST['caller'] ) && ( sanitize_title( $_POST['caller'] ) == 'customize' ) ) {
			ob_start();
			$this->wpc_load_table_items( $wpc_products, true );
			$data_item = ob_get_clean();
		} else {
			ob_start();
			$this->wpc_load_table_items( $wpc_products, false, $wpc_count );
			$data_item = ob_get_clean();
		}

		$return_arr = [];
		array_push( $return_arr, $data_widget );
		array_push( $return_arr, $data_bar );
		array_push( $return_arr, $data_item );
		wp_send_json( $return_arr );
		wp_die();
	}

	/**
	 *  Create compare button in list
	 */
	function wpc_create_archive_btn() {
		do_shortcode( '[wpc_sc_archive]' );
	}

	/**
	 *  Create compare button on single product
	 */
	function wpc_create_single_btn() {
		do_shortcode( '[wpc_sc_single]' );
	}

	/**
	 *  Create shortCode for compare button on single product
	 *
	 * @param $atts
	 */
	function wpc_sc_single( $atts ) {

		$atts = shortcode_atts( array(
			'id'       => null,
			'position' => null,
		), $atts );

		if ( ! $atts['id'] ) {
			global $product;
			$atts['id'] = $product->get_id();
		}

		if ( $atts['id'] ) {
			$button_text = $this->settings->get_params( 'wpc_btn_compare_text' );
			if ( empty( $button_text ) ) {
				$button_text = esc_html( 'Compare' );
			}
			$data_pos = '';
			if ( $atts['position'] ) {
				$data_pos = $atts['position'];
			}
			$button_icon = $this->settings->get_params( 'wpc_btn_compare_icon' );

			if ( empty( $button_icon ) ) {
				$button_icon = esc_attr( 'wpc_icon_compare-share-files' );
			} ?>
            <button class="button woo-compare-btn woo-compare-btn-<?php echo esc_attr( $atts['id'] ) ?> woo-compare-single"
                    data-id="<?php echo esc_attr( $atts['id'] ) ?>"
                    data-position="<?php if ( ! empty( $data_pos ) ) {
				        echo esc_attr( $data_pos );
			        } else {
				        echo esc_attr( $this->settings->get_params( 'wpc_btn_single_pos' ) );
			        } ?>">
                <i class="<?php echo esc_attr( $button_icon ) ?>"></i><?php echo esc_html( $button_text ) ?>
            </button>
			<?php
		}

		return;
	}

	/**
	 *  Create shortCode for compare button on list product
	 *
	 * @param $atts
	 */
	function wpc_sc_archive( $atts ) {

		$atts = shortcode_atts( array(
			'id'       => null,
			'position' => null,
		), $atts );

		if ( ! $atts['id'] ) {
			global $product;
			$atts['id'] = $product->get_id();
		}

		if ( $atts['id'] ) {
			$button_text = $this->settings->get_params( 'wpc_btn_compare_text' );
			if ( empty( $button_text ) ) {
				$button_text = esc_html( 'Compare' );
			}

			$button_icon  = $this->settings->get_params( 'wpc_btn_compare_icon' );
			$pos_class    = '';
			$button_title = '';
			$data_pos     = '';
			if ( $atts['position'] ) {
				if ( $atts['position'] == 'on_image_left' || $atts['position'] == 'on_image_right' ) {
					$pos_class    .= esc_attr( ' woo-compare-btn-inside woo-compare-btn-inside-' ) . esc_attr( $atts['position'] );
					$button_title .= esc_attr( 'Compare' );
				} else {
					$pos_class .= esc_attr( ' woo-compare-btn-' ) . esc_attr( $atts['position'] );
				}
				$data_pos = $atts['position'];
			} else {
				if ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_left' || $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_right' ) {
					$pos_class    .= esc_attr( ' woo-compare-btn-inside woo-compare-btn-inside-' ) . esc_attr( $this->settings->get_params( 'wpc_btn_archive_pos' ) );
					$button_title .= esc_attr( 'Compare' );
				}
				$data_pos = $this->settings->get_params( 'wpc_btn_archive_pos' );
			}
			if ( empty( $button_icon ) ) {
				$button_icon = esc_attr( 'wpc_icon_compare-share-files' );
			} ?>
            <button class="button icon woo-compare-btn woo-compare-btn-<?php echo esc_attr( $atts['id'] );
			echo esc_attr( $pos_class ); ?> woo-compare-icon"
                    data-id="<?php echo esc_attr( $atts['id'] ) ?>" data-position="<?php echo esc_attr( $data_pos ) ?>"
                    title="<?php echo esc_attr( $button_title ); ?>">
                <i class="<?php echo esc_attr( $button_icon ) ?>"></i><?php echo esc_html( $button_text ) ?>
            </button>
			<?php
		}

		return;
	}

	function wpc_load_page_compare( $atts ) {
		global $wp_query;
		$attributes = shortcode_atts(
			array(
				'products' => '',
			),
			$atts
		);

		$wpc_products = array();

		if ( ! $attributes['products'] ) {
			$wooCompare_cookie = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );
			if ( isset( $_COOKIE[ $wooCompare_cookie ] ) && ! empty( $_COOKIE[ $wooCompare_cookie ] ) ) {
				if ( is_user_logged_in() ) {
					update_user_meta( get_current_user_id(), 'wooCompare_products', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
				}
				$wpc_products = explode( ',', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			}
		}
		$post_id = ( isset( $wp_query->queried_object->ID ) ? $wp_query->queried_object->ID : '' );
		if ( $this->settings->get_params( 'wpc_page_compare' ) != '' ) {
			$post_id == $this->settings->get_params( 'wpc_page_compare' ) ? $wpc_not_page_compare = 0 : $wpc_not_page_compare = 1;
		} else {
			$post_id == get_option( 'wpc_plugin_page_id' ) ? $wpc_not_page_compare = 0 : $wpc_not_page_compare = 1;
		}
		if ( $wpc_not_page_compare === 0 ) {
			?>
            <div id="vi-woo-compare-page-table" class="woo-compare-table woo-compare-table-open">
                <div class="woo-compare-table-inner">
                    <div class="woo-compare-slide-prev-contain">
                        <span class="woo-compare-slide-prev wpc_icon_compare-left-arrow"
                              title="<?php esc_attr_e( 'Previous' ); ?>"></span>
                    </div>
                    <div class="woo-compare-slide-next-contain">
                        <span class="woo-compare-slide-next wpc_icon_compare-next"
                              title="<?php esc_attr_e( 'Next' ); ?>"></span>
                    </div>
                    <div class="woo-compare-table-items">
						<?php
						$this->wpc_load_table_items( $wpc_products, false, 0 );
						?>
                    </div>
					<?php
					$this->wpc_load_table_search();
					?>
                </div>
            </div>
			<?php
		}

		return;
	}

	function wpc_load_table_items( $wpc_arr = array(), $wpc_integrity = false, $wpc_count = 0 ) {
		$wpc_table_output     = '';
		$wpc_table_output_sub = '';
		$customize_class      = '';
		$wpc_row_overlay      = array();
		$wpc_products         = array();
		$wpc_products_data    = array();
		$s_fields             = json_decode( $this->settings->get_params( 'wpc_blocks' ) );

		if ( $wpc_integrity ) {
			if ( is_array( $wpc_arr ) && ( count( $wpc_arr ) > 0 ) ) {
				$wpc_products = $wpc_arr;
			} else {
				$customize_class = 'tr-hide';
				$args            = array(
					'posts_per_page' => 3,
					'orderby'        => 'rand',
					'post_type'      => 'product'
				);

				$random_products = get_posts( $args );

				foreach ( $random_products as $post ) : setup_postdata( $post );
					array_push( $wpc_products, $post->ID );
				endforeach;
			}
		} else {
			if ( is_array( $wpc_arr ) && ( count( $wpc_arr ) > 0 ) ) {
				$wpc_products = $wpc_arr;
			}
		}
		foreach ( $s_fields as $fields_k => $fields_v ) {
			if ( $fields_v[2] != 1 ) {
				array_push( $wpc_row_overlay, $fields_v[0] );
			}
		}

		if ( is_array( $wpc_products ) && ( count( $wpc_products ) > 0 ) ) {
			foreach ( $wpc_products as $wpc_product ) {
				global $product;
				$product = wc_get_product( $wpc_product );

				if ( ! $product ) {
					continue;
				}

				$product_name = apply_filters( 'wooCompare_product_name', $product->get_name() );

				if ( $product->get_type() == 'variable' ) {
					$add_to_cart = '';
					$add_to_cart .= '<p class="woo-compare-cart-button-contain"><a class="button woo-compare-cart-button">' . esc_html__( 'Select options', 'compe-woo-compare-products' ) . '</a></p><div class="woo-compare-variant-cart woo-compare-hide">';
					ob_start();
					woocommerce_variable_add_to_cart();
					$add_to_cart  .= ob_get_clean() . '</div>';
					$product_cart = apply_filters( 'wooCompare_product_add_to_cart_variant', $add_to_cart, $product );
				} else {
					$product_cart = do_shortcode( '[add_to_cart id="' . $wpc_product . '"]' );
				}
				ob_start();
				$this->wpc_load_table_item_header( $product, $wpc_integrity, $product_cart, $wpc_product );
				$wpc_products_data[ $wpc_product ]['title'] = ob_get_clean();
				foreach ( $s_fields as $fields_k => $fields_v ) {
					if ( $wpc_integrity || $fields_v[2] ) {
						switch ( $fields_v[0] ) {
							case 'sku':
								$wpc_products_data[ $wpc_product ]['sku'] = apply_filters( 'wooCompare_product_sku', $product->get_sku(), $product );
								break;
							case 'price':
								$wpc_products_data[ $wpc_product ]['price'] = apply_filters( 'wooCompare_product_price', $product->get_price_html(), $product );
								break;
							case 'stock':
								$wpc_products_data[ $wpc_product ]['stock'] = apply_filters( 'wooCompare_product_stock', wc_get_stock_html( $product ), $product );
								break;
							case 'shortDes':
								$wpc_products_data[ $wpc_product ]['shortDes'] = apply_filters( 'wooCompare_product_description', $product->get_short_description(), $product );
								break;
							case 'description':
								$des_content          = apply_filters( 'the_content', $product->get_description() );
								$stripped_des_content = wp_strip_all_tags( $des_content );
								$des_content_length   = function_exists( 'mb_strlen' ) ? mb_strlen( $stripped_des_content ) : strlen( $stripped_des_content );
								if ( $des_content_length > $this->settings->get_params( 'wpc_table_content_length' ) ) {
									$des_content = function_exists( 'mb_substr' ) ? mb_substr( $stripped_des_content, 0, $this->settings->get_params( 'wpc_table_content_length' ) ) : substr( $stripped_des_content, 0, $this->settings->get_params( 'wpc_table_content_length' ) );
									$des_content = '<div class="woo-compare-content-short" title="' . esc_attr__( 'Read more' ) . '">' . $des_content . '...</div>
                                        <div class="woo-compare-content-full">' . ( apply_filters( 'the_content', $product->get_description() ) ) . '</div>';
								}
								$wpc_products_data[ $wpc_product ]['description'] = apply_filters( 'wooCompare_product_content', $des_content, $product );
								break;
							case 'weight':
								$wpc_products_data[ $wpc_product ]['weight'] = apply_filters( 'wooCompare_product_weight', $product->get_weight(), $product );
								break;
							case 'dimensions':
								$wpc_products_data[ $wpc_product ]['dimensions'] = apply_filters( 'wooCompare_product_dimensions', wc_format_dimensions( $product->get_dimensions( false ) ), $product );
								break;
							case 'rating':
								if ( $product->get_rating_count() != '0' ) {
									$wpc_products_data[ $wpc_product ]['rating'] = apply_filters( 'wooCompare_product_rating', wc_get_rating_html( $product->get_average_rating() ) .
									                                                                                           sprintf( '<div class="woo-compare-inline">(%s)</div>', $product->get_rating_count() ), $product );
								} else {
									$wpc_products_data[ $wpc_product ]['rating'] = esc_html( '' );
								}
								break;
							case 'tags':
								$wpc_products_data[ $wpc_product ]['tags'] = apply_filters( 'wooCompare_product_tags', wc_get_product_tag_list( $wpc_product, ', ' ), $product );
								break;
							case 'categories':
								$wpc_products_data[ $wpc_product ]['categories'] = apply_filters( 'wooCompare_product_categories', wc_get_product_category_list( $wpc_product, ', ' ), $product );
								break;
							case 'comments':
								$wpc_products_data[ $wpc_product ]['comments'] = apply_filters( 'wooCompare_product_comments', get_comments_number( $wpc_product ), $product );
								break;
							case 'shipping':
								$shipping_class_id = $product->get_shipping_class_id();
								if ( ! empty( $shipping_class_id ) ) {
									$shipping_class_term                           = get_term( $shipping_class_id, 'product_shipping_class' );
									$wpc_products_data[ $wpc_product ]['shipping'] = apply_filters( 'wooCompare_product_shipping', $shipping_class_term->description, $product );
								} else {
									$wpc_products_data[ $wpc_product ]['shipping'] = apply_filters( 'wooCompare_product_shipping', '', $product );
								}
								break;
							case 'totalSale':
								$wpc_products_data[ $wpc_product ]['totalSale'] = apply_filters( 'wooCompare_product_totalSale', $product->get_total_sales(), $product );
								break;
							case 'availability':
								$product_availability                              = $product->get_availability();
								$wpc_products_data[ $wpc_product ]['availability'] = apply_filters( 'wooCompare_product_availability', $product_availability['availability'], $product );
								break;
							default:
								if ( taxonomy_exists( 'pa_' . $fields_v[0] ) ) {
									$wpc_products_data[ $wpc_product ][ $fields_v[0] ] = array();
									$terms                                             = get_the_terms( $product->get_id(), 'pa_' . $fields_v[0] );
									if ( ! empty( $terms ) ) {
										foreach ( $terms as $term ) {
											$term                                                = sanitize_term( $term, $fields_v[0] );
											$wpc_products_data[ $wpc_product ][ $fields_v[0] ][] = $term->name;
										}
									}
									$wpc_products_data[ $wpc_product ][ $fields_v[0] ] = implode( ', ', $wpc_products_data[ $wpc_product ][ $fields_v[0] ] );
								} else {
									$wpc_products_data[ $wpc_product ][ $fields_v[0] ] = 'null';
								}
								break;
						}
					}
				}
			}

			if ( $wpc_count == 1 ) {
				$this->wpc_load_table_item_contents( $wpc_products_data, $wpc_integrity, $wpc_row_overlay );

				return;
			}

			?>
            <div class="woo-compare-table-content">
				<?php
				$this->wpc_load_table_col_header( $s_fields, $wpc_integrity );
				$this->wpc_load_table_item_contents( $wpc_products_data, $wpc_integrity, $wpc_row_overlay );
				?>
            </div>
			<?php

		} else {
			?>
            <div class="woo-compare-table-content">
				<?php
				$this->wpc_load_table_col_header( $s_fields, $wpc_integrity );
				?>
            </div>
			<?php
		}

		return;
	}

	function wpc_load_table_item_header( $product, $wpc_integrity, $product_cart, $wpc_product ) {
		?>
        <div class="woo-compare-product-stage">
            <div class="woo-compare-product-stage-button">
                <div class="woo-compare-product-stage-inner woo-compare-table-product-freeze-contain">
                    <span class="woo-compare-table-product-freeze dashicons-before dashicons-paperclip"
                          title="<?php esc_attr_e( 'Freeze' ) ?>" data-freeze="0"
                          data-id="<?php echo esc_attr( $wpc_product ) ?>"></span>
                </div>
                <div class="woo-compare-product-stage-inner woo-compare-table-product-remove-contain">
                    <span class="woo-compare-table-product-remove dashicons-before dashicons-no-alt"
                          title="<?php esc_attr_e( 'Remove' ) ?>"
                          data-id="<?php echo esc_attr( $wpc_product ) ?>"></span>
                </div>
            </div>
			<?php
			if ( $this->settings->get_params( 'wpc_table_header_image_display' ) == true ) { ?>
                <div class="woo-compare-tr-title-image woo-compare-tr-head">
                    <div class="woo-compare-image-wrap"><?php echo wp_kses_post( $product->get_image( 'large', array( 'draggable' => 'false' ) ) ) ?>
                    </div>
                </div>
			<?php } else if ( $wpc_integrity ) { ?>
                <div class="woo-compare-tr-title-image woo-compare-tr-head tr-hide">
                    <div class="woo-compare-image-wrap"><?php echo wp_kses_post( $product->get_image( 'large', array( 'draggable' => 'false' ) ) ) ?>
                    </div>
                </div>
			<?php }
			if ( $this->settings->get_params( 'wpc_table_header_title_display' ) == true ) { ?>
                <div class="woo-compare-tr-title-name woo-compare-tr-head"><a
                            href="<?php echo esc_url( $product->get_permalink() ) ?>" draggable="false"
                            target="_blank"><?php echo wp_kses_post( wp_strip_all_tags( apply_filters( 'wooCompare_product_name', $product->get_name() ) ) ) ?></a>
                </div>
			<?php } else if ( $wpc_integrity ) { ?>
                <div class="woo-compare-tr-title-name woo-compare-tr-head tr-hide"><a
                            href="<?php echo esc_url( $product->get_permalink() ) ?>" draggable="false"
                            target="_blank"><?php echo wp_kses_post( wp_strip_all_tags( apply_filters( 'wooCompare_product_name', $product->get_name() ) ) ) ?></a>
                </div>
			<?php }
			if ( $this->settings->get_params( 'wpc_table_header_cart_display' ) == true ) { ?>
                <div class="woo-compare-tr-title-cart woo-compare-tr-head"><?php echo wp_kses( $product_cart, $this->wpc_expanded_allowed_tags() ) ?></div>
			<?php } else if ( $wpc_integrity ) { ?>
                <div class="woo-compare-tr-title-cart woo-compare-tr-head tr-hide"><?php echo wp_kses( $product_cart, $this->wpc_expanded_allowed_tags() ) ?></div>
			<?php } ?>
        </div>
		<?php
	}

	function wpc_load_table_item_contents( $wpc_products_data, $wpc_integrity, $wpc_row_overlay ) {
		foreach ( $wpc_products_data as $wpc_product_key => $wpc_product ) { ?>
            <div class="woo-compare-table-item-<?php echo esc_attr( $wpc_product_key ) ?> woo-compare-table-column woo-compare-table-column-free">
                <div class="woo-compare-table-row-freeze">
                </div>
                <div class="woo-compare-table-row-free">
					<?php
					foreach ( $wpc_product as $fields_k => $fields_v ) {
						$field_data = '';
						if ( ! empty( $fields_v ) ) {
							$field_data = $fields_v;
						} else {
							$field_data = '&nbsp;';
						}
						if ( $fields_k == 'title' ) { ?>
                            <div class="woo-compare-tr-<?php echo esc_attr( $fields_k ) ?> woo-compare-table-column-<?php echo esc_attr( $fields_k ) ?>"><?php echo wp_kses( $field_data, $this->wpc_expanded_allowed_tags() ) ?></div>
							<?php
						} else {
							if ( ! $wpc_integrity || ! in_array( $fields_k, $wpc_row_overlay ) ) {
								?>
                                <div class="woo-compare-cell woo-compare-tr-<?php echo esc_attr( $fields_k ) ?> woo-compare-table-column-<?php echo esc_attr( $fields_k ) ?>"><?php echo wp_kses( $field_data, $this->wpc_expanded_allowed_tags() ) ?></div>
								<?php
							} else {
								?>
                                <div class="woo-compare-cell woo-compare-tr-<?php echo esc_attr( $fields_k ) ?> woo-compare-table-column-<?php echo esc_attr( $fields_k ) ?> <?php echo esc_attr( "tr-hide" ) ?>"><?php echo wp_kses( $field_data, $this->wpc_expanded_allowed_tags() ) ?></div>
								<?php
							}
						}
					}
					?>
                </div>
            </div>
			<?php
		}
	}

	function wpc_load_table_col_header( $wpc_fields, $wpc_integrity ) {
		?>
        <div class="woo-compare-table-field-header">
            <div class="woo-compare-table-field-header-freeze"></div>
            <div class="woo-compare-table-field-header-free">
                <div class="woo-compare-table-field-header-button woo-compare-tr-title">
					<?php
					$this->wpc_table_settings();
					?>
                    <span class="button woo-compare-table-button-clear dashicons-before dashicons-trash"
                          title="<?php esc_attr_e( 'Clear' ); ?>"></span>
                </div>
				<?php
				foreach ( $wpc_fields as $fields_k => $fields_v ) {
					if ( $wpc_integrity || $fields_v[2] == 1 ) {
						if ( $fields_v[2] == 1 ) {
							?>
                            <div class="woo-compare-cell woo-compare-tr-<?php echo esc_attr( $fields_v[0] ) ?> woo-compare-table-field-header-<?php
							echo esc_attr( $fields_v[0] ) ?>"><?php echo wp_kses_post( $fields_v[1] ) ?></div>
							<?php
						} else {
							?>
                            <div class="woo-compare-cell woo-compare-tr-<?php echo esc_attr( $fields_v[0] ) ?> woo-compare-table-field-header-<?php
							echo esc_attr( $fields_v[0] ) ?> <?php echo esc_attr( "tr-hide" ) ?>"><?php echo wp_kses_post( $fields_v[1] ) ?></div>
							<?php
						}
					}
				}
				?>
            </div>
        </div>
		<?php

		return;
	}

	function wpc_load_table_search() {
		?>
        <div class="woo-compare-table-search">
            <div class="woo-compare-table-search-inner">
                <div class="woo-compare-table-search-button" title="<?php esc_attr_e( 'Search' ); ?>">
                    <p class="woo-compare-table-search-arrow dashicons dashicons-arrow-right-alt2"
                       title="<?php esc_attr_e( 'Hide' ); ?>"></p>
                    <span class="woo-compare-table-search-stick-vertical"></span><span
                            class="woo-compare-table-search-stick-horizontal"></span>
                </div>
                <div class="woo-compare-table-search-scroll"><p
                            class="woo-compare-table-search-scroll-top"><?php esc_html_e( 'Show search results' ); ?></p>
                </div>
                <input type="search" class="woo-compare-table-search-input" id="woo_compare_table_search_input"
                       placeholder="<?php esc_attr_e( 'Please enter 3 or more characters' ); ?>...">
                <div class="woo-compare-table-search-result"></div>
            </div>
        </div>
		<?php

		return;
	}

	function wpc_expanded_allowed_tags() {
		$my_allowed           = wp_kses_allowed_html( 'post' );
		$my_allowed['select'] = array(
			'class'                 => array(),
			'id'                    => array(),
			'name'                  => array(),
			'value'                 => array(),
			'type'                  => array(),
			'data-attribute_name'   => array(),
			'data-show_option_none' => array(),
		);
		$my_allowed['option'] = array(
			'selected' => array(),
			'value'    => array(),
		);
		$my_allowed['form']   = array(
			'class'                   => array(),
			'id'                      => array(),
			'name'                    => array(),
			'action'                  => array(),
			'method'                  => array(),
			'enctype'                 => array(),
			'data'                    => array(),
			'data-product_id'         => array(),
			'data-product_variations' => array(),
		);
		$my_allowed['input']  = array(
			'class'       => array(),
			'id'          => array(),
			'name'        => array(),
			'value'       => array(),
			'type'        => array(),
			'step'        => array(),
			'min'         => array(),
			'max'         => array(),
			'title'       => array(),
			'size'        => array(),
			'placeholder' => array(),
			'inputmode'   => array(),
		);
		$my_allowed['video']  = array(
			'class'    => array(),
			'id'       => array(),
			'name'     => array(),
			'value'    => array(),
			'src'      => array(),
//			'width'    => array(),
//			'height'   => array(),
			'preload'  => array(),
			'controls' => array(),
		);
		$my_allowed['source'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'src'   => array(),
			'type'  => array(),
		);
		$my_allowed['script'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'src'   => array(),
		);
		$my_allowed['iframe'] = array(
			'class'           => array(),
			'id'              => array(),
			'name'            => array(),
			'value'           => array(),
			'src'             => array(),
			'loading'         => array(),
			'title'           => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
		);

		return $my_allowed;
	}

	/**
	 *  Get page compare url
	 * public static function wpc_get_page_url() ----???
	 */
	function wpc_get_page_url() {
		$page_id  = $this->settings->get_params( 'wpc_page_compare' );
		$page_url = ! empty( $page_id ) ? get_permalink( $page_id ) : get_permalink( get_option( 'wpc_plugin_page_id' ) );

		return esc_url( $page_url );
	}

	/**
	 *  format class id
	 */
	function wpc_nice_class_id( $str ) {
		return preg_replace( '/[^a-zA-Z0-9#._-]/', '', $str );
	}

	function wpc_table_settings() {
		?>
        <span class="button woo-compare-table-button-setting dashicons-before dashicons-menu"
              title="<?php esc_attr_e( 'Settings' ); ?>"></span>
        <div class="woo-compare-table-setting">
            <table id="woo-compare-popup-table-setting-fields" class="woo-compare-popup-table-setting-fields">
                <thead>
                <tr>
                    <th>
						<?php esc_html_e( 'Fields', 'compe-woo-compare-products' ) ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Freeze', 'compe-woo-compare-products' ) ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Display', 'compe-woo-compare-products' ) ?>
                    </th>
                </tr>
                </thead>
                <tbody>
				<?php $s_fields = json_decode( $this->settings->get_params( 'wpc_blocks' ) );
				foreach ( $s_fields as $fields_k => $fields_v ) {
					if ( $fields_v[2] == 1 ) { ?>
                        <tr class="woo-compare-popup-settings-field woo-compare-settings-field-<?php echo esc_attr( $fields_v[0] ); ?>"
                            data-field="<?php echo esc_attr( $fields_v[0] ); ?>">
                            <td>
								<?php if ( $fields_v[1] != '' ) {
									echo esc_attr( $fields_v[1] );
								} else {
									echo esc_attr( '[' . $fields_v[0] . ']' );
								} ?>
                            </td>
                            <td>
                                <input type="checkbox"
                                       class="woo-compare-settings-freeze woo-compare-field-freeze-<?php echo esc_attr( $fields_v[0] ) ?>"
                                       title="<?php esc_attr_e( 'Freeze this column' ); ?>"
                                       value="<?php echo esc_attr( $fields_v[0] ) ?>">
                            </td>
                            <td>
                                <input type="checkbox"
                                       class="woo-compare-field-display woo-compare-field-display-<?php echo esc_attr( $fields_v[0] ) ?>"
                                       title="<?php esc_attr_e( 'Display this column' ); ?>"
                                       value="<?php echo esc_attr( $fields_v[0] ) ?>" checked="">
                            </td>
                        </tr>
					<?php }
				} ?>
                </tbody>
            </table>
        </div>
		<?php
	}

	function wpc_footer_setting() {
		$wpc_class = 'woo-compare-area';
		$wpc_class .= ' woo-compare-bar-' . esc_attr( $this->settings->get_params( 'wpc_side_bar_vertical' ) ) . ' woo-compare-bar-' .
		              esc_attr( $this->settings->get_params( 'wpc_side_bar_horizontal' ) );
		?>
        <div id="woo-compare-area" class="<?php echo esc_attr( $wpc_class ); ?>"
             data-bg-color="<?php echo esc_attr( $this->settings->get_params( 'wpc_side_bar_background_color' ) ); ?>"
             data-btn-background="<?php echo esc_attr( $this->settings->get_params( 'wpc_side_bar_button_background' ) ); ?>"
             data-btn-color="<?php echo esc_attr( $this->settings->get_params( 'wpc_side_bar_button_color' ) ); ?>">
            <div class="woo-compare-inner">
                <div class="woo-compare-overlay"></div>
				<?php if ( $this->settings->get_params( 'wpc_popup_compare' ) == 1 ) { ?>
                    <div class="woo-compare-table <?php echo esc_attr( $this->settings->get_params( 'wpc_popup_transition' ) ); ?>"
                         id="vi-woo-compare-table">
                        <div class="woo-compare-table-inner">
                            <div class="woo-compare-slide-prev-contain">
                                <span class="woo-compare-slide-prev wpc_icon_compare-left-arrow"
                                      title="<?php esc_attr_e( 'Previous', 'compe-woo-compare-products' ); ?>"></span>
                            </div>
                            <div class="woo-compare-slide-next-contain">
                                <span class="woo-compare-slide-next wpc_icon_compare-next"
                                      title="<?php esc_attr_e( 'Next', 'compe-woo-compare-products' ); ?>"></span>
                            </div>
                            <div class="woo-compare-table-items"></div>
							<?php
							$this->wpc_load_table_search();
							?>
                        </div>
                        <a id="woo-compare-table-close"
                           class="woo-compare-table-close"
                           title="<?php esc_attr_e( 'Close', 'compe-woo-compare-products' ); ?>"><span
                                    class="woo-compare-table-close-icon wpc_icon_compare-cancel-2"></span>
                        </a>
                    </div>
				<?php }
				$this->wpc_floating_icon();
				if ( $this->settings->get_params( 'wpc_open_sidebar' ) == 1 ) { ?>
                    <div class="woo-compare-bar <?php echo esc_attr( 'woo-compare-bar-bubble' ); ?>">
                        <div class="woo-compare-bar-items"></div>
                        <div class="woo-compare-bar-remove"
                             title="<?php esc_attr_e( 'Remove all', 'compe-woo-compare-products' ); ?>"></div>
                        <div class="woo-compare-bar-btn woo-compare-bar-btn-text">
							<?php
							$bar_btn_text = $this->settings->get_params( 'wpc_side_bar_button_text' );
							if ( empty( $bar_btn_text ) ) {
								$bar_btn_text = esc_html( 'Compare' );
							}
							echo esc_html( $bar_btn_text );
							?>
                        </div>
                        <div class="woo-compare-bar-btn-icon-wrapper">
                            <div class="woo-compare-bar-btn-icon-inner"><span></span><span></span>
                            </div>
                        </div>
                    </div>
				<?php } ?>
            </div>
        </div>
		<?php
	}

	function wpc_load_compare_bar() {
		check_ajax_referer( 'wpc-nonce', 'nonce' );
		$this->wpc_load_compare_bar_items();
		wp_die();
	}

	function wpc_load_compare_bar_items() {
		if ( isset( $_REQUEST['compe_frontend_nonce'] ) && ! wp_verify_nonce( wc_clean( wp_unslash( $_REQUEST['compe_frontend_nonce'] ) ), 'compe_frontend_nonce' ) ) {
		    return;
        }
		// get items
		$wpc_products = array();

		if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
			$wpc_products = explode( ',', sanitize_text_field( $_POST['products'] ) );
		} else {
			$wpc_cookie = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );

			if ( isset( $_COOKIE[ $wpc_cookie ] ) && ! empty( $_COOKIE[ $wpc_cookie ] ) ) {
				$wpc_products = explode( ',', sanitize_text_field( $_COOKIE[ $wpc_cookie ] ) );
			}
		}

		if ( ! empty( $wpc_products ) ) {
			foreach ( $wpc_products as $wpc_product ) {
				$wpc_product_obj = wc_get_product( $wpc_product );

				if ( ! $wpc_product_obj ) {
					continue;
				}

				$wpc_product_id   = $wpc_product_obj->get_id();
				$wpc_product_name = apply_filters( 'wooCompare_product_name', $wpc_product_obj->get_name() );
				?>
                <div class="woo-compare-bar-item woo-compare-bar-item-<?php echo esc_attr( $wpc_product_id ) ?>"
                     data-id="<?php echo esc_attr( $wpc_product_id ) ?>">
                    <span class="woo-compare-bar-item-img"
                          title="<?php echo esc_attr( apply_filters( 'wooCompare_product_title', wp_strip_all_tags( $wpc_product_name ), $wpc_product_obj ) ) ?>">
                        <?php echo wp_kses_post( $wpc_product_obj->get_image( 'thumbnail' ) ) ?></span>
                    <span class="woo-compare-bar-item-remove" data-id="<?php echo esc_attr( $wpc_product_id ) ?>"
                          title="<?php esc_attr_e( 'Remove' ); ?>"></span>
                </div>
				<?php
			}
		}

		return;
	}

	public function wpc_floating_icon() {
		if ( $this->settings->get_params( 'wpc_popup_compare' ) ) {
			$hide_anim = '';
			if ( in_array( $this->settings->get_params( 'wpc_floating_icon_position' ), array(
				'top-left',
				'bottom-left'
			) ) ) {
				$hide_anim .= ' woo-compare-floating-icon-hide-left';
			} else {
				$hide_anim .= ' woo-compare-floating-icon-hide-right';
			}
			?>
            <div class="woo-compare-floating-icon-wrap woo-compare-floating-icon-position-<?php echo esc_attr( $this->settings->get_params( 'wpc_floating_icon_position' ) );
			echo esc_attr( $hide_anim ); ?>">
                <div class="woo-compare-floating-icon-container">
                    <span class="woo-compare-floating-icon-close woo-compare_button_close_icons-cancel"
                          title="<?php esc_html_e( 'Do not show again', 'compe-woo-compare-products' ) ?>">X</span>
                    <span class="woo-compare-floating-icon <?php echo esc_attr( $this->settings->get_params( 'wpc_floating_icon' ) ) ?>"> </span>
                </div>
            </div>
			<?php
		}
	}

	public function wpc_variation_cart() {
		check_ajax_referer( 'wpc-nonce', 'nonce' );
		ob_start();

		if ( ! isset( $_POST['product_id'] ) ) {
			return;
		}

		$product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
		$product           = wc_get_product( $product_id );
		$quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $_POST['quantity'] ) );
		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
		$product_status    = get_post_status( $product_id );
		$variation_id      = 0;
		$variation         = array();

		if ( $product && 'variation' === $product->get_type() ) {
			$variation_id  = $product_id;
			$product_id    = $product->get_parent_id();
			$variation_src = $product->get_variation_attributes();
			foreach ( $variation_src as $fields_k => $fields_v ) {
				$variation[ $fields_k ] = ! empty( $_POST[ $fields_k ] ) ? wc_sanitize_taxonomy_name( $_POST[ $fields_k ] ) : '';
			}
		}
		if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation ) && 'publish' === $product_status ) {

			do_action( 'woocommerce_ajax_added_to_cart', $product_id );
			if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
			}

			self::wpc_get_refreshed_fragments();

		} else {

			// If there was an error adding to the cart, redirect to the product page to show any errors.
			$data = array(
				'error'       => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
			);

			wp_send_json( $data );
		}
	}

	/**
	 * Get a refreshed cart fragment, including the mini cart HTML.
	 */
	public static function wpc_get_refreshed_fragments() {
		ob_start();

		woocommerce_mini_cart();

		$mini_cart = ob_get_clean();

		$data = array(
			'fragments' => apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			),
			'cart_hash' => WC()->cart->get_cart_hash(),
		);

		wp_send_json( $data );
	}

	public function wpc_generate_pdf() {
		global $post;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

	}

	public function wpc_hexToRgb( $color, $alpha = 1 ) {
		list( $r, $g, $b ) = array_map(
			function ( $c ) {
				return hexdec( str_pad( $c, 2, $c ) );
			},
			str_split( ltrim( $color, '#' ), strlen( $color ) > 4 ? 2 : 1 )
		);

		return [ $r, $g, $b, $alpha ];
	}
}