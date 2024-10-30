<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_PRODUCT_COMPARE_DATA {
	private $params;
	private $default;

	/**
	 * VI_WOO_PRODUCT_COMPARE_DATA constructor.
	 * Init setting
	 */
	public function __construct() {
		global $product_compare_settings;
		if ( ! $product_compare_settings ) {
			$product_compare_settings = get_option( 'woo_product_compare_params', array() );
		}
		$this->default = array(
			//General
			'wpc_page_compare'               => '',
			'wpc_popup_compare'              => 1,
			'wpc_btn_redirect'               => 1,
			'wpc_conditional_tag'            => '',
			'wpc_popup_transition'           => 'wpc-popup-effect-1',
			'wpc_limit_compare'              => 7,
			'wpc_limit_notice'               => '',
			'wpc_remove_mode'                => 0,
			'wpc_text_remove'                => 'Remove compare',
			'wpc_open_custom_cart'           => 1,
			'wpc_list_product_mode'          => 1,
			'wpc_load_ajax_mode'             => 1,
			'wpc_export_file'                => 0,

			/*Compare button
			 * Under title: 6
			 * Under price & rating: 11
			 * Above add to cart: 29
			 * Under add to cart: 31*/
			'wpc_btn_single_pos'             => '31',
			'wpc_btn_compare_text'           => 'Compare',
			'wpc_btn_added_text'             => 'Open Compare',
			'wpc_btn_compare_list'           => '',
			'wpc_btn_compare_icon'           => 'wpc_icon_compare-share-files',
			'wpc_btn_compare_font_size'      => 16,
			'wpc_btn_compare_color'          => '',
			'wpc_btn_compare_background'     => '',
			'wpc_btn_compare_hover'          => '',
			'wpc_btn_compare_added'          => '',

			//Compare icon
//			on_image_left
//			on_image_right
//			after_title
//			after_rating
//			after_price
//			before_add_to_cart
//			after_add_to_cart
			'wpc_btn_archive_pos'            => 'on_image_right',
			'wpc_btn_archive_size'           => 20,
			'wpc_btn_archive_color'          => '',
			'wpc_btn_archive_background'     => '',
			'wpc_btn_archive_hover'          => '',
			'wpc_btn_archive_added'          => '',
			'wpc_btn_archive_enable_hover'   => false,


			//Side bar
			'wpc_open_sidebar'               => 1,
			'wpc_side_bar_hide_empty'        => 0,
			'wpc_side_bar_horizontal'        => 'right',
			'wpc_side_bar_vertical'          => 'bottom',
			'wpc_side_bar_background_color'  => '#f8f8f8',
			'wpc_side_bar_button_color'      => '#000000',
			'wpc_side_bar_button_background' => '#bfbfbf',
			'wpc_side_bar_button_text'       => 'Compare Product',
			'wpc_side_bar_outside_close'     => 0,
//			'wpc_side_bar_button_open'       => 1,

			//slide
			'wpc_slide_product_num'          => 3,
			'wpc_slide_product_num_mob'      => 2,

			//Table compare
			'wpc_link_product_direct'        => 'blank',
			'wpc_popup_header_text'          => 'Product compare',
			'wpc_popup_header_size'          => 20,
			'wpc_popup_header_padding'       => 10,
			'wpc_popup_header_margin'        => 5,
			'wpc_popup_header_color'         => '#4b4c36',
			'wpc_popup_header_background'    => '#ffffff',
			'wpc_popup_header_align'         => 'center',

			'wpc_table_header_image_display'   => true,
			'wpc_table_header_title_display'   => true,
			'wpc_table_header_cart_display'    => true,
			'wpc_table_header_image_size'    => '',
			'wpc_table_header_cart_font_size'  => '',
			'wpc_table_header_cart_color'      => '',
			'wpc_table_header_cart_background' => '',
			'wpc_table_header_title_size'      => 18,
			'wpc_table_header_title_color'     => '#4b4c36',
			'wpc_table_header_text_align'      => 'left',
			'wpc_table_header_background'      => '#f3f3f3',

			'wpc_table_content_header_size'        => 16,
			'wpc_table_content_header_align'       => 'left',
			'wpc_table_content_header_font_weight' => '500',
			'wpc_table_content_header_color'       => '#4b4c36',
			'wpc_table_content_header_background'  => '#ffffff',
			'wpc_table_content_content_size'       => 16,
			'wpc_table_content_content_align'      => 'left',
			'wpc_table_content_content_color'      => '#4b4c36',
			'wpc_table_content_content_background' => '#ffffff',
			'wpc_table_content_border_size'        => 1,
			'wpc_table_content_border_style'       => 'solid',
			'wpc_table_content_border_color'       => '#cecece',
			'wpc_table_alternating_type'           => 'row',
			'wpc_table_alternating_row'            => true,
			'wpc_table_alternating_col'            => false,
			'wpc_table_alternating_row_odd'        => '#f6f6f6',
			'wpc_table_alternating_row_even'       => '#ffffff',
			'wpc_table_alternating_col_odd'        => '#f6f6f6',
			'wpc_table_alternating_col_even'       => '#ffffff',

			'wpc_table_clear_font_size'       => 18,
			'wpc_table_clear_size'            => 50,
			'wpc_table_clear_border_radius'   => 0,
			'wpc_table_clear_color'           => '',
			'wpc_table_clear_background'      => '',
			'wpc_table_select_font_size'      => 18,
			'wpc_table_select_size'           => 50,
			'wpc_table_select_border_radius'  => 0,
			'wpc_table_select_color'          => '',
			'wpc_table_select_background'     => '',
			'wpc_table_export_font_size'      => 16,
			'wpc_table_export_size'           => 50,
			'wpc_table_export_border_radius'  => 0,
			'wpc_table_export_color'          => '',
			'wpc_table_export_background'     => '',
			'wpc_table_search_size'           => 11,
			'wpc_table_search_background'     => '#ffffff',
			'wpc_table_search_color'          => '',
			'wpc_table_search_btn_background' => '#eeeeee',
			'wpc_table_search_btn_color'      => '#828282',

			'wpc_table_expand_text'         => 'more',
			'wpc_table_shrink_text'         => 'less',
			'wpc_table_content_length'      => '200',

			//Widget
//			'wpc_widget_button_open'        => '0',
			'wpc_widget_search'             => '1',
			'wpc_widget_header_text'        => 'Product Compare',
			'wpc_widget_header_size'        => 18,
			'wpc_widget_header_color'       => '',
			'wpc_widget_header_background'  => '',
			'wpc_widget_compare_text_size'  => 14,
			'wpc_widget_compare_text_color' => '',
			'wpc_widget_compare_background' => '',
			'wpc_widget_clear_size'         => 14,
			'wpc_widget_clear_color'        => '',
			'wpc_widget_clear_background'   => '',

			//Floating icon
			//Enable
//			'wpc_open_floating_icon'        => 1,
			'wpc_floating_icon_position'    => 'bottom-left',
			'wpc_floating_icon'             => 'wpc_icon_compare-share-files',
			'wpc_floating_icon_size'        => 25,
			'wpc_floating_icon_border'      => 25,
			'wpc_floating_icon_color'       => '#ffffff',
			'wpc_floating_icon_background'  => '#9632dc',
			//Redirect
			'wpc_floating_icon_open'        => 0,

			//display content
			'wpc_display_sku'               => array( 1, 'SKU' ),
			'wpc_sku_title'                 => 'SKU',
			'wpc_image_title'               => 'Image',
			'wpc_stock_title'               => 'Stock',
			'wpc_custom_css'                => '',
			'wpc_sticky_elements'           => array(
				array(
					0,
					'#vi-woo-compare-page-table .woo-compare-table-field-header-freeze',
				),
				array(
					0,
					'#vi-woo-compare-page-table .woo-compare-table-row-freeze',
				),
			),
			'wpc_icon_main'                 => array(
				"wpc_icon_compare-share-files",
				"wpc_icon_compare-ab-testing",
				"wpc_icon_compare-ab-testing-1",
				"wpc_icon_compare-compare",
				"wpc_icon_compare-compare-1",
				"wpc_icon_compare-compare-2",
				"wpc_icon_compare-comparative",
				"wpc_icon_compare-risk",
				"wpc_icon_compare-compare-3",
				"wpc_icon_compare-compare-4",
				"wpc_icon_compare-compare-5",
				"wpc_icon_compare-decision",
				"wpc_icon_compare-advantages",
				"wpc_icon_compare-computer",
				"wpc_icon_compare-diagram",
				"wpc_icon_compare-balance",
				"wpc_icon_compare-file",
				"wpc_icon_compare-lists",
				"wpc_icon_compare-website",
				"wpc_icon_compare-skill",
				"wpc_icon_compare-file-1",
				"wpc_icon_compare-phone",
				"wpc_icon_compare-compare-6",
				"wpc_icon_compare-compare-7"
			),
			'wpc_blocks'                    => wp_json_encode(
				array(
					array(
						'sku',
						'Sku',
						0,
					),
					array(
						'rating',
						'Rating',
						1,
					),
					array(
						'price',
						'Price',
						1,
					),
					array(
						'totalSale',
						'Total Sale',
						1,
					),
					array(
						'stock',
						'Stock',
						0,
					),
					array(
						'shortDes',
						'Short Description',
						1,
					),
					array(
						'description',
						'Description',
						1,
					),
					array(
						'weight',
						'Weight',
						0,
					),
					array(
						'dimensions',
						'Dimensions',
						0,
					),
					array(
						'tags',
						'Tags',
						0,
					),
					array(
						'categories',
						'Categories',
						0,
					),
					array(
						'comments',
						'Total Comments',
						0,
					),
					array(
						'shipping',
						'Shipping',
						0,
					),
				)
			),
		);
		$this->attribute_taxonomies();
		$this->params = apply_filters( 'woo_product_compare_params', wp_parse_args( $product_compare_settings, $this->default ) );
	}

	/**
	 * Get params of setting
	 * @return mixed
	 */
	public function get_params( $name = "" ) {
		if ( ! $name ) {
			return $this->params;
		} elseif ( isset( $this->params[ $name ] ) ) {
			return apply_filters( 'woo_product_compare_params' . $name, $this->params[ $name ] );
		} else {
			return false;
		}
	}

	/**
	 * Get default param setting
	 * @return mixed
	 */
	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'woo_product_compare_params_default' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public function set_attribute( $arr = array( '' ) ) {
		if ( ! $arr ) {
			return false;
		} elseif ( isset( $this->default['wpc_blocks'] ) ) {
			$param_default = json_decode( $this->default['wpc_blocks'] );
			if ( is_array( $arr ) && count( $arr ) ) {
				$arr_attribute = array();
				foreach ( $arr as $row_key => $row_value ) {
					$att_slug      = $row_value->attribute_name;
					$att_name      = $row_value->attribute_label;
					$src_attribute = array( $att_slug, $att_name, 1 );
					array_push( $arr_attribute, $src_attribute );
				}
				foreach ( $arr_attribute as $row_key => $row_value ) {
					$aval = true;
					foreach ( $param_default as $block => $block_value ) {
						if ( $block_value[0] == $row_value[0] ) {
							$aval = false;
							break;
						};
					}
					if ( $aval ) {
						array_push( $param_default, array( $row_value[0], $row_value[1], 1 ) );
					}
				}
			}
			$this->set_params( $param_default );

			return true;
		}

		return false;
	}

	public function set_params( $arr = array() ) {
		$this->default['wpc_blocks'] = wp_json_encode( $arr );
	}

	public function attribute_taxonomies() {

		global $wpdb;

		$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name != '' ORDER BY attribute_name ASC;" );// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );

		$attribute_taxonomies = array_filter( $attribute_taxonomies );

		$this->set_attribute( $attribute_taxonomies );
	}
}

new VI_WOO_PRODUCT_COMPARE_DATA();