(function ($) {
    "use strict";
    let alter_type = woo_compare_design_params.wpc_table_alternating_type;
    let data_fields = woo_compare_design_params.data_fields_arr;
    let wooCompare_slide_num = 3;
    let wooCompare_slideNum = $(window).width() > 600 ? 3 : 2;
    let wooCompare_scrollRate = $(window).width() > 600 ? 0.28 : 0.4;
    let wooCompare_scrollLeft = ($('.woo-compare-table-content').width() - $('.woo-compare-table-content .woo-compare-table-field-header').width()) / 3;
    let wooCompare_hexDigits = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f"];
    let wooCompare_header_image_size = woo_compare_design_params.wpc_table_header_image_size;

    $(document).ready(function () {
        if ($('.woo-compare-table').hasClass('woo-compare-table-open')) {
            $('.woo-compare-floating-icon-wrap').addClass('woo-compare-floating-icon-hide-' + woo_compare_design_params.wpc_floating_icon_position);
        } else {
            $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-floating-icon-hide-left').removeClass('woo-compare-floating-icon-hide-right');
        }
        $('.woo-compare-bar.woo-compare-bar-bubble').addClass('woo-compare-bar-open');
        $('.widget_wpc-widget .woo-compare-widget-remove-all').removeClass('woo-compare-hide');
        $('.widget_wpc-widget .woo-compare-widget-btn').removeClass('woo-compare-hide');
        if (woo_compare_design_params.wpc_popup_compare != 1) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-slide-prev-contain').css('display', 'none');
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-slide-next-contain').css('display', 'none');
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
        }

        wooCompareCheckButtons();
        wooCompareIconInvert();
        // wooCompareSetSingleStyle();
        // wooCompareSetArchiveStyle();
        wooCompareLoadComparePopup();
    });
    $(document).on('click touch', '.woo-compare-bar-btn-icon-wrapper', function (e) {
        if ($('.woo-compare-bar').hasClass('woo-compare-bar-bubble')) {
            $('.woo-compare-bar').removeClass('woo-compare-bar-bubble');
        } else {
            $('.woo-compare-bar').addClass('woo-compare-bar-bubble');
        }
    });

    //button compare
    wp.customize('woo_product_compare_params[wpc_btn_compare_font_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-btn.woo-compare-single').css({
                'font-size': newval + 'px',
                // 'line-height': newval + 'px'
            });
            woo_compare_design_params.wpc_btn_compare_font_size = newval;
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_compare_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-btn.woo-compare-single').css('color', newval);
            $('.woo-compare-btn.woo-compare-single i').css('color', newval);
            woo_compare_design_params.wpc_btn_compare_color = newval;
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_compare_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-btn.woo-compare-single').not('.woo-compare-btn-added').css('background-color', newval);
            woo_compare_design_params.wpc_btn_compare_background = newval;
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_compare_added]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-btn.woo-compare-single.woo-compare-btn-added').css('background-color', newval);
            woo_compare_design_params.wpc_btn_compare_added = newval;
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_single_pos]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_btn_single_pos = newval;
            wooCompare_SingleChange();
            wooCompareSetSingleStyle();
        });
    });

    //icon compare
    wp.customize('woo_product_compare_params[wpc_btn_archive_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-btn.woo-compare-icon').each(function () {
                $(this).find('i').css({
                    'font-size': newval + 'px',
                });
                if (!$(this).hasClass('woo-compare-btn-inside')) {
                    $(this).css({
                        'font-size': newval + 'px',
                    });
                }
            })
            // if ($('.woo-compare-btn.woo-compare-icon').eq(0).hasClass('woo-compare-btn-inside')) {
            //     $('.woo-compare-btn.woo-compare-icon i').css({
            //         'font-size': newval + 'px',
            //         // 'line-height': newval + 'px'
            //     });
            // } else {
            //     $('.woo-compare-btn.woo-compare-icon').css({
            //         'font-size': newval + 'px',
            //         // 'line-height': newval + 'px'
            //     });
            //     $('.woo-compare-btn.woo-compare-icon i').css({
            //         'font-size': newval + 'px',
            //         // 'line-height': newval + 'px'
            //     });
            // }
            woo_compare_design_params.wpc_btn_archive_size = newval;
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_archive_color]', function (value) {
        value.bind(function (newval) {
            // let _color = woo_compare_design_params.wpc_btn_archive_added != '' ? woo_compare_design_params.wpc_btn_archive_added : '#f2ca68';
            woo_compare_design_params.wpc_btn_archive_color = newval;
            $('.woo-compare-btn.woo-compare-icon').each(function () {
                // let _display_color = $(this).hasClass('woo-compare-added') ? _color : woo_compare_design_params.wpc_btn_archive_color;
                $(this).css({
                    'color': newval,
                });
            })
            wooCompareIconInvert();
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_archive_background]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_btn_archive_background = newval;
            $('.woo-compare-btn.woo-compare-icon').each(function () {
                let _background = $(this).hasClass('woo-compare-added') ? woo_compare_design_params.wpc_btn_archive_added : woo_compare_design_params.wpc_btn_archive_background;
                if (!$(this).hasClass('woo-compare-btn-inside')) {
                    $(this).css({
                        'background-color': _background,
                    });
                }
            })
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_archive_added]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_btn_archive_added = newval;
            $('.woo-compare-btn.woo-compare-icon').each(function () {
                if ($(this).hasClass('woo-compare-added')) {
                    if (!$(this).hasClass('woo-compare-btn-inside')) {
                        $(this).css({
                            'background-color': newval,
                        });
                    } else {
                        $(this).find('i').css({
                            'color': newval,
                        });
                    }
                }
            });
            wooCompareIconInvert();
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_archive_pos]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_btn_archive_pos = newval;
            if (woo_compare_design_params.wpc_list_product_mode == 1) {
                wooCompare_ArchiveChange();
            }
            wooCompareArchiveHoverHide(woo_compare_design_params.wpc_btn_archive_enable_hover);
        });
    });
    wp.customize('woo_product_compare_params[wpc_btn_archive_enable_hover]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_btn_archive_enable_hover = newval;
            wooCompareArchiveHoverHide(newval);
        });
    });

    // compare icon
    wp.customize('woo_product_compare_params[wpc_btn_compare_icon]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-btn.woo-compare-icon i').attr('class', newval);
            $('.woo-compare-btn.woo-compare-single i').attr('class', newval);
        });
    });

    // floating icon
    wp.customize('woo_product_compare_params[wpc_floating_icon]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-floating-icon').attr('class', 'woo-compare-floating-icon ' + newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_floating_icon_position]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-floating-icon-position-top-right').removeClass('woo-compare-floating-icon-position-bottom-right').removeClass('woo-compare-floating-icon-position-top-left').removeClass('woo-compare-floating-icon-position-bottom-left').addClass('woo-compare-floating-icon-position-' + newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_floating_icon_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-floating-icon').css({'font-size': newval + 'px', 'line-height': newval + 'px'});
        });
    });
    wp.customize('woo_product_compare_params[wpc_floating_icon_border]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-floating-icon-wrap').css({'border-radius': newval + 'px'});
        });
    });
    wp.customize('woo_product_compare_params[wpc_floating_icon_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-floating-icon').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_floating_icon_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-floating-icon-wrap').css('background-color', newval);
        });
    });

    // compare bar
    wp.customize('woo_product_compare_params[wpc_side_bar_horizontal]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-area').attr('class', 'woo-compare-area' + ' woo-compare-bar-' + newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_side_bar_background_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar').css('background-color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_side_bar_button_background]', function (value) {
        value.bind(function (newval) {
            let rgb_color = wooCompare_hexToRgb(newval, 0.5, 0);
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn').css('background-color', newval);
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn-icon-wrapper').css('background-color', 'rgba(' + rgb_color[0] + ',' + rgb_color[1] + ',' + rgb_color[2] + ',' + rgb_color[3] + ')');
        });
    });
    wp.customize('woo_product_compare_params[wpc_side_bar_button_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn').css('color', newval);
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn-icon-wrapper .woo-compare-bar-btn-icon-inner span').css('background-color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_side_bar_button_text]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-btn').html(newval);
        });
    });

    //widget
    wp.customize('woo_product_compare_params[wpc_widget_header_size]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .widget-title').css({'font-size': newval + 'px', 'line-height': newval + 'px'});
            $('.widget_wpc-widget .widgettitle').css({'font-size': newval + 'px !important', 'line-height': newval + 'px'});
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_header_text]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .widget-title').html(newval);
            $('.widget_wpc-widget .widgettitle').html(newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_header_color]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .widget-title').css('color', newval);
            $('.widget_wpc-widget .widgettitle').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_header_background]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .widget-title').css('background-color', newval);
            $('.widget_wpc-widget .widgettitle').css('background-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_widget_compare_text_size]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .woo-compare-widget-btn').css({'font-size': newval + 'px'});
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_compare_text_color]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .woo-compare-widget-btn').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_compare_background]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .woo-compare-widget-btn').css('background-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_widget_clear_size]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .remove-btn').css({
                'font-size': newval + 'px'
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_clear_color]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .remove-btn').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_widget_clear_background]', function (value) {
        value.bind(function (newval) {
            $('.widget_wpc-widget .remove-btn').css('background-color', newval);
        });
    });

    //table buttons
    wp.customize('woo_product_compare_params[wpc_table_select_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-content .woo-compare-table-field-header .woo-compare-table-button-setting').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_select_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-content .woo-compare-table-field-header .woo-compare-table-button-setting').css('background-color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_select_font_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-content .woo-compare-table-field-header .woo-compare-table-button-setting').css({
                'font-size': newval + 'px',
                'line-height': newval + 'px'
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_select_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-content .woo-compare-table-field-header .woo-compare-table-button-setting').css('width', newval + '%');
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_select_border_radius]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-content .woo-compare-table-field-header .woo-compare-table-button-setting').css('border-radius', newval + 'px');
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_clear_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_clear_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').css('background-color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_clear_font_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').css({
                'font-size': newval + 'px',
                'line-height': newval + 'px'
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_clear_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').css({
                'width': newval + '%'
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_clear_border_radius]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').css({
                'border-radius': newval + 'px'
            });
        });
    });

    //table content
    wp.customize('woo_product_compare_params[wpc_table_header_image_display]', function (value) {
        value.bind(function (newval) {
            if (!newval) {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-image').css({
                    'display': 'none',
                });
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-image').css({
                    'display': 'initial',
                });
            }
            wooCompareTableRowSize();
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_header_title_display]', function (value) {
        value.bind(function (newval) {
            if (!newval) {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name').css({
                    'display': 'none',
                });
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name').css({
                    'display': 'initial',
                });
            }
            wooCompareTableRowSize();
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_header_cart_display]', function (value) {
        value.bind(function (newval) {
            if (!newval) {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart').css({
                    'display': 'none',
                });
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart').css({
                    'display': 'initial',
                });
            }
            wooCompareTableRowSize();
        });
    });


    wp.customize('woo_product_compare_params[wpc_table_header_image_size]', function (value) {
        value.bind(function (newval) {
            wooCompare_header_image_size = newval;
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-product-stage .woo-compare-image-wrap img').css('max-height', newval + 'px')
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_header_cart_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart p a.button').css({
                'color': newval,
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_header_cart_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart p a.button').css({
                'background-color': newval,
            });
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_header_title_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name a').css({
                'font-size': newval + 'px',
            });
            wooCompareTableRowSize();
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_header_title_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name a').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_header_text_align]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title').css('text-align', newval);
            $('.woo-compare-table .woo-compare-table-items .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-cart p.add_to_cart_inline').css('justify-content', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_header_background]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_header_background = newval;
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button').css('background-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_content_header_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css({
                'font-size': newval + 'px'
            });
            wooCompareTableRowSize();
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_header_align]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css('text-align', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_header_font_weight]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css('font-weight', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_header_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_header_background]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_content_header_background = newval;
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css({'background-color': newval});
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_content_border_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-cell').css('border-width', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-tr-title').css('border-width', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('border-width', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('border-width', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('border-width', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_border_style]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('border-right-style', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css({
                'border-right-style': newval,
                'border-left-style': newval,
            });
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-cell').css({'border-bottom-style': newval});
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-tr-title').css({
                'border-bottom-style': newval,
                'border-top-style': newval,
            });
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css({
                'border-style': newval,
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_border_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('border-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('border-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-cell').css('border-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-tr-title').css('border-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('border-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_content_content_size]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell').css('font-size', newval + 'px');
            wooCompareTableRowSize();
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_content_align]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell').css({
                'text-align': '-webkit-' + newval,
            });
            let _align = '';
            switch (newval) {
                case 'left':
                    _align = 'flex-start';
                    break;
                case 'right':
                    _align = 'flex-end';
                    break;
                default:
                    _align = newval;
                    break;
            }
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell.woo-compare-tr-rating').css({
                'justify-content': _align,
            });
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_content_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell').css('color', newval);
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_content_content_background]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_content_content_background = newval;
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell table tr th').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell table tr td').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('background-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_alternating_type]', function (value) {
        value.bind(function (newval) {
            alter_type = newval;
            switch (newval) {
                case 'row':
                    wooCompareAlternateRow(woo_compare_design_params.wpc_table_alternating_row_odd, woo_compare_design_params.wpc_table_alternating_row_even);
                    break;
                case 'col':
                    wooCompareAlternateCol(woo_compare_design_params.wpc_table_alternating_col_odd, woo_compare_design_params.wpc_table_alternating_col_even);
                    break;
                default:
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title').css('background-color', woo_compare_design_params.wpc_table_header_background);
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name').css('background-color', woo_compare_design_params.wpc_table_header_background);
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button').css('background-color', woo_compare_design_params.wpc_table_header_background);
                    // field header
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css({'background-color': woo_compare_design_params.wpc_table_content_header_background});
                    // content
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell').css('background-color', woo_compare_design_params.wpc_table_content_content_background);
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell table tr th').css('background-color', woo_compare_design_params.wpc_table_content_content_background);
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-cell table tr td').css('background-color', woo_compare_design_params.wpc_table_content_content_background);
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('background-color', woo_compare_design_params.wpc_table_content_content_background);
                    break;
            }
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_alternating_row_odd]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_alternating_row_odd = newval;
            if (alter_type == 'row') {
                wooCompareAlternateRow(newval, woo_compare_design_params.wpc_table_alternating_row_even);
            }
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_alternating_row_even]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_alternating_row_even = newval;
            if (alter_type == 'row') {
                wooCompareAlternateRow(woo_compare_design_params.wpc_table_alternating_row_odd, newval);
            }
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_alternating_col_odd]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_alternating_col_odd = newval;
            if (alter_type == 'col') {
                wooCompareAlternateCol(newval, woo_compare_design_params.wpc_table_alternating_col_even);
            }
        });
    });
    wp.customize('woo_product_compare_params[wpc_table_alternating_col_even]', function (value) {
        value.bind(function (newval) {
            woo_compare_design_params.wpc_table_alternating_col_even = newval;
            if (alter_type == 'col') {
                wooCompareAlternateCol(woo_compare_design_params.wpc_table_alternating_col_odd, newval);
            }
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_search_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-input').css('color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result .item-inner .item-name a').css('color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_search_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-input').css('background-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_search_btn_color]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button span').css('background-color', newval);
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button .woo-compare-table-search-arrow').css('color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_table_search_btn_background]', function (value) {
        value.bind(function (newval) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').css('background-color', newval);
        });
    });

    wp.customize('woo_product_compare_params[wpc_blocks]', function (value) {
        value.bind(function (newval) {
            let fields_arr = $.parseJSON(newval);
            data_fields = $.parseJSON(newval);
            $.each(fields_arr, function (index, object) {
                if (object[2] == 1) {
                    $('.woo-compare-cell.woo-compare-tr-' + object[0]).removeClass('tr-hide');
                } else {
                    $('.woo-compare-cell.woo-compare-tr-' + object[0]).removeClass('tr-hide').addClass('tr-hide');
                }
                $('.woo-compare-table-field-header .woo-compare-table-field-header-free .woo-compare-table-field-header-' + object[0]).html(object[1]);
                $('.woo-compare-table-field-header .woo-compare-table-field-header-free').append($('.woo-compare-table-field-header-' + object[0]));
            });
            let _column = $('.woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
            for (let i = 0; i < _column.length; i++) {
                $.each(fields_arr, function (index, object) {
                    if (_column[i].children[1].querySelector('.woo-compare-tr-' + object[0]) != null) {
                        _column[i].children[1].append(_column[i].children[1].querySelector('.woo-compare-tr-' + object[0]));
                    }
                });
            }
            wooCompareTableRowSize();
            wooCompareAlternateColor();
        });
    });

    wp.customize('woo_product_compare_params[wpc_custom_css]', function (value) {
        value.bind(function (newval) {
            $('#woo-product-compare-custom-css').html(newval);
        });
    });

    $(document).on('click touch', '#woo-compare-table-close', function () {
        $('.woo-compare-table').removeClass('woo-compare-table-open');
        $('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
        $('.woo-compare-bar').addClass('woo-compare-bar-open');
        wooCompare_show_floating();
    });

    $(document).on('click touch', '.woo-compare-floating-icon-wrap', function () {
        $('.woo-compare-table').addClass('woo-compare-table-open');
        $('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
        $('.woo-compare-bar').removeClass('woo-compare-bar-open');
        wooCompare_hide_floating();
    });

    $(document).on('click touch', '.woo-compare-table-search-button', function (e) {
        e.stopPropagation();
        if ($(this).hasClass('woo-compare-hide')) {
            let _col = wooCompare_slideNum - $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
            let _col_rate = _col < 1 ? 1 : _col;
            let base_width = $('.woo-compare-table .woo-compare-table-search').width();
            let col_width = ($('.woo-compare-table-content').width() * wooCompare_scrollRate * _col_rate);
            let _width = col_width + base_width;
            $(this).removeClass('woo-compare-hide');
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
                'position': 'absolute',
                'width': _width + 'px',
                'left': '-' + col_width + 'px'
            }).addClass('woo-compare-open');
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').show();
            $('.woo-compare-table-search-input').addClass('open').focus();
        } else {
            let _aval_col = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
            if (_aval_col.length && _aval_col.length >= wooCompare_slideNum) {
                wooCompare_CloseSearch();
            }
        }
        $(document.body).trigger('wooCompare_openSearch');
    });

    function wooCompareSearchSlime(stage = 0) {
        let col_length = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
        let _col = wooCompare_slideNum - $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
        let _col_rate = _col < 1 ? 1 : _col;
        let base_width = $('.woo-compare-table .woo-compare-table-search').width();
        let col_width = ($('.woo-compare-table-content').width() * wooCompare_scrollRate * _col_rate);
        let _width = col_width + base_width;
        if (stage === 0) {
            if (col_length >= wooCompare_slideNum) wooCompareSearchClose(); else {
                $('.woo-compare-table-search-button').removeClass('woo-compare-hide');
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
                    'position': 'absolute',
                    'width': _width + 'px',
                    'left': '-' + col_width + 'px'
                }).addClass('woo-compare-open');
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').show();
                $('.woo-compare-table-search-input').addClass('open').focus();
            }
        }
        if (stage === 1) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
                // 'position': 'absolute',
                'width': _width + 'px',
                'left': '-' + col_width + 'px'
            });
        }
    }

    function wooCompareSearchClose(hide = 1) {
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
            'position': 'relative',
            'width': '100%',
            'left': 'unset'
        }).removeClass('woo-compare-open');
        if (hide) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').addClass('woo-compare-hide');
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').hide();
        }
    }

    function wooCompare_CloseSearch() {
        $('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-button').addClass('woo-compare-hide');
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
            'position': 'relative',
            'width': '100%',
            'left': 'unset'
        }).removeClass('woo-compare-open');
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').hide();
    }

    function wooCompareArchiveHoverHide(_val = '') {
        if (_val && ((woo_compare_design_params.wpc_btn_archive_pos == 'on_image_left') || (woo_compare_design_params.wpc_btn_archive_pos == 'on_image_right'))) {
            $('.woo-compare-btn.woo-compare-icon').css({
                'visibility': 'hidden',
            });
            $('.product.type-product').hover(function () {
                    $(this).find('.woo-compare-btn.woo-compare-icon').css('visibility', 'visible');
                },
                function () {
                    $(this).find('.woo-compare-btn.woo-compare-icon').css('visibility', 'hidden');
                }
            );
        } else {
            $('.woo-compare-btn.woo-compare-icon').css({
                'visibility': 'visible',
            });
            $('.product.type-product').hover(function () {
                    $(this).find('.woo-compare-btn.woo-compare-icon').css('visibility', 'visible');
                },
                function () {
                    $(this).find('.woo-compare-btn.woo-compare-icon').css('visibility', 'visible');
                }
            );
        }
    }

    function wooCompareSetSingleStyle() {
        // $('.woo-compare-btn.woo-compare-single').css({
        //     'font-size': woo_compare_design_params.wpc_btn_compare_font_size + 'px',
        //     // 'line-height': woo_compare_design_params.wpc_btn_compare_font_size + 'px',
        //     'color': woo_compare_design_params.wpc_btn_compare_color,
        // });
        // if ($('.woo-compare-btn.woo-compare-single').hasClass('woo-compare-added')) {
        //     $('.woo-compare-btn.woo-compare-single').css('background-color', woo_compare_design_params.wpc_btn_compare_added);
        // } else {
        //     $('.woo-compare-btn.woo-compare-single').css('background-color', woo_compare_design_params.wpc_btn_compare_background);
        // }
    }

    function wooCompareSetArchiveStyle() {
        // let _color = woo_compare_design_params.wpc_btn_archive_added != '' ? woo_compare_design_params.wpc_btn_archive_added : '#f2ca68';
        // if ($('.woo-compare-btn.woo-compare-icon').eq(0).hasClass('woo-compare-btn-inside')) {
        //     $('.woo-compare-btn.woo-compare-icon i').css({
        //         'font-size': woo_compare_design_params.wpc_btn_archive_size + 'px',
        //         // 'line-height': woo_compare_design_params.wpc_btn_archive_size + 'px',
        //     });
        //     $('.woo-compare-btn.woo-compare-icon').each(function () {
        //         let _display_color = $(this).hasClass('woo-compare-added') ? _color : woo_compare_design_params.wpc_btn_archive_color;
        //         $(this).css({
        //             'font-size': 0,
        //             // 'line-height': 'unset',
        //             'background-color': 'initial',
        //             'color': _display_color,
        //         });
        //     })
        // } else {
        //     $('.woo-compare-btn.woo-compare-icon').each(function () {
        //         let _background = $(this).hasClass('woo-compare-added') ? woo_compare_design_params.wpc_btn_archive_added : woo_compare_design_params.wpc_btn_archive_background;
        //         $(this).css({
        //             'font-size': woo_compare_design_params.wpc_btn_archive_size + 'px',
        //             // 'line-height': woo_compare_design_params.wpc_btn_archive_size + 'px',
        //             'color': woo_compare_design_params.wpc_btn_archive_color,
        //             'background-color': _background,
        //         });
        //     });
        //     $('.woo-compare-btn.woo-compare-icon i').each(function () {
        //         $(this).css({
        //             'font-size': woo_compare_design_params.wpc_btn_archive_size + 'px',
        //             // 'line-height': woo_compare_design_params.wpc_btn_archive_size + 'px',
        //         });
        //     })
        // }

    }

    function wooCompareLoadComparePopup() {
        $('.woo-compare-table-inner').addClass('woo-compare-loading');

        let data = {
            action: 'wpc_load_compare_table',
            products: wooCompareGetProducts(),
            caller: 'customize',
            nonce: woo_compare_design_params.nonce,
        };

        $.post(woo_compare_design_params.ajaxurl, data, function (response) {
            $('.woo-compare-table-items').html(response).addClass('woo-compare-table-items-loaded');
            $('.woo-compare-table-inner').removeClass('woo-compare-loading');
            wooComparePopupFreezeInit();
            wooCompareSlideInit();
            wooCompareDesignTable();
            wooCompareAlternateColor();
            wooCompareSearchSlime();
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name a').removeAttr('href');
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title p a').removeAttr('href').removeAttr('data-product_id').removeAttr('data-product_sku');
        });
    }

    function wooCompareDesignTable() {
        let product_col = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        let product_col_free = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        let width = parseFloat($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').width());
        let wooCompareCookieProducts = 'wooCompare_products';
        let wooCompareCount = 0;

        if (woo_compare_design_params.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + woo_compare_design_params.user_id;
        }
        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');
            wooCompareCount = wooCompareProducts.length;
        }
        switch (wooCompare_slideNum) {
            case '1':
            case '2':
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css({
                    'width': '90%',
                    'min-width': 'unset'
                });
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css({
                    'width': '10%',
                    'min-width': 'unset'
                });
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('min-width', '40%');
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('min-width', '20%');
                if (wooCompareCount == 0) {
                    $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
                    $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
                }
                break;
            default:
                if (wooCompareCount < parseInt(wooCompare_slideNum)) {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                    if (wooCompareCount == 0) {
                        $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
                        $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
                    }
                } else {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                }
                break;
        }

        wooCompareTableRowSize();
        wooComparePageFreezeInit();
        wooCompareTableOuterSize();
    }

    function wooComparePopupFreezeInit() {
        $('#vi-woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
            $(this).children('.woo-compare-table-row-freeze').prepend($(this).children('.woo-compare-table-row-free').children('.woo-compare-tr-title'));
        });
        $('#vi-woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze').prepend($('#vi-woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free .woo-compare-tr-title'));
    }

    function wooComparePageFreezeInit() {
        $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
            $(this).children('.woo-compare-table-row-freeze').prepend($(this).children('.woo-compare-table-row-free').children('.woo-compare-tr-title'));
        });
        $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze').prepend($('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free .woo-compare-tr-title'));
    }

    function wooCompareTableOuterSize() {
        let _height = $('.woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header').height();
        if (_height < 500) {
            $('.woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header').height(500);
            $('#vi-woo-compare-table').height(500);
        } else if (_height < (parseInt($(window).height()) / 100 * 98)) {
            $('#vi-woo-compare-table').height(_height + 4);
        }
    }

    function wooCompareTableRowSize() {
        wooCompareRowResize(['image', 'name', 'cart'], '.woo-compare-tr-title .woo-compare-tr-title-');

        wooCompareRowResize(woo_compare_design_params.fields_arr, '.woo-compare-tr-');
        wooCompareRowResize(['title'], '.woo-compare-tr-');
    }

    function wooCompareRowResize(_array = [], _object = '') {
        if (_array.length === 1) {
            $(_object + _array[0]).css('height', '');
            let _arrSingle = $(_object + _array[0]);
            if (_arrSingle.length === 1) {
                $('.woo-compare-table .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').addClass('woo-compare-hide');
            } else {
                $('.woo-compare-table .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').removeClass('woo-compare-hide');
                let max_height = 0;
                $(_object + _array[0]).each(function () {
                    if (parseInt($(this).outerHeight()) > parseInt(max_height)) max_height = $(this).outerHeight();
                });
                $(_object + _array[0]).css('height', max_height + 'px');
            }
        } else {
            for (let i = 0; i < _array.length; i++) {
                $(_object + _array[i]).css('height', '');
                let max_height = 0;
                $(_object + _array[i]).each(function () {
                    if ('image' === _array[i]){
                        if (parseInt(wooCompare_header_image_size) > parseInt(max_height)) max_height = wooCompare_header_image_size;
                    } else {
                        if (parseInt($(this).outerHeight()) > parseInt(max_height)) max_height = $(this).outerHeight();
                    }
                });
                $(_object + _array[i]).css('height', max_height + 'px');
            }
        }
    }

    function wooCompareSlideInit() {
        let product_col = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        let product_col_free = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column-free');
        let product_col_freeze = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column-freeze');
        wooCompare_scrollLeft = ($('.woo-compare-table-content').width() - $('.woo-compare-table-content .woo-compare-table-field-header').width()) / 3;
        let slide = parseInt(wooCompare_slide_num) < parseInt(product_col.length) ? parseInt(wooCompare_slide_num) : parseInt(product_col.length);
        for (let i = 0; i < slide; i++) {
            product_col[i].classList.remove('col-hide');
            product_col[i].children[0].classList.add('woo-compare-row-available');
        }
        if (parseInt(product_col.length) <= parseInt(wooCompare_slide_num)) {
            wooCompareSetPageRowFreeze(0, product_col, product_col_free, product_col_freeze);
        }
    }

    function wooCompareSetPageRowFreeze(stage = 0, col = '', col_free = '', col_freeze = '') {
        for (let i = 0; i < wooCompare_slide_num; i++) {
            if (col[i] != null) {
                col[i].classList.remove('col-hide');
                if (col[i].children[0] != null) {
                    col[i].children[0].classList.add('woo-compare-row-available');
                    col[i].classList.add('woo-compare-col-available');
                }
            }
        }
    }

    function wooCompareGetProducts() {
        let wooCompareCookieProducts = 'wooCompare_products';

        if (woo_compare_design_params.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + woo_compare_design_params.user_id;
        }

        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            return wooCompareGetCookie(wooCompareCookieProducts);
        } else {
            return '';
        }
    }

    function wooCompareGetCookie(cname) {
        let name = cname + '=';
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return decodeURIComponent(c.substring(name.length, c.length));
            }
        }
        return '';
    }

    function wooCompareAlternateColor() {
        switch (alter_type) {
            case 'row':
                wooCompareAlternateRow(woo_compare_design_params.wpc_table_alternating_row_odd, woo_compare_design_params.wpc_table_alternating_row_even);
                break;
            case 'col':
                wooCompareAlternateCol(woo_compare_design_params.wpc_table_alternating_col_odd, woo_compare_design_params.wpc_table_alternating_col_even);
                break;
            default:
                break;
        }
    }

    function wooCompareAlternateRow(_row_odd, _row_even) {
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header .woo-compare-tr-title').css('background-color', _row_odd);
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title').css('background-color', _row_odd);
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name').css('background-color', _row_odd);
        let _column = $('.woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        for (let i = 0; i < _column.length; i++) {
            let _check = 0;
            let _rows = _column[i].querySelectorAll('.woo-compare-cell');
            for (let j = 0; j < _rows.length; j++) {
                if (_rows[j] != null && !_rows[j].classList.contains('tr-hide')) {
                    _check += 1;
                    if (_check % 2 !== 0 && _row_even != null) {
                        _rows[j].style.background = _row_even;
                        let __table = _rows[j].querySelectorAll('table tr');
                        if (__table != null) {
                            for (let k = 0; k < __table.length; k++) {
                                if (__table[k].querySelector('th') != null) {
                                    __table[k].querySelector('th').style.background = _row_even;
                                    __table[k].querySelector('td').style.background = _row_even;
                                }
                            }
                        }
                    } else if (_check % 2 === 0 && _row_odd != null) {
                        _rows[j].style.background = _row_odd;
                        let __table = _rows[j].querySelectorAll('table tr');
                        if (__table != null) {
                            for (let k = 0; k < __table.length; k++) {
                                if (__table[k].querySelector('th') != null) {
                                    __table[k].querySelector('th').style.background = _row_odd;
                                    __table[k].querySelector('td').style.background = _row_odd;
                                }
                            }
                        }
                    }
                }
            }
        }
        let _head_check = 0;
        let _head = $('.woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell');
        for (let j = 0; j < _head.length; j++) {
            if (_head[j] != null && !_head[j].classList.contains('tr-hide')) {
                _head_check += 1;
                if (_head_check % 2 !== 0 && _row_even != null) {
                    _head[j].style.background = _row_even;
                } else if (_head_check % 2 === 0 && _row_odd != null) {
                    _head[j].style.background = _row_odd;
                }
            }
        }
    }

    function wooCompareAlternateCol(_col_odd, _col_even) {
        let _columns = $('.woo-compare-table-items .woo-compare-table-content .woo-compare-table-column.woo-compare-col-available');
        if (_col_odd != null) {
            $('.woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell').css('background', _col_odd);
            $('.woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-title').css('background', _col_odd);
        }
        let _check_col = 0;
        for (let i = 0; i < _columns.length; i++) {
            if (_columns[i] != null && !_columns[i].classList.contains('col-hide')) {
                _check_col += 1;
                if (_check_col % 2 === 0 && _col_odd != null) {
                    for (let j = 0; j < _columns[i].childElementCount; j++) {
                        for (let k = 0; k < _columns[i].children[j].children.length; k++) {
                            _columns[i].children[j].children[k].style.background = _col_odd;
                            if (_columns[i].children[j].children[k].classList.contains('woo-compare-tr-title')) {
                                _columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-name').style.background = _col_odd;
                            }
                            let __table = _columns[i].children[j].children[k].querySelectorAll('table tr');
                            if (__table != null) {
                                for (let l = 0; l < __table.length; l++) {
                                    if (__table[l].querySelector('th') != null) {
                                        __table[l].querySelector('th').style.background = _col_odd;
                                        __table[l].querySelector('td').style.background = _col_odd;
                                    }
                                }
                            }
                        }
                    }
                } else if (_check_col % 2 !== 0 && _col_even != null) {
                    for (let j = 0; j < _columns[i].childElementCount; j++) {
                        for (let k = 0; k < _columns[i].children[j].children.length; k++) {
                            _columns[i].children[j].children[k].style.background = _col_even;
                            if (_columns[i].children[j].children[k].classList.contains('woo-compare-tr-title')) {
                                _columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-name').style.background = _col_even;
                            }
                            let __table = _columns[i].children[j].children[k].querySelectorAll('table tr');
                            if (__table != null) {
                                for (let l = 0; l < __table.length; l++) {
                                    if (__table[l].querySelector('th') != null) {
                                        __table[l].querySelector('th').style.background = _col_even;
                                        __table[l].querySelector('td').style.background = _col_even;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    function wooCompare_show_floating() {
        if (woo_compare_design_params.wpc_floating_icon_position == 'left') {
            $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-floating-icon-hide-left');
        } else {
            $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-floating-icon-hide-right');
        }
        $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-hide');
        $('.woo-compare-bar-bubble').addClass('woo-compare-bar-open');
    }

    function wooCompare_hide_floating() {
        if (woo_compare_design_params.wpc_floating_icon_position == 'left') {
            $('.woo-compare-floating-icon-wrap').addClass('woo-compare-floating-icon-hide-left');
        } else {
            $('.woo-compare-floating-icon-wrap').addClass('woo-compare-floating-icon-hide-right');
        }
        $('.woo-compare-bar-bubble').removeClass('woo-compare-bar-open');
    }

    function wooCompare_ArchiveChange() {
        $('.woo-compare-btn.woo-compare-icon').each(function () {
            if ($(this).attr('data-position') == woo_compare_design_params.wpc_btn_archive_pos) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function wooCompare_SingleChange() {
        $('.woo-compare-btn.woo-compare-single').each(function () {
            if ($(this).attr('data-position') == woo_compare_design_params.wpc_btn_single_pos) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    function wooCompareButtonLoading(product_id, direct = 0, check = 0) {
        // console.log('wooCompareButtonLoading: '+product_id+' - '+direct+' - '+check);
        let wooCompareIconButton = '<i class="' + woo_compare_design_params.wpc_btn_compare_icon + '"></i>';
        if (product_id === 'all') {
            if (check === 1) $('.woo-compare-btn').removeClass('woo-compare-btn-added woo-compare-added');
            $('.woo-compare-btn.woo-compare-single').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compare_text).css('background-color', woo_compare_design_params.wpc_btn_compare_background);
            $('.woo-compare-btn.woo-compare-single').each(function () {
                if ($(this).attr('data-position') == woo_compare_design_params.wpc_btn_single_pos) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })

            $('.woo-compare-btn.woo-compare-icon').each(function () {
                if ($(this).hasClass('woo-compare-btn-inside')) {
                    // console.log($(this));
                    $(this).html(wooCompareIconButton).css({
                        'color': woo_compare_design_params.wpc_btn_archive_color,
                        'background-color': 'initial'
                    });
                } else {
                    $(this).html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compare_text).css('background-color', woo_compare_design_params.wpc_btn_archive_background);
                }
                if ($(this).attr('data-position') == woo_compare_design_params.wpc_btn_archive_pos) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            })

            // if (!(woo_compare_design_params.wpc_btn_archive_pos == 'on_image_left') && !(woo_compare_design_params.wpc_btn_archive_pos == 'on_image_right')) {
            //     $('.woo-compare-btn.woo-compare-icon').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compare_text).css('background-color', woo_compare_design_params.wpc_btn_archive_background);
            // } else {
            //     $('.woo-compare-btn.woo-compare-icon').html(wooCompareIconButton).css({
            //         'color': woo_compare_design_params.wpc_btn_archive_color,
            //         'background-color': 'initial'
            //     });
            // }
            return;
        }
        if (direct === 0) {
            $('.woo-compare-btn[data-id="' + product_id + '"]').addClass('woo-compare-btn-added woo-compare-added');
            $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-single').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compared_text).css('background-color', woo_compare_design_params.wpc_btn_compare_added);

            $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').each(function () {
                if (!$(this).hasClass('woo-compare-btn-inside')) {
                    $(this).html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compared_text).css('background-color', woo_compare_design_params.wpc_btn_archive_added);
                } else if (woo_compare_design_params.wpc_btn_archive_added != '') {
                    $(this).html(wooCompareIconButton).css({
                        'color': woo_compare_design_params.wpc_btn_archive_added,
                        'background-color': 'initial'
                    });
                } else {
                    $(this).html(wooCompareIconButton).css({
                        'color': '#f2ca68',
                        'background-color': 'initial'
                    });
                }
            })

            // if (!(woo_compare_design_params.wpc_btn_archive_pos == 'on_image_left') && !(woo_compare_design_params.wpc_btn_archive_pos == 'on_image_right')) {
            //     $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compared_text).css('background-color', woo_compare_design_params.wpc_btn_archive_added);
            // } else if (woo_compare_design_params.wpc_btn_archive_added != '') {
            //     $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
            //         'color': woo_compare_design_params.wpc_btn_archive_added,
            //         'background-color': 'initial'
            //     });
            // } else {
            //     $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
            //         'color': '#f2ca68',
            //         'background-color': 'initial'
            //     });
            // }
        } else {
            $('.woo-compare-btn[data-id="' + product_id + '"]').removeClass('woo-compare-btn-added woo-compare-added');
            $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-single').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compare_text).css('background-color', woo_compare_design_params.wpc_btn_compare_background);

            $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').each(function () {
                if (!$(this).hasClass('woo-compare-btn-inside')) {
                    $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compare_text).css('background-color', woo_compare_design_params.wpc_btn_archive_background);
                } else {
                    $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
                        'color': woo_compare_design_params.wpc_btn_archive_color,
                        'background-color': 'initial'
                    });
                }
            })

            // if (!(woo_compare_design_params.wpc_btn_archive_pos == 'on_image_left') && !(woo_compare_design_params.wpc_btn_archive_pos == 'on_image_right')) {
            //     $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton + woo_compare_design_params.wpc_btn_compare_text).css('background-color', woo_compare_design_params.wpc_btn_archive_background);
            // } else {
            //     $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
            //         'color': woo_compare_design_params.wpc_btn_archive_color,
            //         'background-color': 'initial'
            //     });
            // }
        }
    }

    function wooCompareCheckButtons() {
        let wooCompareCookieProducts = 'wooCompare_products';
        if (woo_compare_design_params.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + woo_compare_design_params.user_id;
        }
        wooCompareButtonLoading('all', 0, 1);
        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');
            $('.woo-compare-btn').removeClass('woo-compare-btn-added woo-compare-added');

            $(document.body).trigger('wooCompare_change_button_text',
                ['all', woo_compare_design_params.wpc_btn_compare_text]);

            wooCompareProducts.forEach(function (entry) {
                wooCompareButtonLoading(entry);

                $(document.body).trigger('wooCompare_change_button_text',
                    [entry, woo_compare_design_params.wpc_btn_compared_text]);
            });
            $('.widget_wpc-widget .woo-compare-widget-remove-all').show();
            $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").removeClass('woo-compare-disable');
            $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").removeClass('woo-compare-disable');
        } else {
            $('.widget_wpc-widget .woo-compare-widget-remove-all').hide();
            $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
            $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
        }
    }

    function wooCompare_hexToRgb(hex, alpha = 1, diff = 0) {
        // Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
        let shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
        hex = hex.replace(shorthandRegex, function (m, r, g, b) {
            return r + r + g + g + b + b;
        });
        let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        if (result) {
            let _rs = [];
            _rs.push((parseInt(result[1], 16) - diff) < 0 ? 0 : (parseInt(result[1], 16) - diff));
            _rs.push((parseInt(result[2], 16) - diff) < 0 ? 0 : (parseInt(result[2], 16) - diff));
            _rs.push((parseInt(result[3], 16) - diff) < 0 ? 0 : (parseInt(result[3], 16) - diff));
            _rs.push(alpha);
            return _rs;
        }
        return null;
    }

    function wooCompareIconInvert() {
        if ($('.woo-compare-btn.woo-compare-btn-inside').length) {
            $('.woo-compare-btn.woo-compare-btn-inside').each(function () {
                let src_color = wooCompare_rgb2hex($(this).css("color"));
                let inv_color = wooCompareInvertColor(src_color);
                let _style = '0 0 2px ' + inv_color;
                $(this).css('text-shadow', _style);
            });
        }
    }

    function wooCompareInvertColor(hex, bw = false) {
        if (hex.indexOf('#') === 0) {
            hex = hex.slice(1);
        }
        // convert 3-digit hex to 6-digits.
        if (hex.length === 3) {
            hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
        }
        if (hex.length !== 6) {
            throw new Error('Invalid HEX color.');
        }
        let r = parseInt(hex.slice(0, 2), 16),
            g = parseInt(hex.slice(2, 4), 16),
            b = parseInt(hex.slice(4, 6), 16);
        if (bw) {
            return (r * 0.299 + g * 0.587 + b * 0.114) > 186
                ? '#000000'
                : '#FFFFFF';
        }
        // invert color components
        r = (255 - r).toString(16);
        g = (255 - g).toString(16);
        b = (255 - b).toString(16);
        // pad each with zeros and return
        return "#" + wooComparePadZero(r) + wooComparePadZero(g) + wooComparePadZero(b);
    }

    function wooComparePadZero(str, len) {
        len = len || 2;
        let zeros = new Array(len).join('0');
        return (zeros + str).slice(-len);
    }

    function wooCompare_rgb2hex(rgb) {
        rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" + wooCompare_hex(rgb[1]) + wooCompare_hex(rgb[2]) + wooCompare_hex(rgb[3]);
    }

    function wooCompare_hex(x) {
        return isNaN(x) ? "00" : wooCompare_hexDigits[(x - x % 16) / 16] + wooCompare_hexDigits[x % 16];
    }

})(jQuery);