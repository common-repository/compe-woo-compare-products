<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	require_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
}
if ( class_exists( 'WP_Customize_Control' ) ):
	class VI_WOO_PRODUCT_COMPARE_Radio_Icons_Control extends WP_Customize_Control {
		public $type = 'wpc_radio_icons';

		public function render_content() {
			?>
            <div class="customize-control-content">
				<?php
				if ( ! empty( $this->label ) ) {
					?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php
				}
				?>
				<?php
				if ( ! empty( $this->description ) ) {
					?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php
				}
				$class = $this->id;
				$class = str_replace( '[', '-', $class );
				$class = str_replace( ']', '', $class );
				?>
                <div class="wpc-radio-icons-wrap <?php echo esc_attr( $class ); ?>">
					<?php
					foreach ( $this->choices as $key => $value ) {
						?>
                        <label class="wpc-radio-icons-label <?php if ( $key == $this->value() )
							echo esc_attr( 'wpc-radio-icons-active' ) ?>">
                            <input type="radio" name="<?php echo esc_attr( $this->id ); ?>"
                                   value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
                            <span class="<?php echo esc_attr( $key ); ?>"></span>
                        </label>
						<?php
					}
					?>
                </div>
            </div>
			<?php
		}

	}

	class VI_WOO_PRODUCT_COMPARE_Field_Control extends WP_Customize_Control {
		public function render_content() {
			?>
            <div class="customize-control-content">
				<?php
				if ( ! empty( $this->label ) ) {
					?>
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<?php
				}
				?>
				<?php
				if ( ! empty( $this->description ) ) {
					?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
					<?php
				}
				$class = $this->id;
				$class = str_replace( '[', '-', $class );
				$class = str_replace( ']', '', $class );
				?>
                <input type="hidden" name="<?php echo esc_attr( $this->id ); ?>" id="<?php echo esc_attr( $class ); ?>"
                       value="<?php echo esc_attr( $this->value() ); ?>"
                       class="<?php echo esc_attr( $class ); ?>" <?php $this->link(); ?>/>
            </div>
			<?php
			$rows = json_decode( $this->value(), true );
			?>
            <div class="<?php echo esc_attr( $this->set( 'container' ) ) ?>">
				<?php
				if ( is_array( $rows ) && count( $rows ) ) {
					?>
                    <div class="<?php echo esc_attr( $this->set( array(
						'container__block',
						'data_blocks',
					) ) ) ?>">
						<?php
						foreach ( $rows as $row_key => $row_value ) {
							if ( $row_value[2] == 1 ) {
								?>
                                <div class="<?php echo esc_attr( $this->set( array(
									'item',
									$row_value[0]
								) ) ) ?>"
                                     data-block_item="<?php echo esc_attr( $row_value[0] ) ?>">
                                    <span class="woo-compare-block-header"><?php if ( $row_value[1] != '' ) {
		                                    echo esc_attr( $row_value[1] );
	                                    } else echo esc_attr( 'Placeholder: ' . $row_value[0] ) ?></span>
                                    <span class="<?php echo esc_attr( $this->set( array( 'inline-block-add-item', ) ) ) ?>"
                                          title="<?php esc_attr_e( 'Add items', 'compe-woo-compare-products' ) ?>">+</span>
                                    <input type="text" id="<?php echo esc_attr( $this->set( $row_value[0] ) ) ?>"
                                           class="<?php echo esc_attr( $this->set( array( 'text', 'text-' . $row_value[0] ) ) ) ?>"
                                           value="<?php echo esc_attr( $row_value[1] ) ?>" placeholder="<?php echo esc_attr( $row_value[0] ) ?>">
                                    <span class="<?php echo esc_attr( $this->set( 'edit' ) );
									echo esc_attr( ' wpc_icon_compare-edit' ) ?>"
                                          title="<?php esc_attr_e( 'Edit this item', 'compe-woo-compare-products' ) ?>"></span>
                                    <span class="<?php echo esc_attr( $this->set( 'remove' ) );
									echo esc_attr( ' wpc_icon_compare-cancel' ) ?>"
                                          title="<?php esc_attr_e( 'Remove this item', 'compe-woo-compare-products' ) ?>"></span>
                                </div>
								<?php
							}
						}
						?>
                        <div class="<?php echo esc_attr( $this->set( array(
							'edit-block-container',
						) ) ) ?>">
                        <span class="<?php echo esc_attr( $this->set( array(
	                        'edit-block-add-item',
                        ) ) ) ?>"
                              title="<?php esc_attr_e( 'Add items', 'compe-woo-compare-products' ) ?>">+</span>
                        </div>
                    </div>
				<?php } else { ?>
                    <div class="<?php echo esc_attr( $this->set( array(
						'container__block',
						'data_blocks',
					) ) ) ?>">
                        <div class="<?php echo esc_attr( $this->set( array(
							'edit-block-container',
						) ) ) ?>">
                        <span class="<?php echo esc_attr( $this->set( array(
	                        'edit-block-add-item',
                        ) ) ) ?>"
                              title="<?php esc_attr_e( 'Add items', 'compe-woo-compare-products' ) ?>">+</span>
                        </div>
                    </div>
				<?php } ?>
            </div>
            <div class="<?php echo esc_attr( $this->set( 'components-container' ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( 'components-overlay' ) ) ?>"></div>
                <div class="<?php echo esc_attr( $this->set( 'components' ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( 'components-close-container' ) ) ?>"><span
                                class="<?php echo esc_attr( $this->set( 'components-close' ) );
								echo esc_attr( ' wpc_icon_compare-cancel-1' ) ?>"></span>
                    </div>
                    <h3 class="<?php echo esc_attr( $this->set( 'available-components' ) ) ?>"><?php esc_html_e( 'Available components', 'compe-woo-compare-products' ) ?></h3>
                    <div class="<?php echo esc_attr( $this->set( array( 'components__block', 'data_blocks' ) ) ) ?>">
						<?php
						if ( is_array( $rows ) && count( $rows ) ) {
							foreach ( $rows as $row_key => $row_value ) {
								if ( $row_value[2] != 1 ) {
									?>
                                    <div class="<?php echo esc_attr( $this->set( array( 'item', $row_value[0] ) ) ) ?>"
                                         data-block_item="<?php echo esc_attr( $row_value[0] ) ?>">
                                        <span class="woo-compare-block-header"><?php echo esc_attr( $row_value[1] ) ?></span>
                                        <span class="<?php echo esc_attr( $this->set( array( 'inline-block-add-item', ) ) ) ?>"
                                              title="<?php esc_attr_e( 'Add items', 'compe-woo-compare-products' ) ?>">+</span>
                                        <input type="text" id="<?php echo esc_attr( $this->set( $row_value[0] ) ) ?>"
                                               class="<?php echo esc_attr( $this->set( array( 'text', 'text-' . $row_value[0] ) ) ) ?>"
                                               value="<?php echo esc_attr( $row_value[1] )
										       ?>">
                                        <span class="<?php echo esc_attr( $this->set( 'edit' ) ) ?> wpc_icon_compare-edit"
                                              title="<?php esc_attr_e( 'Edit this item', 'compe-woo-compare-products' ) ?>"></span>
                                        <span class="<?php echo esc_attr( $this->set( 'remove' ) ) ?> wpc_icon_compare-cancel"
                                              title="<?php esc_attr_e( 'Remove this item', 'compe-woo-compare-products' ) ?>"></span>
                                    </div>
									<?php
								}
							}
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}

		private function set( $name ) {
			if ( is_array( $name ) ) {
				return implode( ' ', array_map( array( $this, 'set' ), $name ) );

			} else {
				return esc_attr( 'woo-compare-block-' . $name );

			}
		}

		public function enqueue() {
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'wpc-product-compare-custom-controls-blocks-js', VI_WOO_PRODUCT_COMPARE_JS . 'wpc-control-blocks.js', array(
				'jquery',
				'customize-preview',
			), VI_WOO_PRODUCT_COMPARE_VERSION, true );

			$rows = array(
				1 => '',
				2 => '',
				3 => '',
				4 => '',
			);
			foreach ( $rows as $key => $val ) {
				ob_start();
				?>
                <div class="<?php echo esc_attr( $this->set(
					array(
						'container__row',
						$key . '-column',
					) ) ) ?>">
					<?php
					for ( $i = 0; $i < $key; $i ++ ) {
						?>
                        <div class="<?php echo esc_attr( $this->set(
							array(
								'container__block',
								'data_blocks',
							) ) ) ?>">
                            <div class="<?php echo esc_attr( $this->set( array(
								'edit-block-container',
							) ) ) ?>">
                                                        <span class="<?php echo esc_attr( $this->set( array(
	                                                        'edit-block-add-item',
                                                        ) ) ) ?>">+</span>
                            </div>
                        </div>
						<?php
					}
					?>
                    <span class="<?php echo esc_attr( $this->set( array(
						'remove-row',
					) ) ) ?> dashicons dashicons-trash"
                          title="<?php esc_attr_e( 'Remove', 'compe-woo-compare-products' ) ?>"></span>
                </div>
				<?php
				$rows[ $key ] = ob_get_clean();
			}
			wp_localize_script( 'wpc-product-compare-custom-controls-blocks-js', 'wooCompare_custom_blocks_params', array(
					'rows' => $rows
				)
			);
			wp_enqueue_style( 'wpc-product-compare-customizer-css', VI_WOO_PRODUCT_COMPARE_CSS . 'wpc-customizer.css', [], VI_WOO_PRODUCT_COMPARE_VERSION );
		}
	}
endif;