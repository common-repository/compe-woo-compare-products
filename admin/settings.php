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

class VI_WOO_PRODUCT_COMPARE_Admin_Settings {
	protected $settings;

	public function __construct() {
		//admin init
		$this->settings = new VI_WOO_PRODUCT_COMPARE_DATA();
		add_action( 'admin_menu', array( $this, 'menu_page' ), 998 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
		add_action( 'admin_init', array( $this, 'save_data_product_compare' ), 99 );
		add_action( 'wp_ajax_wpc_search_page', array( $this, 'wpc_search_page' ) );
	}

	/**
	 * Save data.
	 */
	public function save_data_product_compare() {
		global $product_compare_settings;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_wooproductcompare_nonce'] ) || ! wp_verify_nonce( $_POST['_wooproductcompare_nonce'], 'wooproductcompare_action_nonce' ) ) {
			return;
		}
		$args = array(
			//General
			'wpc_page_compare'         => '',
			'wpc_popup_compare'        => 0,
			'wpc_conditional_tag'      => '',
			'wpc_btn_redirect'         => 0,
			'wpc_popup_transition'     => '',
			'wpc_limit_compare'        => 0,
			'wpc_remove_mode'          => 0,
			'wpc_text_remove'          => '',
//			'wpc_open_floating_icon'   => 0,
			'wpc_open_custom_cart'     => 0,
			'wpc_open_sidebar'         => 0,
			'wpc_side_bar_hide_empty'  => 0,
			'wpc_side_bar_button_open' => 0,
			'wpc_widget_button_open'   => 0,
			'wpc_floating_icon_open'   => 0,
			'wpc_list_product_mode'    => 0,
			'wpc_load_ajax_mode'       => 0,
			'wpc_export_file'          => 0,

			//Compare button
			'wpc_btn_compare_text'     => '',
			'wpc_btn_added_text'       => '',
			'wpc_btn_compare_list'     => '',
//			'wpc_btn_compare_icon'     => '',
		);
		foreach ( $args as $key => $arg ) {
			$args[ $key ] = isset( $_POST[ $key ] ) ? sanitize_text_field( $_POST[ $key ] ) : '';
		}
		$args = wp_parse_args( $args, get_option( 'woo_product_compare_params', $product_compare_settings ) );
		update_option( 'woo_product_compare_params', $args );
		$product_compare_settings = $args;
	}

	/**
	 * Register a custom menu page.
	 */
	public function menu_page() {
		add_menu_page(
			esc_html__( 'Compe', 'compe-woo-compare-products' ),
			esc_html__( 'Compe', 'compe-woo-compare-products' ),
			'manage_options', 'compe-woo-compare-products',
			array( $this, 'setting_page_woo_product_compare' ),
			VI_WOO_PRODUCT_COMPARE_IMAGES . 'icon.svg', 2
		);
	}

