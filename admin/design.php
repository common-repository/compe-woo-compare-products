<?php
/*
Class Name: VI_WOO_PRODUCT_COMPARE_Admin_Design
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_PRODUCT_COMPARE_Admin_Design {
	protected $settings;

	public function __construct() {

		$this->settings = new VI_WOO_PRODUCT_COMPARE_DATA();

		add_action( 'customize_register', array( $this, 'design_option_customizer' ) );
		add_action( 'wp_print_styles', array( $this, 'customize_controls_print_styles' ) );
		add_action( 'customize_preview_init', array( $this, 'customize_preview_init' ) );
		add_action( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_scripts' ), 30 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ), 30 );
	}

	public function customize_controls_print_styles() {
		?>
        <style id="woo-product-compare-custom-css" type="text/css"></style>
        <style id="woo-product-compare-custom-input-border-radius" type="text/css"></style>
		<?php

	}

	public function customize_controls_enqueue_scripts() {
		wp_enqueue_style( 'compe-woo-compare-products-customizer', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc-customizer.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
		wp_enqueue_style( 'compe-woo-compare-products-floating-icons', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc_icon_compare.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
	}

	public function wp_enqueue_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}
		wp_enqueue_style( 'compe-woo-compare-products-frontend', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc-frontend.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
		wp_enqueue_style( 'compe-woo-compare-products-floating-icons', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc_icon_compare.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );

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
		$css .= '}';
		$css .= '.type-product .woo-compare-btn.woo-compare-icon:not(.woo-compare-btn-inside) {';
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.type-product .woo-compare-btn.woo-compare-icon.woo-compare-btn-inside {';
//		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
		$css .= '}';
		$css .= '.type-product .woo-compare-btn.woo-compare-icon i {';
		if ( ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_right' ) || ( $this->settings->get_params( 'wpc_btn_archive_pos' ) == 'on_image_left' ) ) {
			$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
//			$css .= 'line-height:' . esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ) . 'px;';
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
		$css .= 'font-size:' . esc_attr( $this->settings->get_params( 'wpc_widget_header_size' ) ) . 'px + !important;';
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

		//only customizer
		$css .= '.woo-compare-floating-icon-wrap {';
		$css .= 'display: initial;';
		$css .= '}';
		$css .= '.woo-compare-floating-icon-wrap.woo-compare-hide {';
		$css .= 'display: none;';
		$css .= '}';

		//alter color

		wp_add_inline_style( 'compe-woo-compare-products-frontend', esc_attr( $css ) );
	}

	public function customize_preview_init() {
		$this->wpc_single_filter_button( $this->settings->get_params( 'wpc_btn_single_pos' ) );
		$this->wpc_archive_filter_button( $this->settings->get_params( 'wpc_btn_archive_pos' ) );
		$suffix = WP_DEBUG ? '' : 'min.';
		wp_enqueue_script( 'compe-woo-compare-products-customize-preview-js', VI_WOO_PRODUCT_COMPARE_JS . 'wpc-customizer.' . $suffix . 'js', array(
			'jquery',
			'customize-preview',
		), VI_WOO_PRODUCT_COMPARE_VERSION, true );
		$data_fields_arr = json_decode( $this->settings->get_params( 'wpc_blocks' ) );
		$fields_arr      = array( 'title' );
		foreach ( $data_fields_arr as $fields_k => $fields_v ) {
			array_push( $fields_arr, $fields_v[0] );
			$data_fields_arr[ $fields_k ] = array_map( 'esc_attr', $fields_v );
		}
		$fields_arr = array_map( 'esc_attr', $fields_arr );
		wp_localize_script( 'compe-woo-compare-products-customize-preview-js', 'woo_compare_design_params', array(
			'ajaxurl'               => admin_url( 'admin-ajax.php' ),
			'user_id'               => md5( 'wpc' . get_current_user_id() ),
			'fields_arr'            => $fields_arr,
			'data_fields_arr'       => $data_fields_arr,
			'wpc_popup_compare'     => esc_attr( $this->settings->get_params( 'wpc_popup_compare' ) ),
			'wpc_list_product_mode' => esc_attr( $this->settings->get_params( 'wpc_list_product_mode' ) ),
			'wpc_btn_compare_icon'  => esc_attr( $this->settings->get_params( 'wpc_btn_compare_icon' ) ),

			'wpc_btn_single_pos'         => esc_attr( $this->settings->get_params( 'wpc_btn_single_pos' ) ),
			'wpc_btn_compare_text'       => esc_attr( $this->settings->get_params( 'wpc_btn_compare_text' ) ),
			'wpc_btn_compared_text'      => $this->settings->get_params( 'wpc_remove_mode' ) == 0 ? esc_attr( $this->settings->get_params( 'wpc_btn_added_text' ) ) : esc_attr( $this->settings->get_params( 'wpc_text_remove' ) ),
			'wpc_btn_compare_font_size'  => esc_attr( $this->settings->get_params( 'wpc_btn_compare_font_size' ) ),
			'wpc_btn_compare_color'      => esc_attr( $this->settings->get_params( 'wpc_btn_compare_color' ) ),
			'wpc_btn_compare_background' => esc_attr( $this->settings->get_params( 'wpc_btn_compare_background' ) ),
			'wpc_btn_compare_added'      => esc_attr( $this->settings->get_params( 'wpc_btn_compare_added' ) ),

			'wpc_btn_archive_pos'          => esc_attr( $this->settings->get_params( 'wpc_btn_archive_pos' ) ),
			'wpc_btn_archive_size'         => esc_attr( $this->settings->get_params( 'wpc_btn_archive_size' ) ),
			'wpc_btn_archive_color'        => esc_attr( $this->settings->get_params( 'wpc_btn_archive_color' ) ),
			'wpc_btn_archive_background'   => esc_attr( $this->settings->get_params( 'wpc_btn_archive_background' ) ),
			'wpc_btn_archive_added'        => esc_attr( $this->settings->get_params( 'wpc_btn_archive_added' ) ),
			'wpc_btn_archive_enable_hover' => esc_attr( $this->settings->get_params( 'wpc_btn_archive_enable_hover' ) ),
			'wpc_floating_icon_position'   => ( $this->settings->get_params( 'wpc_floating_icon_position' ) == 'bottom-left' ||
			                                    $this->settings->get_params( 'wpc_floating_icon_position' ) == 'top-left' ) ? esc_attr( 'left' ) : esc_attr( 'right' ),

			'wpc_table_header_image_size'          => esc_html( $this->settings->get_params( 'wpc_table_header_image_size' ) ),
			'wpc_table_header_background'          => esc_html( $this->settings->get_params( 'wpc_table_header_background' ) ),
			'wpc_table_content_header_background'  => esc_html( $this->settings->get_params( 'wpc_table_content_header_background' ) ),
			'wpc_table_content_content_background' => esc_html( $this->settings->get_params( 'wpc_table_content_content_background' ) ),
			'wpc_table_alternating_type'           => esc_html( $this->settings->get_params( 'wpc_table_alternating_type' ) ),
			'wpc_table_alternating_col_odd'        => esc_html( $this->settings->get_params( 'wpc_table_alternating_col_odd' ) ),
			'wpc_table_alternating_col_even'       => esc_html( $this->settings->get_params( 'wpc_table_alternating_col_even' ) ),
			'wpc_table_alternating_row_odd'        => esc_html( $this->settings->get_params( 'wpc_table_alternating_row_odd' ) ),
			'wpc_table_alternating_row_even'       => esc_html( $this->settings->get_params( 'wpc_table_alternating_row_even' ) ),
			'wpc_compare_hover'                    => esc_html( $this->settings->get_params( 'wpc_btn_compare_hover' ) ),
			'wpc_compare_color'                    => esc_html( $this->settings->get_params( 'wpc_btn_compare_color' ) ),
			'wpc_compare_background'               => esc_html( $this->settings->get_params( 'wpc_btn_compare_background' ) ),
			'wpc_compare_added'                    => esc_html( $this->settings->get_params( 'wpc_btn_compare_added' ) ),

			'nonce' => wp_create_nonce( 'wpc-nonce' ),
		) );
	}

	public function wpc_single_btn_after_add() {
		do_shortcode( '[wpc_sc_single position="31"]' );
	}

	public function wpc_single_btn_before_add() {
		do_shortcode( '[wpc_sc_single position="29"]' );
	}

	public function wpc_single_btn_after_price() {
		do_shortcode( '[wpc_sc_single position="11"]' );
	}

	public function wpc_single_btn_after_title() {
		do_shortcode( '[wpc_sc_single position="6"]' );
	}

	public function wpc_archive_btn_image_right() {
		do_shortcode( '[wpc_sc_archive position="on_image_right"]' );
	}

	public function wpc_archive_btn_image_left() {
		do_shortcode( '[wpc_sc_archive position="on_image_left"]' );
	}

	public function wpc_archive_btn_after_title() {
		do_shortcode( '[wpc_sc_archive position="after_title"]' );
	}

	public function wpc_archive_btn_after_rating() {
		do_shortcode( '[wpc_sc_archive position="after_rating"]' );
	}

	public function wpc_archive_btn_after_price() {
		do_shortcode( '[wpc_sc_archive position="after_price"]' );
	}

	public function wpc_archive_btn_before_add() {
		do_shortcode( '[wpc_sc_archive position="before_add"]' );
	}

	public function wpc_archive_btn_after_add() {
		do_shortcode( '[wpc_sc_archive position="after_add"]' );
	}

	public function wpc_single_filter_button( $position = '' ) {
		if ( $position != '31' ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'wpc_single_btn_after_add' ), 31 );
		}
		if ( $position != '29' ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'wpc_single_btn_before_add' ), 29 );
		}
		if ( $position != '11' ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'wpc_single_btn_after_price' ), 11 );
		}
		if ( $position != '6' ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'wpc_single_btn_after_title' ), 6 );
		}
	}

	public function wpc_archive_filter_button( $position = '' ) {
		if ( $position != 'on_image_left' ) {
			add_action( 'woocommerce_before_shop_loop_item', array( $this, 'wpc_archive_btn_image_left' ), 7 );
		}
		if ( $position != 'on_image_right' ) {
			add_action( 'woocommerce_before_shop_loop_item', array( $this, 'wpc_archive_btn_image_right' ), 7 );
		}
		if ( $position != 'after_title' ) {
			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'wpc_archive_btn_after_title' ), 11 );
		}
		if ( $position != 'after_rating' ) {
			add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'wpc_archive_btn_after_rating' ), 6 );
		}
		if ( $position != 'after_price' ) {
			add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'wpc_archive_btn_after_price' ), 11 );
		}
		if ( $position != 'before_add_to_cart' ) {
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wpc_archive_btn_before_add' ), 9 );
		}
		if ( $position != 'after_add_to_cart' ) {
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'wpc_archive_btn_after_add' ), 11 );
		}
	}

	public function customize_controls_print_scripts() {
		if ( ! is_customize_preview() ) {
			return;
		}
		?>
        <script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    wp.customize.panel('wpc_product_compare_design', function (panel) {
                        panel.expanded.bind(function (isExpanded) {
                            let iframe = $('iframe').contents().find('body');
                            if (isExpanded) {
                                iframe.find('.woo-compare-floating-icon-wrap').addClass('woo-compare-hide');
                                iframe.find('.woo-compare-bar-bubble').removeClass('woo-compare-bar-open');
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) != 1) {
								?>
                                wp.customize.previewer.previewUrl.set('<?php echo esc_js( get_permalink( $this->settings->get_params( 'wpc_page_compare' ) ) ); ?>');
								<?php } else { ?>
                                iframe.find('.woo-compare-table').addClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
								<?php } ?>
                            } else {
                                iframe.find('.woo-compare-bar-bubble').addClass('woo-compare-bar-open');
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) == 1) {
								?>
                                iframe.find('.woo-compare-table').removeClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
								<?php } ?>
                            }
                        });
                    });

                    /*compare icon*/
                    wp.customize('woo_product_compare_params[wpc_btn_compare_icon]', function (value) {
                        value.bind(function (newval) {
                            $('.woo_product_compare_params-wpc_btn_compare_icon label').removeClass('wpc-radio-icons-active');
                            $('.woo_product_compare_params-wpc_btn_compare_icon .' + newval).parent().addClass('wpc-radio-icons-active');
                        });
                    });
                    /*floating icon*/
                    wp.customize('woo_product_compare_params[wpc_floating_icon]', function (value) {
                        value.bind(function (newval) {
                            $('.woo_product_compare_params-wpc_floating_icon label').removeClass('wpc-radio-icons-active');
                            $('.woo_product_compare_params-wpc_floating_icon .' + newval).parent().addClass('wpc-radio-icons-active');
                        });
                    });
                    wp.customize.section('wpc_product_compare_design_element_style', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            let iframe = $('iframe').contents().find('body');
                            if (isExpanded) {
                                iframe.find('.woo-compare-table').removeClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
                                iframe.find('.woo-compare-bar.woo-compare-bar-bubble').addClass('woo-compare-bar-open');
                                iframe.find('.woo-compare-floating-icon-wrap').removeClass('woo-compare-hide');
                                wp.customize.previewer.previewUrl.set('<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>');
                            } else {
                                iframe.find('.woo-compare-floating-icon-wrap').addClass('woo-compare-hide');
                                iframe.find('.woo-compare-bar-bubble').removeClass('woo-compare-bar-open');
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) != 1) {
								?>
                                wp.customize.previewer.previewUrl.set('<?php echo esc_js( get_permalink( $this->settings->get_params( 'wpc_page_compare' ) ) ); ?>');
								<?php } else { ?>
                                iframe.find('.woo-compare-table').addClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
								<?php } ?>
                            }
                        });
                    });
                    wp.customize.section('wpc_product_compare_design_widget', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            let iframe = $('iframe').contents().find('body');
                            if (isExpanded) {
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) == 1) { ?>
                                iframe.find('.woo-compare-table').removeClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
								<?php } ?>
                            } else {
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) == 1) { ?>
                                iframe.find('.woo-compare-table').addClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
								<?php } ?>
                            }
                        });
                    });
                    wp.customize.section('wpc_product_compare_design_display_content', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            let iframe = $('iframe').contents().find('body');
                            if (isExpanded) {
                                iframe.find('.woo-compare-floating-icon-wrap').addClass('woo-compare-hide');
                                iframe.find('.woo-compare-bar-bubble').removeClass('woo-compare-bar-open');
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) != 1) {
								?>
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
								<?php } else { ?>
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
                                iframe.find('.woo-compare-table').addClass('woo-compare-table-open');
								<?php } ?>
                            }
                        });
                    });
                    wp.customize.section('wpc_product_compare_design_table_content', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            let iframe = $('iframe').contents().find('body');
                            if (isExpanded) {
                                iframe.find('.woo-compare-floating-icon-wrap').addClass('woo-compare-hide');
                                iframe.find('.woo-compare-bar-bubble').removeClass('woo-compare-bar-open');
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) != 1) {
								?>
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
								<?php } else { ?>
                                iframe.find('.woo-compare-table').addClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
								<?php } ?>
                            }
                        });
                    });
                    wp.customize.section('wpc_product_compare_design_table_buttons', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            let iframe = $('iframe').contents().find('body');
                            if (isExpanded) {
                                iframe.find('.woo-compare-floating-icon-wrap').addClass('woo-compare-hide');
                                iframe.find('.woo-compare-bar-bubble').removeClass('woo-compare-bar-open');
								<?php if ($this->settings->get_params( 'wpc_popup_compare' ) != 1) {
								?>
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
								<?php } else { ?>
                                iframe.find('.woo-compare-table').addClass('woo-compare-table-open');
                                iframe.find('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
								<?php } ?>
                            }
                        });
                    });

                    wp.customize('woo_product_compare_params[wpc_btn_archive_pos]', function (value) {
                        value.bind(function (newval) {
                            switch (newval) {
                                case 'on_image_left':
                                case 'on_image_right':
                                    $('#customize-control-woo_product_compare_params-wpc_btn_archive_enable_hover').css('display', 'initial');
                                    $('#customize-control-woo_product_compare_params-wpc_btn_archive_background').css('display', 'none');
                                    break;
                                default:
                                    $('#customize-control-woo_product_compare_params-wpc_btn_archive_enable_hover').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_btn_archive_background').css('display', 'initial');
                                    break;
                            }
                        });
                    });

                    wp.customize('woo_product_compare_params[wpc_table_alternating_type]', function (value) {
                        value.bind(function (newval) {
                            switch (newval) {
                                case 'row':
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_row_odd').css('display', 'initial');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_row_even').css('display', 'initial');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_col_odd').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_col_even').css('display', 'none');
                                    break;
                                case 'col':
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_row_odd').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_row_even').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_col_odd').css('display', 'initial');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_col_even').css('display', 'initial');
                                    break;
                                default:
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_row_odd').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_row_even').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_col_odd').css('display', 'none');
                                    $('#customize-control-woo_product_compare_params-wpc_table_alternating_col_even').css('display', 'none');
                                    break;
                            }
                        });
                    });
                });
            })(jQuery);
        </script>
		<?php
	}

	public function design_option_customizer( $wp_customize ) {
		$wp_customize->add_panel( 'wpc_product_compare_design', array(
			'priority'       => 200,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Woo Product Compare', 'compe-woo-compare-products' ),
		) );


		$this->add_section_design_display_content( $wp_customize );
		$this->add_section_design_table_content( $wp_customize );
		$this->add_section_design_table_buttons( $wp_customize );
		$this->add_section_design_element_style( $wp_customize );
		$this->add_section_design_widget( $wp_customize );
		$this->add_section_design_custom_css( $wp_customize );
	}

	protected function add_section_design_widget( $wp_customize ) {
		$wp_customize->add_section( 'wpc_product_compare_design_widget', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Widget Compare', 'compe-woo-compare-products' ),
			'panel'          => 'wpc_product_compare_design',
		) );

		//Header
		$wp_customize->selective_refresh->add_partial( 'woo_product_compare_params[wpc_widget_header_text]', array(
			'selector'            => '.widget .widget_wpc-widget',
			'container_inclusive' => true,
			'fallback_refresh'    => false,
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_header]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_header]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_widget',
			'label'   => esc_html__( 'Widget header', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_header_text]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_widget_header_text' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_header_text]', array(
			'type'     => 'text',
			'section'  => 'wpc_product_compare_design_widget',
			'settings' => 'woo_product_compare_params[wpc_widget_header_text]',
			'label'    => esc_html__( 'Text', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_header_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_widget_header_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_header_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_widget',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_header_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_widget_header_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_widget_header_color]',
				array(
					'label'    => esc_html__( 'Color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_widget',
					'settings' => 'woo_product_compare_params[wpc_widget_header_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_header_background]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_widget_header_background' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_widget_header_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_widget',
					'settings' => 'woo_product_compare_params[wpc_widget_header_background]',
				) )
		);

		//Compare button
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_compare]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_compare]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_widget',
			'label'   => esc_html__( 'Compare button', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_compare_text_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_widget_compare_text_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_compare_text_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_widget',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_compare_text_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_widget_compare_text_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_widget_compare_text_color]',
				array(
					'label'    => esc_html__( 'Text color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_widget',
					'settings' => 'woo_product_compare_params[wpc_widget_compare_text_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_compare_background]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_widget_compare_background' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_widget_compare_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_widget',
					'settings' => 'woo_product_compare_params[wpc_widget_compare_background]',
				) )
		);

		//Clear button
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_clear]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_clear]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_widget',
			'label'   => esc_html__( 'Clear button', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_clear_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_widget_clear_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_widget_clear_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_widget',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_clear_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_widget_clear_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_widget_clear_color]',
				array(
					'label'    => esc_html__( 'Text color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_widget',
					'settings' => 'woo_product_compare_params[wpc_widget_clear_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_widget_clear_background]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_widget_clear_background' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_widget_clear_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_widget',
					'settings' => 'woo_product_compare_params[wpc_widget_clear_background]',
				) )
		);
	}

	protected function add_section_design_display_content( $wp_customize ) {
		$wp_customize->add_section( 'wpc_product_compare_design_display_content', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Display content', 'compe-woo-compare-products' ),
			'panel'          => 'wpc_product_compare_design',
		) );

		$wp_customize->selective_refresh->add_partial( 'woo_product_compare_params[wpc_blocks]', array(
			'selector'            => array(
				'.woo-compare-table',
			),
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_blocks]', array(
			'default'              => $this->settings->get_default( 'wpc_blocks' ),
			'type'                 => 'option',
			'capability'           => 'manage_options',
			'sanitize_callback'    => 'wpc_sanitize_block',
			'sanitize_js_callback' => 'wpc_sanitize_block',
			'transport'            => 'postMessage',
		) );
		$wp_customize->add_control(
			new VI_WOO_PRODUCT_COMPARE_Field_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_blocks]',
				array(
					'label'   => 'Layout',
					'section' => 'wpc_product_compare_design_display_content',
				)
			)
		);
	}

	protected function add_section_design_element_style( $wp_customize ) {
		$wp_customize->add_section( 'wpc_product_compare_design_element_style', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Element style', 'compe-woo-compare-products' ),
			'panel'          => 'wpc_product_compare_design',
		) );

		//single compare button
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_element_style_button_single]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_element_style_button_single]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_element_style',
			'label'   => esc_html__( 'Button on single product', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_compare_font_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_btn_compare_font_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_btn_compare_font_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_element_style',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_compare_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_btn_compare_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_compare_color]',
				array(
					'label'    => esc_html__( 'Text color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_btn_compare_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_compare_background]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_btn_compare_background' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_compare_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_btn_compare_background]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_compare_added]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_btn_compare_added' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_compare_added]',
				array(
					'label'    => esc_html__( 'Added to compare list color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_btn_compare_added]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_single_pos]', array(
			'default'           => $this->settings->get_default( 'wpc_btn_single_pos' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_btn_single_pos]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_element_style',
			'label'    => esc_html__( 'Select position', 'compe-woo-compare-products' ),
			'choices'  => array(
				'6'  => esc_html__( 'Under title', 'compe-woo-compare-products' ),
				'11' => esc_html__( 'Under price & rating', 'compe-woo-compare-products' ),
				'29' => esc_html__( 'Above add to cart', 'compe-woo-compare-products' ),
				'31' => esc_html__( 'Under add to cart', 'compe-woo-compare-products' ),
			),
		) );

		//archive compare button
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_element_style_button_list]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_element_style_button_list]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_element_style',
			'label'   => esc_html__( 'Button on list product', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_archive_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_btn_archive_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_btn_archive_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_element_style',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_archive_color]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_btn_archive_color' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_archive_color]',
				array(
					'label'    => esc_html__( 'Color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_btn_archive_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_archive_background]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_btn_archive_background' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_archive_background]',
				array(
					'label'           => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'         => 'wpc_product_compare_design_element_style',
					'settings'        => 'woo_product_compare_params[wpc_btn_archive_background]',
					'active_callback' => array( $this, 'wpc_btn_archive_background_callback' ),
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_archive_added]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'default'           => $this->settings->get_default( 'wpc_btn_archive_added' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_archive_added]',
				array(
					'label'    => esc_html__( 'Added to compare list color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_btn_archive_added]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_archive_pos]', array(
			'default'           => $this->settings->get_default( 'wpc_btn_archive_pos' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_btn_archive_pos]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_element_style',
			'label'    => esc_html__( 'Select position', 'compe-woo-compare-products' ),
			'choices'  => array(
				'on_image_left'      => esc_html__( 'On top left of the image', 'compe-woo-compare-products' ),
				'on_image_right'     => esc_html__( 'On top right of the image', 'compe-woo-compare-products' ),
				'after_title'        => esc_html__( 'Under title', 'compe-woo-compare-products' ),
				'after_rating'       => esc_html__( 'Under rating', 'compe-woo-compare-products' ),
				'after_price'        => esc_html__( 'Under price', 'compe-woo-compare-products' ),
				'before_add_to_cart' => esc_html__( 'Above add to cart', 'compe-woo-compare-products' ),
				'after_add_to_cart'  => esc_html__( 'Under add to cart', 'compe-woo-compare-products' ),
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_archive_enable_hover]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_btn_archive_enable_hover' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_btn_archive_enable_hover]', array(
			'type'            => 'checkbox',
			'section'         => 'wpc_product_compare_design_element_style',
			'label'           => esc_html__( 'Only display on hover image', 'compe-woo-compare-products' ),
			'description'     => '',
			'active_callback' => array( $this, 'wpc_btn_archive_hover_active_callback' ),
		) );

		//Compare icon
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_element_style_compare_icon]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_element_style_compare_icon]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_element_style',
			'label'   => esc_html__( 'Compare icon', 'compe-woo-compare-products' ),
		) );
		$wp_customize->selective_refresh->add_partial( 'woo_product_compare_params[wpc_element_style_compare_icon]', array(
			'selector'            => '.woo-compare-floating-icon-container',
			'container_inclusive' => true,
			'fallback_refresh'    => false,
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_btn_compare_icon]', array(
			'default'           => $this->settings->get_default( 'wpc_btn_compare_icon' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new VI_WOO_PRODUCT_COMPARE_Radio_Icons_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_btn_compare_icon]',
				array(
					'label'   => 'Icons',
					'section' => 'wpc_product_compare_design_element_style',
					'choices' => array(
						"wpc_icon_compare-share-files"  => "wpc_icon_compare-share-files",
						"wpc_icon_compare-ab-testing"   => "wpc_icon_compare-ab-testing",
						"wpc_icon_compare-ab-testing-1" => "wpc_icon_compare-ab-testing-1",
						"wpc_icon_compare-compare"      => "wpc_icon_compare-compare",
						"wpc_icon_compare-compare-1"    => "wpc_icon_compare-compare-1",
						"wpc_icon_compare-compare-2"    => "wpc_icon_compare-compare-2",
						"wpc_icon_compare-comparative"  => "wpc_icon_compare-comparative",
						"wpc_icon_compare-risk"         => "wpc_icon_compare-risk",
						"wpc_icon_compare-compare-3"    => "wpc_icon_compare-compare-3",
						"wpc_icon_compare-compare-4"    => "wpc_icon_compare-compare-4",
						"wpc_icon_compare-compare-5"    => "wpc_icon_compare-compare-5",
						"wpc_icon_compare-decision"     => "wpc_icon_compare-decision",
						"wpc_icon_compare-advantages"   => "wpc_icon_compare-advantages",
						"wpc_icon_compare-computer"     => "wpc_icon_compare-computer",
						"wpc_icon_compare-diagram"      => "wpc_icon_compare-diagram",
						"wpc_icon_compare-balance"      => "wpc_icon_compare-balance",
						"wpc_icon_compare-file"         => "wpc_icon_compare-file",
						"wpc_icon_compare-lists"        => "wpc_icon_compare-lists",
						"wpc_icon_compare-website"      => "wpc_icon_compare-website",
						"wpc_icon_compare-skill"        => "wpc_icon_compare-skill",
						"wpc_icon_compare-file-1"       => "wpc_icon_compare-file-1",
						"wpc_icon_compare-phone"        => "wpc_icon_compare-phone",
						"wpc_icon_compare-compare-6"    => "wpc_icon_compare-compare-6",
						"wpc_icon_compare-compare-7"    => "wpc_icon_compare-compare-7"
					),
				)
			)
		);

		//floating icon
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_element_style_floating]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_element_style_floating]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_element_style',
			'label'   => esc_html__( 'Floating icon', 'compe-woo-compare-products' ),
		) );
		$wp_customize->selective_refresh->add_partial( 'woo_product_compare_params[wpc_element_style_floating]', array(
			'selector'            => '.woo-compare-floating-icon-container',
			'container_inclusive' => true,
			'fallback_refresh'    => false,
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_floating_icon]', array(
			'default'           => $this->settings->get_default( 'wpc_floating_icon' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new VI_WOO_PRODUCT_COMPARE_Radio_Icons_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_floating_icon]',
				array(
					'label'   => 'Icons',
					'section' => 'wpc_product_compare_design_element_style',
					'choices' => array(
						"wpc_icon_compare-share-files"  => "wpc_icon_compare-share-files",
						"wpc_icon_compare-ab-testing"   => "wpc_icon_compare-ab-testing",
						"wpc_icon_compare-ab-testing-1" => "wpc_icon_compare-ab-testing-1",
						"wpc_icon_compare-compare"      => "wpc_icon_compare-compare",
						"wpc_icon_compare-compare-1"    => "wpc_icon_compare-compare-1",
						"wpc_icon_compare-compare-2"    => "wpc_icon_compare-compare-2",
						"wpc_icon_compare-comparative"  => "wpc_icon_compare-comparative",
						"wpc_icon_compare-risk"         => "wpc_icon_compare-risk",
						"wpc_icon_compare-compare-3"    => "wpc_icon_compare-compare-3",
						"wpc_icon_compare-compare-4"    => "wpc_icon_compare-compare-4",
						"wpc_icon_compare-compare-5"    => "wpc_icon_compare-compare-5",
						"wpc_icon_compare-decision"     => "wpc_icon_compare-decision",
						"wpc_icon_compare-advantages"   => "wpc_icon_compare-advantages",
						"wpc_icon_compare-computer"     => "wpc_icon_compare-computer",
						"wpc_icon_compare-diagram"      => "wpc_icon_compare-diagram",
						"wpc_icon_compare-balance"      => "wpc_icon_compare-balance",
						"wpc_icon_compare-file"         => "wpc_icon_compare-file",
						"wpc_icon_compare-lists"        => "wpc_icon_compare-lists",
						"wpc_icon_compare-website"      => "wpc_icon_compare-website",
						"wpc_icon_compare-skill"        => "wpc_icon_compare-skill",
						"wpc_icon_compare-file-1"       => "wpc_icon_compare-file-1",
						"wpc_icon_compare-phone"        => "wpc_icon_compare-phone",
						"wpc_icon_compare-compare-6"    => "wpc_icon_compare-compare-6",
						"wpc_icon_compare-compare-7"    => "wpc_icon_compare-compare-7"
					),
				)
			)
		);

		$wp_customize->add_setting( 'woo_product_compare_params[wpc_floating_icon_position]', array(
			'default'           => $this->settings->get_default( 'wpc_floating_icon_position' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_floating_icon_position]', array(
			'type'    => 'select',
			'section' => 'wpc_product_compare_design_element_style',
			'label'   => esc_html__( 'Icon position', 'compe-woo-compare-products' ),
			'choices' => array(
				'top-left'     => esc_html__( 'Top Left', 'compe-woo-compare-products' ),
				'top-right'    => esc_html__( 'Top Right', 'compe-woo-compare-products' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'compe-woo-compare-products' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'compe-woo-compare-products' ),

			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_floating_icon_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_floating_icon_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_floating_icon_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_element_style',
			'label'       => esc_html__( 'Icons size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_floating_icon_border]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_floating_icon_border' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_floating_icon_border]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_element_style',
			'label'       => esc_html__( 'Icon wrap rounded corner(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_floating_icon_color]', array(
			'default'           => $this->settings->get_default( 'wpc_floating_icon_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_floating_icon_color]',
				array(
					'label'    => esc_html__( 'Icon color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_floating_icon_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_floating_icon_background]', array(
			'default'           => $this->settings->get_default( 'wpc_floating_icon_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_floating_icon_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_floating_icon_background]',
				) )
		);

		//Side bar icon
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_element_style_side_bar]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_element_style_side_bar]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_element_style',
			'label'   => esc_html__( 'Side bar', 'compe-woo-compare-products' ),
		) );
		$wp_customize->selective_refresh->add_partial( 'woo_product_compare_params[wpc_element_style_side_bar]', array(
			'selector'            => '.woo-compare-bar-btn-icon-wrapper',
			'container_inclusive' => true,
			'fallback_refresh'    => false,
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_side_bar_horizontal]', array(
			'default'           => $this->settings->get_default( 'wpc_side_bar_horizontal' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_side_bar_horizontal]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_element_style',
			'label'    => esc_html__( 'Align', 'compe-woo-compare-products' ),
			'choices'  => array(
				'left'  => esc_html__( 'Left', 'compe-woo-compare-products' ),
				'right' => esc_html__( 'Right', 'compe-woo-compare-products' ),
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_side_bar_background_color]', array(
			'default'           => $this->settings->get_default( 'wpc_side_bar_background_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_side_bar_background_color]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_side_bar_background_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_side_bar_button_text]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_side_bar_button_text' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_side_bar_button_text]', array(
			'type'     => 'text',
			'section'  => 'wpc_product_compare_design_element_style',
			'settings' => 'woo_product_compare_params[wpc_side_bar_button_text]',
			'label'    => esc_html__( 'Button text', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_side_bar_button_background]', array(
			'default'           => $this->settings->get_default( 'wpc_side_bar_button_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_side_bar_button_background]',
				array(
					'label'    => esc_html__( 'Button background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_side_bar_button_background]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_side_bar_button_color]', array(
			'default'           => $this->settings->get_default( 'wpc_side_bar_button_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_side_bar_button_color]',
				array(
					'label'    => esc_html__( 'Button text color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_element_style',
					'settings' => 'woo_product_compare_params[wpc_side_bar_button_color]',
				) )
		);
	}

	protected function add_section_design_table_content( $wp_customize ) {
		$wp_customize->add_section( 'wpc_product_compare_design_table_content', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Table content', 'compe-woo-compare-products' ),
			'panel'          => 'wpc_product_compare_design',
		) );

		//table inner header
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_table_header]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_table_header]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_content',
			'label'   => esc_html__( 'Table header', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_image_display]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_table_header_image_display' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_header_image_display]', array(
			'type'        => 'checkbox',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Display image', 'compe-woo-compare-products' ),
			'description' => '',
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_title_display]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_table_header_title_display' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_header_title_display]', array(
			'type'        => 'checkbox',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Display title', 'compe-woo-compare-products' ),
			'description' => '',
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_cart_display]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_table_header_cart_display' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_header_cart_display]', array(
			'type'        => 'checkbox',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Display "Add to cart" button', 'compe-woo-compare-products' ),
			'description' => '',
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_image_size]', array(
			'default'           => $this->settings->get_default( 'wpc_table_header_image_size' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_header_image_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Image height', 'compe-woo-compare-products' ),
			'description' => '',
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_cart_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_header_cart_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_header_cart_color]',
				array(
					'label'    => esc_html__( 'Add to Cart color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_header_cart_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_cart_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_header_cart_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_header_cart_background]',
				array(
					'label'    => esc_html__( 'Add to Cart background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_header_cart_background]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_title_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_header_title_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_header_title_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Title font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_title_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_header_title_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_header_title_color]',
				array(
					'label'    => esc_html__( 'Title color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_header_title_color]',
				) )
		);

		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_text_align]', array(
			'default'           => $this->settings->get_default( 'wpc_table_header_text_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_header_text_align]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_table_content',
			'label'    => esc_html__( 'Align', 'compe-woo-compare-products' ),
			'choices'  => array(
				'left'   => esc_html__( 'Left', 'compe-woo-compare-products' ),
				'right'  => esc_html__( 'Right', 'compe-woo-compare-products' ),
				'center' => esc_html__( 'Center', 'compe-woo-compare-products' ),
			),
		) );

		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_header_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_header_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_header_background]',
				array(
					'label'    => esc_html__( 'Header background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_header_background]',
				) )
		);

		//fields header
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_field_header]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_field_header]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_content',
			'label'   => esc_html__( 'Fields header', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_header_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_content_header_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_header_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_header_align]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_header_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_header_align]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_table_content',
			'label'    => esc_html__( 'Align', 'compe-woo-compare-products' ),
			'choices'  => array(
				'left'   => esc_html__( 'Left', 'compe-woo-compare-products' ),
				'right'  => esc_html__( 'Right', 'compe-woo-compare-products' ),
				'center' => esc_html__( 'Center', 'compe-woo-compare-products' ),
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_header_font_weight]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_header_font_weight' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_header_font_weight]', array(
			'type'        => 'number',
			'priority'    => 10,
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Font weight', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 100,
				'max'  => 900,
				'step' => 100
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_header_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_header_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_content_header_color]',
				array(
					'label'    => esc_html__( 'Text color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_content_header_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_header_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_header_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_content_header_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_content_header_background]',
				) )
		);

		//content of table
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_content]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_content]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_content',
			'label'   => esc_html__( 'Content', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_content_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_content_content_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_content_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_content_align]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_content_align' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_content_align]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_table_content',
			'label'    => esc_html__( 'Align', 'compe-woo-compare-products' ),
			'choices'  => array(
				'left'   => esc_html__( 'Left', 'compe-woo-compare-products' ),
				'right'  => esc_html__( 'Right', 'compe-woo-compare-products' ),
				'center' => esc_html__( 'Center', 'compe-woo-compare-products' ),
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_content_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_content_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_content_content_color]',
				array(
					'label'    => esc_html__( 'Text color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_content_content_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_content_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_content_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_content_content_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_content_content_background]',
				) )
		);

		//border of table
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_border]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_border]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_content',
			'label'   => esc_html__( 'Border', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_border_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => $this->settings->get_default( 'wpc_table_content_border_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_border_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_content',
			'label'       => esc_html__( 'Width(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 5,
				'step' => 0.5
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_border_style]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_border_style' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_border_style]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_table_content',
			'label'    => esc_html__( 'Style', 'compe-woo-compare-products' ),
			'choices'  => array(
				'dotted' => esc_html__( 'Dotted', 'compe-woo-compare-products' ),
				'dashed' => esc_html__( 'Dashed', 'compe-woo-compare-products' ),
				'solid'  => esc_html__( 'Solid', 'compe-woo-compare-products' ),
				'double' => esc_html__( 'Double', 'compe-woo-compare-products' ),
				'groove' => esc_html__( 'Groove', 'compe-woo-compare-products' ),
				'ridge'  => esc_html__( 'Ridge', 'compe-woo-compare-products' ),
				'inset'  => esc_html__( 'Inset', 'compe-woo-compare-products' ),
				'outset' => esc_html__( 'Outset', 'compe-woo-compare-products' ),
				'hidden' => esc_html__( 'Hidden', 'compe-woo-compare-products' ),
				'none'   => esc_html__( 'None', 'compe-woo-compare-products' ),
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_border_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_content_border_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_content_border_color]',
				array(
					'label'    => esc_html__( 'Color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_content',
					'settings' => 'woo_product_compare_params[wpc_table_content_border_color]',
				) )
		);

		//alter table
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_content_alternating]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_content_alternating]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_content',
			'label'   => esc_html__( 'Alternating color', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_alternating_type]', array(
			'default'           => $this->settings->get_default( 'wpc_table_alternating_type' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_alternating_type]', array(
			'type'     => 'select',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_table_content',
			'label'    => esc_html__( 'Type', 'compe-woo-compare-products' ),
			'choices'  => array(
				''    => esc_html__( 'None', 'compe-woo-compare-products' ),
				'row' => esc_html__( 'Row', 'compe-woo-compare-products' ),
				'col' => esc_html__( 'Column', 'compe-woo-compare-products' ),
			),
		) );

		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_alternating_row_odd]', array(
			'default'           => $this->settings->get_default( 'wpc_table_alternating_row_odd' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_alternating_row_odd]',
				array(
					'label'           => esc_html__( 'Odd rows color', 'compe-woo-compare-products' ),
					'section'         => 'wpc_product_compare_design_table_content',
					'settings'        => 'woo_product_compare_params[wpc_table_alternating_row_odd]',
					'active_callback' => array( $this, 'wpc_alter_row_callback' ),
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_alternating_row_even]', array(
			'default'           => $this->settings->get_default( 'wpc_table_alternating_row_even' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_alternating_row_even]',
				array(
					'label'           => esc_html__( 'Even rows color', 'compe-woo-compare-products' ),
					'section'         => 'wpc_product_compare_design_table_content',
					'settings'        => 'woo_product_compare_params[wpc_table_alternating_row_even]',
					'active_callback' => array( $this, 'wpc_alter_row_callback' ),
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_alternating_col_odd]', array(
			'default'           => $this->settings->get_default( 'wpc_table_alternating_col_odd' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_alternating_col_odd]',
				array(
					'label'           => esc_html__( 'Odd columns color', 'compe-woo-compare-products' ),
					'section'         => 'wpc_product_compare_design_table_content',
					'settings'        => 'woo_product_compare_params[wpc_table_alternating_col_odd]',
					'active_callback' => array( $this, 'wpc_alter_col_callback' ),
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_alternating_col_even]', array(
			'default'           => $this->settings->get_default( 'wpc_table_alternating_col_even' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_alternating_col_even]',
				array(
					'label'           => esc_html__( 'Even columns color', 'compe-woo-compare-products' ),
					'section'         => 'wpc_product_compare_design_table_content',
					'settings'        => 'woo_product_compare_params[wpc_table_alternating_col_even]',
					'active_callback' => array( $this, 'wpc_alter_col_callback' ),
				) )
		);
	}

	protected function add_section_design_table_buttons( $wp_customize ) {
		$wp_customize->add_section( 'wpc_product_compare_design_table_buttons', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Table buttons', 'compe-woo-compare-products' ),
			'panel'          => 'wpc_product_compare_design',
		) );

		//clear button
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_buttons_clear]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_buttons_clear]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_buttons',
			'label'   => esc_html__( 'Clear button', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_clear_font_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_clear_font_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_clear_font_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_buttons',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_clear_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_clear_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_clear_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_buttons',
			'label'       => esc_html__( 'Size(%)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_clear_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_clear_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_clear_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_buttons',
			'label'       => esc_html__( 'Button rounded corner(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_clear_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_clear_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_clear_color]',
				array(
					'label'    => esc_html__( 'Color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_clear_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_clear_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_clear_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_clear_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_clear_background]',
				) )
		);

		//select fields button
		$wp_customize->selective_refresh->add_partial( 'woo_product_compare_params[wpc_table_buttons_select]', array(
			'selector'            => '.woo-compare-table-button-setting',
			'container_inclusive' => true,
			'fallback_refresh'    => false, // Pre
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_buttons_select]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_buttons_select]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_buttons',
			'label'   => esc_html__( 'Setting button', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_select_font_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_select_font_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_select_font_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_buttons',
			'label'       => esc_html__( 'Font size(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_select_size]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_select_size' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_select_size]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_buttons',
			'label'       => esc_html__( 'Size(%)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_select_border_radius]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'absint',
			'default'           => $this->settings->get_default( 'wpc_table_select_border_radius' ),
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_select_border_radius]', array(
			'type'        => 'number',
			'section'     => 'wpc_product_compare_design_table_buttons',
			'label'       => esc_html__( 'Button rounded corner(px)', 'compe-woo-compare-products' ),
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 100,
				'step' => 1
			),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_select_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_select_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_select_color]',
				array(
					'label'    => esc_html__( 'Color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_select_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_select_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_select_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_select_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_select_background]',
				) )
		);

		//search button
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_buttons_search]', array(
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage'
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_table_buttons_search]', array(
			'type'    => 'url',
			'section' => 'wpc_product_compare_design_table_buttons',
			'label'   => esc_html__( 'Search field', 'compe-woo-compare-products' ),
		) );
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_search_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_search_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_search_color]',
				array(
					'label'    => esc_html__( 'Color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_search_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_search_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_search_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_search_background]',
				array(
					'label'    => esc_html__( 'Background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_search_background]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_search_btn_color]', array(
			'default'           => $this->settings->get_default( 'wpc_table_search_btn_color' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_search_btn_color]',
				array(
					'label'    => esc_html__( 'Search button color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_search_btn_color]',
				) )
		);
		$wp_customize->add_setting( 'woo_product_compare_params[wpc_table_search_btn_background]', array(
			'default'           => $this->settings->get_default( 'wpc_table_search_btn_background' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'woo_product_compare_params[wpc_table_search_btn_background]',
				array(
					'label'    => esc_html__( 'Search button background color', 'compe-woo-compare-products' ),
					'section'  => 'wpc_product_compare_design_table_buttons',
					'settings' => 'woo_product_compare_params[wpc_table_search_btn_background]',
				) )
		);
	}

	protected function add_section_design_custom_css( $wp_customize ) {

		$wp_customize->add_section( 'wpc_product_compare_design_custom_css', array(
			'priority'       => 20,
			'capability'     => 'manage_options',
			'theme_supports' => '',
			'title'          => esc_html__( 'Custom CSS', 'compe-woo-compare-products' ),
			'panel'          => 'wpc_product_compare_design',
		) );

		$wp_customize->add_setting( 'woo_product_compare_params[wpc_custom_css]', array(
			'default'           => $this->settings->get_default( 'wpc_custom_css' ),
			'type'              => 'option',
			'capability'        => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( 'woo_product_compare_params[wpc_custom_css]', array(
			'type'     => 'textarea',
			'priority' => 10,
			'section'  => 'wpc_product_compare_design_custom_css',
			'label'    => esc_html__( 'Custom CSS', 'compe-woo-compare-products' )
		) );
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

	function wpc_sanitize_float( $input ) {
		return filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
	}

	function wpc_sanitize_radio( $input, $setting ) {
		//input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
		$input = sanitize_key( $input );
		//get the list of possible radio box options
		$choices = $setting->manager->get_control( $setting->id )->choices;

		//return input if valid or return default option
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	function wpc_sanitize_checkbox( $input ) {
		return ( isset( $input ) ? true : false );
	}

	function wpc_btn_archive_hover_active_callback() {
		return in_array( $this->settings->get_params( 'wpc_btn_archive_pos' ), array( 'on_image_left', 'on_image_right' ) );
	}

	function wpc_btn_archive_background_callback() {
		return ! in_array( $this->settings->get_params( 'wpc_btn_archive_pos' ), array( 'on_image_left', 'on_image_right' ) );
	}

	function wpc_alter_row_callback() {
		return $this->settings->get_params( 'wpc_table_alternating_type' ) == 'row';
	}

	function wpc_alter_col_callback() {
		return $this->settings->get_params( 'wpc_table_alternating_type' ) == 'col';
	}

	function wpc_sanitize_select( $input, $setting ) {
		//input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
		$input = sanitize_key( $input );
		//get the list of possible select options
		$choices = $setting->manager->get_control( $setting->id )->choices;

		//return input if valid or return default option
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

	}

}