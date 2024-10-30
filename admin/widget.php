<?php
/*
Class Name: VI_WOO_PRODUCT_COMPARE_Admin_Widget
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wpc_register_widget() {
	register_widget( 'VI_WOO_PRODUCT_COMPARE_Admin_Widget' );
}

add_action( 'widgets_init', 'wpc_register_widget' );

class VI_WOO_PRODUCT_COMPARE_Admin_Widget extends WP_Widget {
	protected $wpc_product_list;
	protected $wpc_settings;

	function __construct() {
		parent::__construct(
			'wpc-widget',
			esc_html__( 'VI WooCommerce Product Compare', 'compe-woo-compare-products' ),
			array( 'description' => esc_html__( 'VillaTheme COMPE - WooCommerce Compare Products', 'compe-woo-compare-products' ), )
		);
		$this->wpc_settings = new VI_WOO_PRODUCT_COMPARE_DATA();
		//Ajax load widget
		add_action( 'wp_ajax_wpc_load_widget', array( $this, 'wpc_load_widget' ) );
		add_action( 'wp_ajax_nopriv_wpc_load_widget', array( $this, 'wpc_load_widget' ) );

		add_action( 'wp_ajax_wpc_search_widget', array( $this, 'wpc_search_widget' ) );
		add_action( 'wp_ajax_nopriv_wpc_search_widget', array( $this, 'wpc_search_widget' ) );
	}

	public function widget( $args, $instance ) {
		$wpc_products = array();
		$title        = apply_filters( 'widget_title', esc_html( $this->wpc_settings->get_params( 'wpc_widget_header_text' ) ) );
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}
		?>
        <ul class="woo-compare-widget-products"> <?php
		$wooCompare_cookie = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );
		if ( isset( $_COOKIE[ $wooCompare_cookie ] ) && ! empty( $_COOKIE[ $wooCompare_cookie ] ) ) {
			if ( is_user_logged_in() ) {
				update_user_meta( get_current_user_id(), 'wooCompare_products', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			}
			$wpc_products = explode( ',', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			$this->list_products_html( $wpc_products ); ?>
            </ul>
            <div class="woo-compare-widget-group">
            <span class="button remove-btn woo-compare-widget-remove-all" data-id="all"
                  rel="nofollow"><?php esc_html_e( 'Clear', 'compe-woo-compare-products' ) ?></span>
                <span class="button woo-compare-widget-btn" rel="nofollow"><?php esc_html_e( 'Compare', 'compe-woo-compare-products' ) ?></span>
            </div>
			<?php
		} else {
			esc_html_e( 'No product in compare list', 'compe-woo-compare-products' ); ?>
            </ul>
            <div class="woo-compare-widget-group">
            <span class="button remove-btn woo-compare-widget-remove-all woo-compare-hide" data-id="all"
                  rel="nofollow"><?php esc_html_e( 'Clear', 'compe-woo-compare-products' ) ?></span>
                <span class="button woo-compare-widget-btn woo-compare-hide" rel="nofollow"><?php esc_html_e( 'Compare', 'compe-woo-compare-products' ) ?></span>
            </div>
			<?php
		}
		if ( $this->wpc_settings->get_params( 'wpc_widget_search' ) == 1 ) { ?>
            <div class="woo-compare-widget-search-content">
                <div class="woo-compare-widget-search-input">
                    <input type="search" id="woo_compare_widget_search_input"
                           placeholder="<?php esc_html_e( 'Input at least 3 keyword to search...', 'compe-woo-compare-products' ); ?>"/>
                </div>
                <div class="woo-compare-widget-search-result"></div>
            </div>
		<?php } ?>
		<?php echo wp_kses_post( $args['after_widget'] );
	}

	public function form( $instance ) {
		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = esc_html__( 'Product Compare', 'compe-woo-compare-products' );
		}
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : '';

		return $instance;
	}

	public function list_products_html( $product_list ) {
		if ( empty( $product_list ) ) { ?>
            <li class="list_empty"><?php esc_html_e( 'No products in compare list', 'compe-woo-compare-products' ) ?></li>
			<?php
			return;
		}
		foreach ( $product_list as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				continue;
			}
			?>
            <li class="woo-compare-widget-item-<?php echo esc_attr( $product_id ) ?>">
                <span data-id="<?php echo esc_attr( $product_id ) ?>" class="woo-compare-widget-remove-single dashicons dashicons-no"
                      id="woo-compare-widget-remove-single"
                      title="<?php esc_attr_e( "Remove", 'compe-woo-compare-products' ) ?>"></span>
                <a class="title woo-compare-widget-product-title" href="<?php echo esc_url( get_permalink( $product_id ) ) ?>"><?php echo esc_html( $product->get_title() ) ?></a>
            </li>
			<?php
		}

		return;
	}

	function wpc_load_widget() {
		check_ajax_referer( 'wpc-nonce', 'nonce' );
		$this->list_products_html( explode( ',', sanitize_text_field( $_POST['products'] ) ) );
		wp_die();
	}

	function wpc_search_widget() {
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpc-nonce' ) ) {
			die( 'Permissions check failed' );
		}
		$wpc_products      = array();
		$keyword           = sanitize_text_field( $_POST['keyword'] );
		$wooCompare_cookie = 'wooCompare_products_' . md5( 'wpc' . get_current_user_id() );

		if ( isset( $_COOKIE[ $wooCompare_cookie ] ) && ! empty( $_COOKIE[ $wooCompare_cookie ] ) ) {
			if ( is_user_logged_in() ) {
				update_user_meta( get_current_user_id(), 'wooCompare_products', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
			}
			$wpc_products = explode( ',', sanitize_text_field( $_COOKIE[ $wooCompare_cookie ] ) );
		}

		$wpc_query_args = array(
			's'              => $keyword,
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 10
		);

		$wpc_query = new WP_Query( $wpc_query_args );

		if ( $wpc_query->have_posts() ) {
			?>
            <ul>
				<?php
				while ( $wpc_query->have_posts() ) {
					$wpc_query->the_post();
					if ( ! in_array( get_the_ID(), $wpc_products ) ) {
						?>
                        <li>
                            <div class="item-inner">
                                <div class="item-image"><?php echo wp_kses_post( get_the_post_thumbnail( get_the_ID(), 'wpc-img-small' ) ) ?></div>
                                <div class="item-name"><a href="<?php echo esc_url( get_permalink() ) ?>"><?php echo esc_html( get_the_title() ) ?></a></div>
                                <div class="item-add woo-compare-item-add" data-id="<?php echo esc_attr( get_the_ID() ) ?>" title="<?php esc_attr_e( 'Add to compare' ); ?>">
                                    <span>+</span></div>
                            </div>
                        </li>
						<?php
					}
				}
				?>
            </ul>
			<?php
			wp_reset_postdata();
		} else {
			?>
            <span><?php /* translators: %s: keyword to search */
                echo sprintf( esc_html__( 'No results found for "%s"', 'compe-woo-compare-products' ), esc_html( $keyword ) ) ?></span>
			<?php
		}
		wp_die();
	}

}