	public function wpc_search_page() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );

		if ( empty( $keyword ) ) {
			die();
		}
		$arg        = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query  = new WP_Query( $arg );
		$found_page = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$wpc_page_id    = get_the_ID();
				$wpc_page_title = get_the_title();
				$wpc_page       = array( 'id' => $wpc_page_id, 'text' => $wpc_page_title );
				$found_page[]   = $wpc_page;
			}
		}
		wp_send_json( $found_page );
	}

	public function setting_page_woo_product_compare() {
		$this->settings = new VI_WOO_PRODUCT_COMPARE_DATA();
		$wpc_icon       = $this->settings->get_params( 'wpc_btn_compare_icon' );
		$wpc_icon_array = $this->settings->get_params( 'wpc_icon_main' );
		$wpc_page_args  = array(
			'depth'                 => 0,
			'child_of'              => 0,
			'selected'              => $this->settings->get_params( 'wpc_page_compare' ) != '' ? $this->settings->get_params( 'wpc_page_compare' ) : get_option( 'wpc_plugin_page_id' ),
			'echo'                  => 1,
			'name'                  => 'wpc_page_compare',
			'id'                    => 'wpc_page_compare',
			'class'                 => 'vi-ui fluid dropdown',
			'show_option_no_change' => '',
			'option_none_value'     => '',
			'value_field'           => 'ID',
		);
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Compe - Products Compare for WooCommerce', 'compe-woo-compare-products' ); ?></h2>
            <div class="vi-ui raised">
                <form class="vi-ui form" method="post" action="">
					<?php
					wp_nonce_field( 'wooproductcompare_action_nonce', '_wooproductcompare_nonce' );
					settings_fields( 'compe-woo-compare-products' );
					do_settings_sections( 'compe-woo-compare-products' );
					?>
                    <div class="vi-ui segment">
                        <table class="vi-ui bottom attached form-table">
                            <tbody>
                            <tr valign="top">
                                <th scope="row">
                                    <h4 class="vi-ui blue header"><?php esc_html_e( 'General', 'compe-woo-compare-products' ); ?></h4>
                                </th>
                            </tr>
                            <tr valign="top" class="wpc_page_compare">
                                <th scope="row">
                                    <label for="wpc_page_compare">
										<?php esc_html_e( 'Comparison page', 'compe-woo-compare-products' ) ?></label>
                                </th>
                                <td>
                                    <select id="wpc_page_compare" name="wpc_page_compare"
                                            class="wpc-page-search"
                                            data-placeholder="<?php esc_html_e( 'Please Fill In Your Product Title', 'compe-woo-compare-products' ) ?>">
										<?php
										$wpc_data_page = $this->settings->get_params( 'wpc_page_compare' );
										if ( isset( $wpc_data_page ) && ! empty( $wpc_data_page ) ) {
											$wpc_page_ids = $wpc_data_page;
										} else {
											$wpc_page_ids = get_option( 'wpc_plugin_page_id' );
										}
										$wpc_page = get_the_title( $wpc_page_ids );
										if ( $wpc_page ) {
											?>
                                            <option selected
                                                    value="<?php echo esc_attr( $wpc_page_ids ) ?>"><?php echo esc_html( $wpc_page ) ?></option>
											<?php
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_popup_compare">
										<?php esc_html_e( 'Popup', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="wpc_popup_compare" <?php checked( $this->settings->get_params( 'wpc_popup_compare' ), 1 ) ?>
                                               name="wpc_popup_compare" value="1">
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="wpc_popup_compare_att">
                                <th scope="row">
                                    <label for="wpc_conditional_tag">
										<?php esc_html_e( 'Conditional tag', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" id="wpc_conditional_tag" tabindex="2" name="wpc_conditional_tag"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wpc_conditional_tag' ) ); ?>"
                                           placeholder="<?php echo esc_html( 'Ex: !is_page(array(123,41,20))' ) ?>">
                                    <p class="description"><?php esc_html_e( 'Let you control on which pages compare elements (popup, floating icon, side bar) will appear using ', 'compe-woo-compare-products' ) ?>
                                        <a href="http://codex.wordpress.org/Conditional_Tags"><?php esc_html_e( 'WP\'s conditional tags', 'compe-woo-compare-products' ) ?></a>
                                    </p>
                                    <p class="description">
                                        <strong>*</strong><?php esc_html_e( 'Combining 2 or more conditionals using || and &&. If 1 of the conditionals matched. e.g: use ', 'compe-woo-compare-products' ); ?>
                                        <strong><?php echo esc_html( 'is_cart() ||
                                        is_checkout()' ) ?></strong><?php esc_html_e( ' to show only on cart page and checkout page. Use ', 'compe-woo-compare-products' ) ?>
                                        <strong><?php echo esc_html( '!is_front_page() && is_page( array( 42, 54, 6 ) )' ) ?></strong><?php
										esc_html_e( ' to show only on page have post id 42, 54 or 6 and it isn\'t home page. It is a conditional expression.', 'compe-woo-compare-products' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr valign="top" class="wpc_popup_compare_att">
                                <th scope="row">
                                    <label for="wpc_popup_transition">
										<?php esc_html_e( 'Popup effect', 'compe-woo-compare-products' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" id="wpc_popup_transition"
                                            name="wpc_popup_transition" tabindex="3">
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-1' ) ?>
                                                value="wpc-popup-effect-1"><?php esc_html_e( 'Fade in and scale up', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-2' ) ?>
                                                value="wpc-popup-effect-2"><?php esc_html_e( 'Slide from the right', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-3' ) ?>
                                                value="wpc-popup-effect-3"><?php esc_html_e( 'Slide from the bottom', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-4' ) ?>
                                                value="wpc-popup-effect-4"><?php esc_html_e( 'Newspaper', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-5' ) ?>
                                                value="wpc-popup-effect-5"><?php esc_html_e( 'Fall', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-6' ) ?>
                                                value="wpc-popup-effect-6"><?php esc_html_e( 'Side fall', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-7' ) ?>
                                                value="wpc-popup-effect-7"><?php esc_html_e( 'Slide and stick to top', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-8' ) ?>
                                                value="wpc-popup-effect-8"><?php esc_html_e( '3D flip horizontal', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-9' ) ?>
                                                value="wpc-popup-effect-9"><?php esc_html_e( '3D flip vertical', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-10' ) ?>
                                                value="wpc-popup-effect-10"><?php esc_html_e( '3D sign', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-11' ) ?>
                                                value="wpc-popup-effect-11"><?php esc_html_e( 'Super scaled', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-12' ) ?>
                                                value="wpc-popup-effect-12"><?php esc_html_e( 'Just me', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-13' ) ?>
                                                value="wpc-popup-effect-13"><?php esc_html_e( '3D slit', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-14' ) ?>
                                                value="wpc-popup-effect-14"><?php esc_html_e( '3D Rotate from bottom', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_popup_transition' ), 'wpc-popup-effect-15' ) ?>
                                                value="wpc-popup-effect-15"><?php esc_html_e( '3D Rotate in from left', 'compe-woo-compare-products' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="">
                                <th scope="row">
                                    <label for="wpc_btn_redirect">
										<?php esc_html_e( 'Action after click compared', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" id="wpc_btn_redirect" tabindex="0"
                                            name="wpc_btn_redirect">
                                        <option <?php selected( $this->settings->get_params( 'wpc_btn_redirect' ), 0 ) ?>
                                                value="0"><?php esc_html_e( 'Popup', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_btn_redirect' ), 1 ) ?>
                                                value="1"><?php esc_html_e( 'Page', 'compe-woo-compare-products' ) ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Screen redirect after click on added product button compare', 'compe-woo-compare-products' ); ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_limit_compare">
										<?php esc_html_e( 'Limit compared item', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="number" id="wpc_limit_compare" min="1" max="15" tabindex="4"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wpc_limit_compare' ) ); ?>"
                                           name="wpc_limit_compare">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_remove_mode">
										<?php esc_html_e( 'Remove button after add to list', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="wpc_remove_mode" <?php checked( $this->settings->get_params( 'wpc_remove_mode' ), 1 ) ?>
                                               name="wpc_remove_mode" value="1">
                                        <label></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Enable this to allow customer remove selected items in the comparison table with a Remove button', 'compe-woo-compare-products' ); ?></p>
                                </td>
                            </tr>
                            <tr valign="top" class="wpc_remove_text">
                                <th scope="row">
                                    <label for="wpc_text_remove">
										<?php esc_html_e( 'Remove button text', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" id="wpc_text_remove" tabindex="6"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wpc_text_remove' ) ); ?>"
                                           name="wpc_text_remove">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_open_mode">
										<?php esc_html_e( 'Enable sidebar', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="wpc_open_sidebar" <?php checked( $this->settings->get_params( 'wpc_open_sidebar' ), 1 ) ?>
                                               name="wpc_open_sidebar" value="1">
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="wpc_side_bar_hide_empty">
                                <th scope="row">
                                    <label for="wpc_side_bar_hide_empty">
										<?php esc_html_e( 'Hide sidebar/floating icon when compare list empty', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="wpc_side_bar_hide_empty" <?php checked( $this->settings->get_params( 'wpc_side_bar_hide_empty' ), 1 ) ?>
                                               name="wpc_side_bar_hide_empty" value="1">
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="wpc_floating_icon_open">
                                <th scope="row">
                                    <label for="wpc_floating_icon_open">
										<?php esc_html_e( 'Floating icon open', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" id="wpc_floating_icon_open" tabindex="0"
                                            name="wpc_floating_icon_open">-->
                                        <option <?php selected( $this->settings->get_params( 'wpc_floating_icon_open' ), 0 ) ?>
                                                value="0"><?php esc_html_e( 'Popup', 'compe-woo-compare-products' ) ?></option>
                                        <option <?php selected( $this->settings->get_params( 'wpc_floating_icon_open' ), 1 ) ?>
                                                value="1"><?php esc_html_e( 'Page', 'compe-woo-compare-products' ) ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Screen redirect after click on floating icon button', 'compe-woo-compare-products' ); ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_list_product_mode">
										<?php esc_html_e( 'Compare button on the Product Catalog', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               id="wpc_list_product_mode" <?php checked( $this->settings->get_params( 'wpc_list_product_mode' ), 1 ) ?>
                                               name="wpc_list_product_mode" value="1">
                                        <label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <h4 class="vi-ui blue header"><?php esc_html_e( 'Button compare', 'compe-woo-compare-products' ); ?></h4>
                                </th>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_btn_compare_text">
										<?php esc_html_e( 'Text', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" id="wpc_btn_compare_text"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wpc_btn_compare_text' ) ); ?>"
                                           name="wpc_btn_compare_text">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_btn_added_text">
										<?php esc_html_e( 'Compared text', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <input type="text" id="wpc_btn_added_text"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wpc_btn_added_text' ) ); ?>"
                                           name="wpc_btn_added_text">
                                    <p class="description"><?php esc_html_e( 'Display text of button compare after add to compare list', 'compe-woo-compare-products' ); ?></p>
                                </td>
                            </tr>
                            <!--                            <tr valign="top">-->
                            <!--                                <th scope="row">-->
                            <!--                                    <label for="wpc_btn_compare_icon">-->
                            <!--										--><?php //esc_html_e( 'Icon', 'compe-woo-compare-products' ) ?>
                            <!--                                    </label>-->
                            <!--                                </th>-->
                            <!--                                <td>-->
                            <!--                                    <div class="wpc-radio-icons-wrap">-->
                            <!--										--><?php
							//										foreach ( $wpc_icon_array as $arr_k => $arr_v ) {
							//											?>
                            <!--                                            <label class="wpc-radio-icons-label -->
							<?php //if ( $wpc_icon == $arr_v )
							//												echo esc_attr( 'wpc-radio-icons-active' ) ?><!--">-->
                            <!--                                                <input type="radio" class="wpc-radio-icons" name="wpc_btn_compare_icon" value="-->
							<?php //echo esc_attr( $arr_v ) ?><!--"-->
                            <!--                                                       data-customize-setting-link="wpc-radio-icons"-->
                            <!--													-->
							<?php //checked( $this->settings->get_params( 'wpc_btn_compare_icon' ), $arr_v ) ?><!-->
                            <!--                                                <i class="-->
							<?php //echo esc_attr( $arr_v ) ?><!--"></i>-->
                            <!--                                            </label>-->
                            <!--											--><?php
							//										}
							//										?>
                            <!--                                    </div>-->
                            <!--                                </td>-->
                            <!--                            </tr>-->
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wpc_design_button">
										<?php esc_html_e( 'Design', 'compe-woo-compare-products' ) ?>
                                    </label>
                                </th>
                                <td>
                                    <a href="<?php echo esc_attr( 'customize.php?autofocus[panel]=wpc_product_compare_design' ) ?>"
                                       target="_blank"><?php esc_html_e( 'Go to design now', 'compe-woo-compare-products' ) ?></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>
                        <input type="submit" name="wpc_save_data"
                               value="<?php esc_html_e( 'Save', 'compe-woo-compare-products' ); ?>"
                               class="vi-ui primary button">
                    </p>
                </form>
            </div>
        </div>
		<?php
		do_action( 'villatheme_support_compe-woo-compare-products' );
	}

	/**
	 *  Include style and script
	 */
	public function admin_enqueue_script() {
		if ( isset( $_REQUEST['compe_admin_nonce'] ) && ! wp_verify_nonce( wc_clean( wp_unslash( $_REQUEST['compe_admin_nonce'] ) ), 'compe_admin_nonce' ) ) {
			return;
		}
		$page = isset( $_REQUEST['page'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['page'] ) ) : '';
		if ( $page == 'compe-woo-compare-products' ) {
			$suffix = WP_DEBUG ? '' : 'min.';
			// style
			wp_enqueue_style( 'compe-woo-compare-products-button', VI_WOO_PRODUCT_COMPARE_CSS . 'button.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-table', VI_WOO_PRODUCT_COMPARE_CSS . 'table.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-form', VI_WOO_PRODUCT_COMPARE_CSS . 'form.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-dropdown', VI_WOO_PRODUCT_COMPARE_CSS . 'dropdown.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-transition', VI_WOO_PRODUCT_COMPARE_CSS . 'transition.min.css', [], VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-checkbox', VI_WOO_PRODUCT_COMPARE_CSS . 'checkbox.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-menu', VI_WOO_PRODUCT_COMPARE_CSS . 'menu.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-header', VI_WOO_PRODUCT_COMPARE_CSS . 'header.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-segment', VI_WOO_PRODUCT_COMPARE_CSS . 'segment.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-vi-icon', VI_WOO_PRODUCT_COMPARE_CSS . 'icon.min.css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-icons', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc_icon_compare.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-icons-custom', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc_icon_custom.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
			wp_enqueue_style( 'compe-woo-compare-products-settings', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc-settings.' . $suffix . 'css', array(), VI_WOO_PRODUCT_COMPARE_VERSION );
//			wp_enqueue_style( 'select2css' );
			wp_enqueue_style( 'compe-woo-compare-products-select2-css', VI_WOO_PRODUCT_COMPARE_CSS . 'select2.min.css', [], VI_WOO_PRODUCT_COMPARE_VERSION );

			//script
//			wp_enqueue_script( 'select2' );
			wp_enqueue_script( 'compe-woo-compare-products-select2-js', VI_WOO_PRODUCT_COMPARE_JS . 'select2.js', array( 'jquery' ), VI_WOO_PRODUCT_COMPARE_VERSION, false );
			wp_enqueue_script( 'compe-woo-compare-products-settings', VI_WOO_PRODUCT_COMPARE_JS . 'wpc-setting.' . $suffix . 'js', array( 'jquery' ), VI_WOO_PRODUCT_COMPARE_VERSION, false );
			wp_enqueue_script( 'compe-woo-compare-products-semantic-dropdown-js', VI_WOO_PRODUCT_COMPARE_JS . 'dropdown.min.js', array( 'jquery' ), VI_WOO_PRODUCT_COMPARE_VERSION, false );
			wp_enqueue_script( 'compe-woo-compare-products-transition-js', VI_WOO_PRODUCT_COMPARE_JS . 'transition.min.js', array( 'jquery' ), VI_WOO_PRODUCT_COMPARE_VERSION, false );
		}
	}

}