jQuery(document).ready(function ($) {
    "use strict";
    let wooCompareSearchTimer = 0;
    let wooCompareFreezeTimer = 0;
    let wooCompare_slide_curr = 1;
    let wooCompare_slide_num = wooCompareVars.slide_product_num;
    let wooCompare_slideNum = $(window).width() > 600 ? wooCompareVars.slide_product_num : wooCompareVars.slide_product_num_mob;
    let wooCompare_scrollRate = $(window).width() > 600 ? 0.28 : 0.4;
    let wooCompare_freeze_num = 0;
    let wooCompare_hexDigits = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f"];

    let wooCompare_adminBarHeight = 0;
    let wooCompare_slide_able = true;
    let wooCompare_scrollLeft = 0;
    let wooCompare_scrollLevel = 0;
    let wooCompare_bottomScroll = 0;
    let wooCompare_bottomFreezeHeight = 0;
    let wooCompare_buttonSlide = false;
    let $supports_html5_storage = ('sessionStorage' in window && window.sessionStorage !== null);

    wooCompareChangeCount('first');
    wooCompareCheckButtons();
    wooCompareIconInvert();

    if (wooCompareVars.element_allow == 1) {
        if (wooCompareVars.conditional_tag) {
            wooCompareLoadDataJson(0);
            if ($supports_html5_storage) wooCompareCreateSession(wooCompareVars.session_data);
            if (wooCompareVars.popup_compare == 1) {
                wooCompare_delay_floating();
                wooCompare_scrollLeft = ($('.woo-compare-table-content').width() * wooCompare_scrollRate);
            }
            if (wooCompareVars.open_sidebar == 1) wooCompareOpenCompareBar();
        }
    } else {
        $('#woo-compare-area').html('');
        wooCompareApplyCookieSetting();
        let _isStick = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze');
        wooCompareAttributesDisplay();
        if (_isStick.length <= 1) {
            wooCompareSaveSettings();
        } else {
            wooComparePageSaveSettings();
        }

        wooCompareSearchSlime();
        wooCompareSlideInit(0);
        wooCompareDesignTable();
        // wooCompareSearchClose();
        wooComparePageRowFreeze();
        wooCompareAlternateColor();
        $('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-scroll .woo-compare-table-search-scroll-top').hide();
        wooCompare_scrollLeft = ($('.woo-compare-table-content').width() * wooCompare_scrollRate);
        $('.widget_wpc-widget .woo-compare-widget-search-content').hide();
        $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').css('left', $('#vi-woo-compare-page-table').offset().left -
            $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').outerWidth());
        $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').css('left', $('#vi-woo-compare-page-table').offset().left + $('#vi-woo-compare-page-table').outerWidth());
        // wooCompareGetPageHeight();
        if ($('#vi-woo-compare-page-table').offset().top > $(window).height() / 2.1) {
            wooCompare_buttonSlide = true;
            $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').css('top', $('#vi-woo-compare-page-table').offset().top);
            $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').css('top', $('#vi-woo-compare-page-table').offset().top);
        }
    }

    // close compare bar
    $(document).on('click touch', function (e) {
        if (($(e.target).closest('.woo-compare-variant-cart').length == 0) && (!$(e.target).hasClass('woo-compare-cart-button'))) $('.woo-compare-variant-cart').hide('slow');
        if (($(e.target).closest('.woo-compare-table-setting').length == 0) && (!$(e.target).hasClass('woo-compare-table-button-setting'))) {
            $('.woo-compare-table-setting').hide('slow');
        }
        if (($(e.target).closest('.woo-compare-table-search-inner').length == 0)) {
            let col_length = $('.woo-compare-table .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
            if (col_length >= wooCompare_slideNum) {
                if (wooCompareVars.element_allow == 1) {
                    if (wooCompareVars.conditional_tag) {
                        if (!$('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-button').hasClass('woo-compare-hide')) {
                            $('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-button').addClass('woo-compare-hide');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
                                'position': 'relative',
                                'width': '100%',
                                'left': 'unset'
                            }).removeClass('woo-compare-open');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').hide();
                        }
                    }
                } else {
                    if (!$('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-button').hasClass('woo-compare-hide')) {
                        $('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-button').addClass('woo-compare-hide');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
                            'position': 'relative',
                            'width': '100%',
                            'left': 'unset'
                        }).removeClass('woo-compare-open');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').hide();
                    }
                }
            } else if (col_length > 0) {
                wooCompareSearchSlime(1);
            }
        }
    });

    $.fn.serializeArrayAll = function () {
        let rCRLF = /\r?\n/g;
        return this.map(function () {
            return this.elements ? jQuery.makeArray(this.elements) : this;
        }).map(function (i, elem) {
            let val = $(this).val();
            if (val == null) {
                return val == null
                //next 2 lines of code look if it is a checkbox and set the value to blank
                //if it is unchecked
            } else if (this.type == "checkbox" && this.checked == false) {
                return {name: this.name, value: this.checked ? this.value : ''}
                //next lines are kept from default jQuery implementation and
                //default to all checkboxes = on
            } else {
                return Array.isArray(val) ?
                    $.map(val, function (val, i) {
                        return {name: elem.name, value: val.replace(rCRLF, "\r\n")};
                    }) :
                    {name: elem.name, value: val.replace(rCRLF, "\r\n")};
            }
        }).get();
    };

    $(document).on('click', '.woo-compare-variant-cart .single_add_to_cart_button:not(.disabled)', function (e) {
        let $thisButton = $(this),
            $form = $thisButton.closest('form.cart'),
            data = {},
            data_arr = $form.find('input:not([name="product_id"]), select, button, textarea').serializeArrayAll() || 0;

        $.each(data_arr, function (i, item) {
            if (item.name == 'add-to-cart') {
                item.name = 'product_id';
                item.value = $form.find('input[name=variation_id]').val() || $thisButton.val();
            }
        });
        for (let item of data_arr) {
            data[item.name] = item.value;
        }
        data['action'] = 'wpc_variation_cart';
        data['nonce'] = wooCompareVars.nonce;

        e.preventDefault();
        $(document.body).trigger('adding_to_cart', [$thisButton, data]);

        $.ajax({
            type: 'POST',
            url: wooCompareVars.ajaxurl,
            data: data,
            beforeSend: function (response) {
                $thisButton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisButton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                if (response.error & response.product_url) {
                    window.location = response.product_url;
                    return;
                }
                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisButton]);
                if (wooCompareVars.is_checkout) $(document.body).trigger('update_checkout');
            },
        });

        return false;
    });

    // add
    $(document).on('click touch', '.woo-compare-btn', function (e) {
        // let _widgetLoad = $('.woo-compare-widget-products').length > 0 ? $('.woo-compare-widget-products').hasClass('woo-compare-loading') : false;
        // let _barLoad = $('.woo-compare-bar-items').length > 0 ? !$('.woo-compare-bar-items').hasClass('woo-compare-bar-items-loaded') : false;
        // let _tableLoad = $('.woo-compare-table-inner').length > 0 ? $('.woo-compare-table-inner').hasClass('woo-compare-loading') : false;
        // if (!_tableLoad && !_barLoad && !_widgetLoad) {
        if (!$(this).hasClass('loading')) {
            let id = $(this).attr('data-id');
            if ($(this).hasClass('woo-compare-btn-added woo-compare-added')) {
                //delete mode
                if (wooCompareVars.remove_mode == 1) {
                    let cf = confirm(wooCompareVars.remove_single_notice);
                    if (cf == true) {
                        wooCompareRemove(id);
                    }
                } else {
                    if (wooCompareVars.popup_compare == 1 && wooCompareVars.open_added_action == 0) {
                        if (!$('.woo-compare-table-items').hasClass('woo-compare-table-items-loaded')) {
                            wooCompareLoadDataJson(1, id, 1, 1);
                            wooCompareOpenComparePopup();
                        } else {
                            wooCompareOpenComparePopup();

                        }
                    } else {
                        if (wooCompareVars.page_url != '' && wooCompareVars.page_url != '#') {
                            window.location.href = wooCompareVars.page_url;
                        }
                    }
                }
            } else {
                $(this).addClass('loading');
                if (wooCompareAddProduct(id)) wooCompareLoadDataJson(1, id); else $(this).removeClass('loading');
            }
        }
        e.preventDefault();
    });

    $(document).on('click touch', '.woo-compare-bar-btn-icon-wrapper', function (e) {
        if ($('.woo-compare-bar').hasClass('woo-compare-bar-bubble')) {
            $('.woo-compare-bar').removeClass('woo-compare-bar-bubble');
        } else {
            $('.woo-compare-bar').addClass('woo-compare-bar-bubble');
        }
    });

    // compare bar button
    $(document).on('click touch', '.woo-compare-bar-btn', function () {
        if (wooCompareVars.popup_compare == 1 && wooCompareVars.open_added_action == 0) {
            wooCompareCloseCompareBar();
            wooCompareOpenComparePopup();
        } else {
            window.location.href = wooCompareVars.page_url;
        }
    });

    // close
    $(document).on('click touch', '#woo-compare-table-close', function () {
        wooCompareCloseComparePopup();
        $(document.body).trigger('wooCompare_close');
    });

    function wooCompareCreateSession(data = []) {
        if ($supports_html5_storage) {
            let _session = 'wooCompare_html_' + wooCompareVars.user_id;
            sessionStorage.setItem(_session, JSON.stringify(data));
        }
    }

    function wooCompareLoadSession(target) {
        let _session = 'wooCompare_html_' + wooCompareVars.user_id;
        if (sessionStorage.getItem(_session) != null) {
            let _data = JSON.parse(sessionStorage.getItem(_session));
            if (target === 1) return _data[1]; else return _data[0];
        }
        return '';
    }

    //add product to compare list
    function wooCompareAddProduct(product_id) {
        let wooCompareLimit = false;
        let wooCompareLimitNotice = wooCompareVars.limit_notice;
        let wooCompareCookieProducts = 'wooCompare_products';
        let wooCompareCount = 0;

        if (wooCompareVars.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + wooCompareVars.user_id;
        }

        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');

            if (wooCompareProducts.length < wooCompareVars.limit) {
                wooCompareProducts = $.grep(wooCompareProducts, function (value) {
                    return value != product_id;
                });
                wooCompareProducts.unshift(product_id);

                let wooCompareProductsStr = wooCompareProducts.join();

                wooCompareSetCookie(wooCompareCookieProducts, wooCompareProductsStr, 7);
            } else {
                wooCompareLimit = true;
                wooCompareLimitNotice = wooCompareLimitNotice.replace('{limit}', wooCompareVars.limit);
            }
            wooCompareCount = wooCompareProducts.length;
        } else {
            $('.widget_wpc-widget .woo-compare-widget-products').html('');
            wooCompareSetCookie(wooCompareCookieProducts, product_id, 7);
            wooCompareCount = 1;
        }

        wooCompareChangeCount(wooCompareCount);
        $(document.body).trigger('wooCompare_added', [wooCompareCount]);

        if (wooCompareLimit) {
            alert(wooCompareLimitNotice);
            return false;
        } else {
            $(document.body).trigger('wooCompare_change_button_text',
                [product_id, wooCompareVars.button_text_added]);
        }
        return true;
    }

    function wooCompareButtonLoading(product_id, direct = 0, check = 0) {
        let wooCompareIconButton = '<i class="' + wooCompareVars.button_icon + '"></i>';
        if (product_id === 'all') {
            if (check === 1) $('.woo-compare-btn').removeClass('woo-compare-btn-added woo-compare-added');
            $('.woo-compare-btn.woo-compare-single').html(wooCompareIconButton + wooCompareVars.button_text).css('background-color', wooCompareVars.button_background);
            if (wooCompareVars.button_achieve_pos == 1) {
                $('.woo-compare-btn.woo-compare-icon').html(wooCompareIconButton + wooCompareVars.button_text).css('background-color', wooCompareVars.button_achieve_background);
            } else {
                $('.woo-compare-btn.woo-compare-icon').html(wooCompareIconButton).css({
                    'color': wooCompareVars.button_achieve_color,
                    'background-color': 'initial'
                });
            }
            return;
        }
        if (direct === 0) {
            $('.woo-compare-btn[data-id="' + product_id + '"]').addClass('woo-compare-btn-added woo-compare-added');
            $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-single').html(wooCompareIconButton + wooCompareVars.button_text_added).css('background-color', wooCompareVars.button_added);
            if (wooCompareVars.button_achieve_pos == 1) {
                $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton + wooCompareVars.button_text_added).css('background-color', wooCompareVars.button_achieve_added);
            } else if (wooCompareVars.button_achieve_added != '') {
                $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
                    'color': wooCompareVars.button_achieve_added,
                    'background-color': 'initial'
                });
            } else {
                $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
                    'color': '#f2ca68',
                    'background-color': 'initial'
                });
            }
        } else {
            $('.woo-compare-btn[data-id="' + product_id + '"]').removeClass('woo-compare-btn-added woo-compare-added');
            $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-single').html(wooCompareIconButton + wooCompareVars.button_text).css('background-color', wooCompareVars.button_background);
            if (wooCompareVars.button_achieve_pos == 1) {
                $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton + wooCompareVars.button_text).css('background-color', wooCompareVars.button_achieve_background);
            } else {
                $('.woo-compare-btn[data-id="' + product_id + '"].woo-compare-icon').html(wooCompareIconButton).css({
                    'color': wooCompareVars.button_achieve_color,
                    'background-color': 'initial'
                });
            }
        }
        wooCompareIconInvert();
    }

    function wooCompareRemoveProduct(product_id) {
        let wooCompareCookieProducts = 'wooCompare_products';
        let wooCompareCount = 0;

        if (wooCompareVars.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + wooCompareVars.user_id;
        }

        if (product_id != 'all') {
            // remove one
            if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
                let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');

                wooCompareProducts = $.grep(wooCompareProducts, function (value) {
                    return value != product_id;
                });

                let wooCompareProductsStr = wooCompareProducts.join();

                wooCompareSetCookie(wooCompareCookieProducts, wooCompareProductsStr, 7);
                wooCompareCount = wooCompareProducts.length;
            }

            wooCompareButtonLoading(product_id, 1);
            $(document.body).trigger('wooCompare_change_button_text',
                [product_id, wooCompareVars.button_text]);
        } else {
            // remove all
            if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
                wooCompareSetCookie(wooCompareCookieProducts, '', 7);
                wooCompareCount = 0;
            }

            wooCompareButtonLoading('all', 0, 1);

            $(document.body).trigger('wooCompare_change_button_text', ['all', wooCompareVars.button_text]);
        }

        wooCompareChangeCount(wooCompareCount);
        if (wooCompareCount === 0) {
            $('.widget_wpc-widget .woo-compare-widget-remove-all').addClass('woo-compare-hide');
            $('.widget_wpc-widget .woo-compare-widget-btn').addClass('woo-compare-hide');
            $('.widget_wpc-widget .woo-compare-widget-products').html('No products in compare list');
        }
        $(document.body).trigger('wooCompare_removed', [wooCompareCount]);
    }

    //Load compare bar
    function wooCompareLoadCompareBar(stage = 0, single_product) {
        if (wooCompareVars.conditional_tag && wooCompareVars.open_sidebar == 1) {
            let _products = '';
            stage === 0 ? _products = wooCompareGetProducts() : _products = single_product;
            let data = {
                action: 'wpc_load_compare_bar',
                products: _products,
                nonce: wooCompareVars.nonce,
            };
            $.post(wooCompareVars.ajaxurl, data, function (response) {
                if ((wooCompareVars.hide_empty == 1) && ((response == '') || (response == 0))) {
                    $('.woo-compare-bar-items').removeClass('woo-compare-bar-items-loaded');
                    wooCompareCloseCompareBar();
                } else {
                    stage === 0 ? $('.woo-compare-bar-items').html(response).addClass('woo-compare-bar-items-loaded') : $('.woo-compare-bar-items').append(response).addClass('woo-compare-bar-items-loaded');
                    if (!$('.woo-compare-table').hasClass('woo-compare-table-open')) {
                        wooCompareOpenCompareBar();
                    } else {
                        wooCompareCloseCompareBar();
                    }
                }
            });
        }
    }

    //get user cookie
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

    //save data to cookie
    function wooCompareSetCookie(cname, cvalue, exdays) {
        let d = new Date();
        d.setTime(d.getTime() + (
            exdays * 24 * 60 * 60 * 1000
        ));
        let expires = 'expires=' + d.toUTCString();
        document.cookie = cname + '=' + cvalue + '; ' + expires + '; path=/';
    }

    function wooCompareChangeCount(count) {
        if (count == 'first') {
            let products = wooCompareGetProducts();
            if (products != '') {
                let products_arr = products.split(',');
                count = products_arr.length;
            } else {
                count = 0;
            }
        }

        $('.woo-compare-bar').attr('data-count', count);
        $(document.body).trigger('wooCompare_change_count', [count]);
    }

    function wooCompareGetProducts() {
        let wooCompareCookieProducts = 'wooCompare_products';

        if (wooCompareVars.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + wooCompareVars.user_id;
        }

        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            return wooCompareGetCookie(wooCompareCookieProducts);
        } else {
            return '';
        }
    }

    function wooCompareLoadComparePopup(wooCompare_count = 0, wooCompare_product_single = '') {
        $('.woo-compare-table-inner').addClass('woo-compare-loading');
        let _products = '';
        wooCompare_count === 0 ? _products = wooCompareGetProducts() : _products = wooCompare_product_single;
        _products == '' ? $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").hide() : $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").show();
        let data = {
            action: 'wpc_load_compare_table',
            products: _products,
            stage: wooCompare_count,
            nonce: wooCompareVars.nonce,
        };
        $.post(wooCompareVars.ajaxurl, data, function (response) {
            if (wooCompare_count === 0) {
                $('.woo-compare-table-items').html(response).addClass('woo-compare-table-items-loaded');
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content').append(response);
            }
            $('.woo-compare-table-inner').removeClass('woo-compare-loading');
            if (wooCompareVars.conditional_tag && wooCompareVars.popup_compare) {
                wooComparePopupFreezeInit();
            } else {
                $('#vi-woo-compare-table').html('');
            }
            wooCompareSlideInit();
            wooCompareDesignTable();
            wooCompareAlternateColor();
        });
    }

    function wooCompareOpenComparePopup() {
        if (wooCompareVars.conditional_tag && wooCompareVars.popup_compare) {
            wooCompare_hide_floating();
            wooCompareCloseCompareBar();
            if (!$('#vi-woo-compare-table').hasClass('woo-compare-table-open')) {
                $('#vi-woo-compare-table').addClass('woo-compare-table-open');
                $('.woo-compare-area .woo-compare-inner .woo-compare-overlay').addClass('woo-compare-table-open');
                wooCompareCloseCompareBar();
                $(document).on('keyup', closeOnEsc);
                wpc_disable_scroll();
                $(document.body).trigger('wooCompare_popup_open');
            }
        } else {
            window.location.href = wooCompareVars.page_url;
        }
    }

    function wooCompareToggleComparePopup() {
        if ($('.woo-compare-table').hasClass('woo-compare-table-open')) {
            wooCompareCloseComparePopup();
        } else {
            wooCompareOpenComparePopup();
        }
    }

    function wooCompareCloseComparePopup() {
        $('#woo-compare-area').removeClass('woo-compare-area-open');
        $('.woo-compare-table').removeClass('woo-compare-table-open');
        $('.woo-compare-area .woo-compare-inner .woo-compare-overlay').removeClass('woo-compare-table-open');
        wooCompareOpenCompareBar();
        wpc_enable_scroll();
        if (wooCompareVars.popup_compare == 1) {
            wooCompare_show_floating();
        }
        $(document.body).trigger('wooCompare_popup_close');
    }

    function wooCompareOpenCompareBar() {
        if (wooCompareVars.conditional_tag && wooCompareVars.open_sidebar == 1 &&
            ((wooCompareVars.hide_empty == 0) || (wooCompareVars.hide_empty == 1 && $('.woo-compare-area .woo-compare-bar .woo-compare-bar-item').length != 0))) {
            $('.woo-compare-area .woo-compare-bar').addClass('woo-compare-bar-open').addClass('woo-compare-bar-bubble');
        }
    }

    function wooCompareCloseCompareBar() {
        if (wooCompareVars.open_sidebar == 1) {
            $('.woo-compare-area .woo-compare-bar').removeClass('woo-compare-bar-open');
        }
    }

    function wooCompareCheckButtons() {
        let wooCompareCookieProducts = 'wooCompare_products';
        if (wooCompareVars.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + wooCompareVars.user_id;
        }
        wooCompareButtonLoading('all', 0, 1);
        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');
            $('.woo-compare-btn').removeClass('woo-compare-btn-added woo-compare-added');

            $(document.body).trigger('wooCompare_change_button_text',
                ['all', wooCompareVars.button_text]);

            wooCompareProducts.forEach(function (entry) {
                wooCompareButtonLoading(entry);

                $(document.body).trigger('wooCompare_change_button_text',
                    [entry, wooCompareVars.button_text_added]);
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

    function wooCompareAlternateColor() {
        switch (wooCompareVars.alternate_type) {
            case 'row':
                wooCompareAlternateRow(wooCompareVars.alternate_row_odd, wooCompareVars.alternate_row_even);
                break;
            case 'col':
                wooCompareAlternateCol(wooCompareVars.alternate_col_odd, wooCompareVars.alternate_col_even);
                break;
            default:
                break;
        }
    }

    function wooCompareAlternateRow(_row_odd, _row_even) {
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header .woo-compare-tr-title').css('background-color', _row_odd);
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title').css('background-color', _row_odd);
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-tr-title-name').css('background-color', _row_odd);
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-variant-cart').css('background-color', _row_odd);
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .woo-compare-variant-cart .variations_form').css('background-color', _row_odd);
        let _column = $('.woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        for (let i = 0; i < _column.length; i++) {
            let _check = 0;
            let _rows = [];
            if (_column[i].querySelectorAll('.woo-compare-table-row-freeze.woo-compare-element-stuck').length > 0) {
                _rows = _column[i].querySelectorAll('.woo-compare-table-row-freeze.woo-compare-element-stuck .woo-compare-cell, .woo-compare-table-row-free .woo-compare-cell')
            } else {
                _rows = _column[i].querySelectorAll('.woo-compare-table-row-freeze .woo-compare-cell, .woo-compare-table-row-free .woo-compare-cell')
            }
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
                                }
                                if (__table[k].querySelector('td') != null) __table[k].querySelector('td').style.background = _row_even;
                            }
                        }
                    } else if (_check % 2 === 0 && _row_odd != null) {
                        _rows[j].style.background = _row_odd;
                        let __table = _rows[j].querySelectorAll('table tr');
                        if (__table != null) {
                            for (let k = 0; k < __table.length; k++) {
                                if (__table[k].querySelector('th') != null) {
                                    __table[k].querySelector('th').style.background = _row_odd;
                                }
                                if (__table[k].querySelector('td') != null) __table[k].querySelector('td').style.background = _row_odd;
                            }
                        }
                    }
                }
            }
        }
        let _head_check = 0;
        let _head = [];
        if ($('.woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze.woo-compare-element-stuck').length > 0) {
            _head = $('.woo-compare-table-field-header-freeze.woo-compare-element-stuck .woo-compare-cell, .woo-compare-table-field-header-free .woo-compare-cell');
        } else {
            _head = $('.woo-compare-table-items .woo-compare-table-field-header .woo-compare-cell');
        }
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
                                if (_columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-cart .woo-compare-variant-cart .variations_form')) {
                                    _columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-cart .woo-compare-variant-cart .variations_form').style.background = _col_odd;
                                    _columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-cart .woo-compare-variant-cart').style.background = _col_odd;
                                }
                            }
                            let __table = _columns[i].children[j].children[k].querySelectorAll('table tr');
                            if (__table != null) {
                                for (let l = 0; l < __table.length; l++) {
                                    if (__table[l].querySelector('th') != null) {
                                        __table[l].querySelector('th').style.background = _col_odd;
                                    }
                                    if (__table[l].querySelector('td') != null) __table[l].querySelector('td').style.background = _col_odd;
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
                                if (_columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-cart .woo-compare-variant-cart .variations_form')) {
                                    _columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-cart .woo-compare-variant-cart .variations_form').style.background = _col_even;
                                    _columns[i].children[j].children[k].children[0].querySelector('.woo-compare-tr-title-cart .woo-compare-variant-cart').style.background = _col_even;
                                }
                            }
                            let __table = _columns[i].children[j].children[k].querySelectorAll('table tr');
                            if (__table != null) {
                                for (let l = 0; l < __table.length; l++) {
                                    if (__table[l].querySelector('th') != null) {
                                        __table[l].querySelector('th').style.background = _col_even;
                                    }
                                    if (__table[l].querySelector('td') != null) __table[l].querySelector('td').style.background = _col_even;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // remove single
    $(document).on('click touch', '#woo-compare-area .woo-compare-bar-item-remove', function (e) {
        let cf = confirm(wooCompareVars.remove_single_notice);
        if (cf == true) {
            let product_id = $(this).attr('data-id');
            $(this).parent().addClass('removing');
            wooCompareRemove(product_id);
            e.preventDefault();
        }
    });

    // remove all
    $(document).on('click touch', '.woo-compare-bar-remove', function () {
        let cf = confirm(wooCompareVars.remove_all_notice);
        if (cf == true) {
            wooCompareRemove('all');
        }
    });

    $(document).on('click touch', '.woo-compare-area .woo-compare-inner .woo-compare-overlay', function () {
        if ($('.woo-compare-table').hasClass('woo-compare-table-open')) {
            $('#woo-compare-table-close').trigger("click");
        }
    });

    $(document).on('click touch', '.woo-compare-widget-btn', function (e) {
        e.preventDefault();
        if (wooCompareVars.open_added_action == 0 && wooCompareVars.popup_compare == 1) {
            wooCompareCloseCompareBar();
            wooCompareOpenComparePopup();
            $('body').trigger('wooCompare_open_popup', {response: $(this).attr('href')});
        } else {
            window.location.href = wooCompareVars.page_url;
        }
    });

    $(document).on('click touch', '.woo-compare-widget-remove-single', function (e) {
        let cf = confirm(wooCompareVars.remove_single_notice);
        if (cf == true) {
            let product_id = $(this).attr('data-id');
            wooCompareRemove(product_id);
            e.preventDefault();
        }
    });

    $(document).on('click touch', '.woo-compare-widget-remove-all', function (e) {
        let cf = confirm(wooCompareVars.remove_all_notice);
        if (cf == true) {
            let product_id = $(this).attr('data-id');
            wooCompareRemove(product_id);
            e.preventDefault();
        }
    });

    $(document).on('keyup', '#woo_compare_widget_search_input', function () {
        let _digit = $(this).val();
        if (_digit !== '' && _digit.length >= 3) {
            if (wooCompareSearchTimer != null) {
                clearTimeout(wooCompareSearchTimer);
            }
            wooCompareSearchTimer = setTimeout(wooCompareAjaxSearch, 800, _digit, 0);
            return false;
        } else {
            // $('.woo-compare-widget-search-result').html('');
        }
    });

    $(document).on('keyup', '#woo_compare_table_search_input', function () {
        let _digit = $(this).val();
        if (_digit !== '' && _digit.length >= 3) {
            if (wooCompareSearchTimer != null) {
                clearTimeout(wooCompareSearchTimer);
            }
            wooCompareSearchTimer = setTimeout(wooCompareAjaxSearch, 800, _digit, 1);
            return false;
        } else {
            // $('.woo-compare-table-search-result').html('');
            // $('.woo-compare-table-search-button').addClass('open');
        }
    });

    $(document).on('click touch', '.woo-compare-item-add', function () {
        let product_id = $(this).attr('data-id');
        let removeObject = $(this).closest('li');
        let wooCompareCookieProducts = 'wooCompare_products';
        if (wooCompareVars.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + wooCompareVars.user_id;
        }
        if (wooCompareVars.element_allow == 0) {
            $('html, body').scrollTop(0);
            wpc_disable_scroll();
        }
        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');
            if (!wooCompareProducts.includes(product_id)) {
                if (wooCompareAddProduct(product_id)) {
                    wooCompareLoadDataJson(1, product_id, wooCompareVars.open_sidebar, wooCompareVars.popup_compare);
                    setTimeout(function () {
                        removeObject.remove();
                    }, 200);
                } else wpc_enable_scroll();
            } else {
                if (wooCompareVars.element_allow == 0) wpc_enable_scroll();
                alert('Product already exits in compare table');
                setTimeout(function () {
                    removeObject.remove();
                }, 200);
            }
        } else {
            if (wooCompareAddProduct(product_id)) {
                wooCompareLoadDataJson(1, product_id, wooCompareVars.open_sidebar, wooCompareVars.popup_compare);
                setTimeout(function () {
                    removeObject.remove();
                }, 200);
            } else wpc_enable_scroll();
        }

    });

    $(document).on('click touch', '.woo-compare-floating-icon-wrap', function () {
        if (wooCompareVars.floating_icon_open == 0 && wooCompareVars.popup_compare == 1) {
            wooCompareCloseCompareBar();
            wooCompareOpenComparePopup();
        } else {
            window.location.href = wooCompareVars.page_url;
        }
    });

    //close floating
    $(document).on('click touch', '.woo-compare-floating-icon-close', function (e) {
        e.stopPropagation();
        wooCompare_hide_floating();
    });

    //setting
    $(document).on('click touch', '.woo-compare-table-button-setting', function () {
        if (!$(this).hasClass("woo-compare-disable")) {
            if (wooCompareVars.element_allow == 0) {
                let _top = $('#vi-woo-compare-page-table .woo-compare-table-button-setting').offset().top - $(window).scrollTop();
                $('#vi-woo-compare-page-table .woo-compare-table-setting').css({
                    'position': 'fixed',
                    'top': (_top > 0) ? _top + $('#vi-woo-compare-page-table .woo-compare-table-button-setting').outerHeight() : $('#vi-woo-compare-page-table .woo-compare-table-button-setting').outerHeight(),
                    'left': $('#vi-woo-compare-page-table .woo-compare-table-button-setting').offset().left,
                });
            }
            $('.woo-compare-table-setting').slideToggle("slow");
        }
    });

    // change settings page
    $(document).on('change', '.woo-compare-page-table-setting-fields', function () {
        wooCompareSaveSettings();
    });

    // change settings table
    $(document).on('change', '.woo-compare-popup-table-setting-fields', function () {
        if (wooCompareVars.element_allow == 1) {
            wooCompareSaveSettings();
        } else {
            let _isStick = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze');
            if (_isStick.length <= 1) {
                wooCompareSaveSettings();
                // wooCompareGetPageHeight();
            } else {
                wooComparePageSaveSettings();
                // wooCompareGetPageHeight(1);
            }
        }
    });

    $(document).on('click touch', '.woo-compare-table-product-freeze', function () {
        if ($(this).attr('data-freeze') == 0) {
            $(this).parent().removeClass('woo-compare-iced').addClass('woo-compare-iced');
            $(this).attr('data-freeze', 1);
            $('.woo-compare-table-content').prepend($(this).closest('.woo-compare-table-column')).prepend($('.woo-compare-table-column-freeze')).prepend($('.woo-compare-table-field-header'));
            $(this).closest('.woo-compare-table-column').removeClass('woo-compare-table-column-free').addClass('woo-compare-table-column-freeze').css('left', '');
        } else {
            $(this).parent().removeClass('woo-compare-iced');
            $(this).attr('data-freeze', 0);
            $('.woo-compare-table-content').append($('.woo-compare-table-field-header')).append($('.woo-compare-table-column.woo-compare-table-column-freeze')).append($('.woo-compare-table-column.woo-compare-hide')).append($(this).closest('.woo-compare-table-column')).append($('.woo-compare-table-column.woo-compare-table-column-free').not('.woo-compare-hide'));
            let _free_left = ($('.woo-compare-table-column-free').length > 0) ? $('.woo-compare-table-column-free').eq(0).css('left') : wooCompare_scrollLevel * wooCompare_scrollLeft;
            $(this).closest('.woo-compare-table-column').removeClass('woo-compare-table-column-freeze').addClass('woo-compare-table-column-free').css({
                'left': _free_left,
            });
        }
        wooCompareSlideInit(4);
        wooCompareAlternateColor();
    });

    //table single remove
    $(document).on('click touch', '.woo-compare-table-product-remove', function () {
        let cf = confirm(wooCompareVars.remove_single_notice);
        if (cf == true) {
            let productId = $(this).attr('data-id');
            wooCompareRemove(productId);
            // wooCompareSearchClose(0);
        }
    });

    $(document).on('click touch', '.woo-compare-table-button-clear', function () {
        if (!$(this).hasClass('woo-compare-disable')) {
            let cf = confirm(wooCompareVars.remove_all_notice);
            if (cf == true) {
                $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
                $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
                wooCompareRemove('all');
                wooCompareCloseComparePopup();
            }
        }
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

    $(document).on('click touch', '.woo-compare-table-search-scroll-top', function () {
        let _add = $('.entry-title') != null ? $('.entry-title').outerHeight() + 100 : 200;
        let _top = $('#vi-woo-compare-page-table').offset().top - _add;
        $('html, body').animate({scrollTop: _top}, 'fast');
    });

    $(document).on('click touch', '.woo-compare-slide-next', function () {
        if (!$(this).hasClass('woo-compare-disabled')) {
            wooCompare_scrollLevel -= 1;
            wooCompare_scrollLeft = ($('.woo-compare-table-content').width() * wooCompare_scrollRate);
            $(this).addClass('woo-compare-disabled');
            let _content = $('.woo-compare-table-content .woo-compare-table-column-free');
            let _content_freeze = $('.woo-compare-table-content .woo-compare-table-column-freeze');
            let _left = $('.woo-compare-table-field-header').width();
            _left += _content_freeze.length * wooCompare_scrollLeft;
            let _diff = _content_freeze.length <= 0 ? _left : wooCompare_scrollLeft;
            if ((_content[_content.length - 1].offsetLeft + wooCompare_scrollLeft / 2) > $('.woo-compare-table-items').width()) {
                let _count = 0;
                _content.each(function () {
                    _count += 1;
                    $(this).css('opacity', 1);
                    if (($(this).position().left + wooCompare_scrollLeft / 2) < _left + wooCompare_scrollLeft) {
                        $(this).addClass('woo-compare-hide');
                    } else {
                        $(this).removeClass('woo-compare-hide');
                    }
                    if (($(this).position().left + wooCompare_scrollLeft / 2) > ($('.woo-compare-table-items').width() + wooCompare_scrollLeft)) {
                        $(this).removeClass('woo-compare-hide-right').addClass('woo-compare-hide-right');
                    } else {
                        $(this).removeClass('woo-compare-hide-right');
                    }

                    $(this).animate({
                        left: '-=' + wooCompare_scrollLeft + 'px',
                    }, 300, function () {
                        if ($(this).hasClass('woo-compare-hide')) {
                            $(this).css('opacity', 0);
                        } else {
                            $(this).css('opacity', 1);
                        }
                        if (_count >= _content.length) {
                            $('.woo-compare-slide-next').removeClass('woo-compare-disabled');
                        }
                    })
                });
                setTimeout(wooCompareAlternateColor, 300);
            } else {
                $(this).removeClass('woo-compare-disabled');
            }
            wooCompareSlideInit(1, 1);
        }
    });

    $(document).on('click touch', '.woo-compare-slide-prev', function () {
        if (!$(this).hasClass('woo-compare-disabled')) {
            wooCompare_scrollLevel += 1;
            wooCompare_scrollLeft = ($('.woo-compare-table-content').width() * wooCompare_scrollRate);
            $(this).addClass('woo-compare-disabled');
            let _content = $('.woo-compare-table-content .woo-compare-table-column-free');
            let _content_freeze = $('.woo-compare-table-content .woo-compare-table-column-freeze');
            let _left = $('.woo-compare-table-field-header').width();
            _left += _content_freeze.length * wooCompare_scrollLeft;
            let _diff = _content_freeze.length <= 0 ? _left : wooCompare_scrollLeft;
            if ((_content[0].offsetLeft + _diff / 2) < _left) {
                let _count = 0;
                _content.each(function () {
                    _count += 1;
                    if (($(this).position().left + wooCompare_scrollLeft + _diff / 2) < _left) {
                        $(this).css('opacity', 0).addClass('woo-compare-hide');
                    } else {
                        $(this).css('opacity', 1).removeClass('woo-compare-hide');
                    }
                    if (($(this).position().left + wooCompare_scrollLeft * 1.5) > $('.woo-compare-table-items').width()) {
                        $(this).addClass('woo-compare-hide-right');
                    } else {
                        $(this).removeClass('woo-compare-hide-right');
                    }

                    $(this).animate({
                        left: '+=' + wooCompare_scrollLeft + 'px',
                    }, 300, function () {
                        if (_count >= _content.length) {
                            $('.woo-compare-slide-prev').removeClass('woo-compare-disabled');
                        }
                    })
                });
                setTimeout(wooCompareAlternateColor, 300);
            } else {
                $(this).removeClass('woo-compare-disabled');
            }
            wooCompareSlideInit(1, 0);
        }
    });

    $(document).on('click touch', '.woo-compare-cart-button-contain .woo-compare-cart-button', function () {
        $(this).closest('div').find('.woo-compare-variant-cart').slideToggle();
    });

    $(document.body).on('click', '.woo-compare-content-short', function (e) {
        e.stopPropagation();
        // let $button = $(this);
        // let $des_content = $button.closest('.woo-compare-tr-description');
        // let $des_content_full = $des_content.find('.woo-compare-content-full');
        // let des_content_full = $des_content_full.html();
        // if (des_content_full) {
        //     $des_content.html(des_content_full);
        // }

        let long_des = $('.woo-compare-content-short').closest('.woo-compare-tr-description');
        if (long_des.length) {
            long_des.each(function () {
                $(this).html($(this).find('.woo-compare-content-full').html());
            })
        }
        wooCompareTableRowSize();
    });

    function wooCompareSearchTimeout(_by = 0) {
        let _digit = _by === 0 ? $('#woo_compare_widget_search_input').val() : $('#woo_compare_table_search_input').val();
        if (_digit !== '' && _digit.length >= 3) {
            if (wooCompareSearchTimer != null) {
                clearTimeout(wooCompareSearchTimer);
            }
            wooCompareSearchTimer = setTimeout(wooCompareAjaxSearch, 300, _digit, _by);
            return false;
        } else {
            if (_by === 0) {
                $('.woo-compare-widget-search-result').html('');
            } else {
                $('.woo-compare-table-search-result').html('');
                $('.woo-compare-table-search-button').addClass('open');
            }
        }
    }

    function wooCompareRemove(product) {
        wooCompareRemoveProduct(product);
        if (product == 'all') {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').remove();
            $('.woo-compare-widget-products li').remove();
            $('.woo-compare-bar-item').remove();
            wooCompare_slide_curr = 1;
            if (wooCompareVars.hide_empty == 1) {
                wooCompare_hide_floating();
                wooCompareCloseCompareBar()
            }
        } else {
            if (!$('.woo-compare-table-item-' + product).hasClass('col-hide') && wooCompare_slide_curr != 0) wooCompare_slide_curr -= 1;
            // if ($('.woo-compare-table-item-' + product).hasClass('woo-compare-table-column-freeze')) {
            //     wooCompare_slide_num += 1;
            //     wooCompare_freeze_num -= 1;
            // }
            $('.woo-compare-table-item-' + product).remove();
            $('.woo-compare-widget-item-' + product).remove();
            $('.woo-compare-bar-item-' + product).remove();
            if (wooCompareVars.hide_empty == 1 && $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-content .woo-compare-table-column').length == 0) {
                wooCompare_hide_floating();
                wooCompareCloseCompareBar()
            }
        }
        //re init table
        wooCompareSearchSlime();
        wooCompareAttributesDisplay();
        wooCompareSlideInit(3);
        wooCompareDesignTable();
        wooCompareAlternateColor();
        wooComparePopupFreezeInit();
        wooCompareIconInvert();
    }

    function closeOnEsc(e) {
        if ($('.woo-compare-table').hasClass('woo-compare-table-open') && e.keyCode === 27) {
            $('#woo-compare-table-close').trigger("click");
        }
    }

    //widget ajax search
    function wooCompareAjaxSearch(keyWord, resultTo) {
        // let _key = '';
        if (resultTo === 0) {
            $('.woo-compare-widget-search-result').html('').addClass('woo-compare-loading');
        } else {
            $('.woo-compare-table-search-result').html('').show().addClass('woo-compare-loading');
        }
        // ajax search product
        wooCompareSearchTimer = null;

        let data = {
            action: 'wpc_search_widget',
            keyword: keyWord,
            nonce: wooCompareVars.nonce,
        };

        $.post(wooCompareVars.ajaxurl, data, function (response) {
            if (resultTo === 0) {
                $('.woo-compare-widget-search-result').html(response).removeClass('woo-compare-loading');
            } else {
                $('.woo-compare-table-search-result').html(response).removeClass('woo-compare-loading');
            }
        });
    }

    function wooCompareColFreezeControl() {
        let col_freeze = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column-freeze');
        let col_normal = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column-free');

        for (let i = 0; i < parseInt(col_freeze.length); i++) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content').append(col_freeze[i]);
        }
        for (let i = 0; i < parseInt(col_normal.length); i++) {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content').append(col_normal[i]);
        }
    }

    function wooComparePageRowFreeze() {
        if ($('body').has('#wpadminbar')) {
            wooCompare_adminBarHeight = $("#wpadminbar").height() != null ? $("#wpadminbar").height() : 0;
        } else {
            wooCompare_adminBarHeight = 0;
        }
        $(window).on('scroll', function () {
            wooCompareGetPageHeight();
            $('.woo-compare-table .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-setting').hide();
            let scrollPosition = $(window).scrollTop();
            let scrollPart = $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').outerHeight() * 0.5;
            let tableTop = $('#vi-woo-compare-page-table').offset().top;
            if (($(window).scrollTop() < $(window).height() / 2.1) && wooCompare_buttonSlide) {
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').animate({
                    top: $('#vi-woo-compare-page-table').offset().top,
                }, 400);
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').animate({
                    top: $('#vi-woo-compare-page-table').offset().top,
                }, 400);
            } else {
                let _slideTop = $(window).height() / 2.1;
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').animate({
                    top: _slideTop,
                }, 400);
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').animate({
                    top: _slideTop,
                }, 400);
            }
            if ((scrollPosition - tableTop + scrollPart) < (wooCompare_bottomScroll - ($(window).height() * 0.5))) {
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').show();
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').show();
            } else {
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').hide();
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').hide();
            }

        });
        if ($(window).width() <= 600) wooCompare_adminBarHeight = 0;
        $.each(wooCompareVars.sticky_elements, function (i, val) {
            let marginTopFreeze = wooCompare_adminBarHeight;
            wooCompareFreezeTimer = setTimeout(function () {
                $(val[1]).each(function () {
                    let element = $(this);
                    let elementOffsetTop = $(this).offset().top - marginTopFreeze;
                    let elementHeight = $(this).outerHeight();
                    let elementMarginLeft = parseInt($(this).css('margin-left'));
                    let elementMarginRight = parseInt($(this).css('margin-right'));
                    let elementWidth = $(this).outerWidth();
                    $(window).on('scroll', function () {
                        let scrollPosition = $(window).scrollTop();
                        elementWidth = $(element).outerWidth();
                        elementHeight = $(element).outerHeight();
                        // wooCompareGetPageHeight();
                        // console.log('scroll: '+scrollPosition+' --offset: '+elementOffsetTop+' --bottom: '+wooCompare_bottomFreezeHeight + ' --table: '+wooCompare_bottomScroll);
                        if (scrollPosition > elementOffsetTop && ((scrollPosition - elementOffsetTop + wooCompare_bottomFreezeHeight ) < wooCompare_bottomScroll)) {
                            $('.woo-compare-table .woo-compare-table-setting').removeClass('open');
                            if (!$(element).next().hasClass('woo-compare-cloned-stick')) {
                                $(element).after($(element).clone().css({
                                    'opacity': 0,
                                    'margin-left': elementMarginLeft,
                                    'margin-right': elementMarginRight,
                                    // 'height': elementHeight,
                                }).addClass('woo-compare-cloned-stick'));
                                $(element).addClass('woo-compare-element-stuck');
                            } else {
                                // elementOffsetLeft = $(element).next().offsetLeft;
                                elementHeight = $(element).next().outerHeight();
                                elementWidth = $(element).next().outerWidth();
                            }
                            $(element).css({
                                'position': 'fixed',
                                'top': marginTopFreeze,
                                // 'left': elementOffsetLeft,
                                // 'height': elementHeight,
                                'width': elementWidth,
                                'margin-left': elementMarginLeft,
                                'margin-right': elementMarginRight
                            });
                        } else  {
                            wooCompare_slide_able = true;
                            $(element).removeAttr('style');
                            $(element).next('.woo-compare-cloned-stick').remove();
                            $(element).removeClass('woo-compare-element-stuck');
                        }
                    })
                });

            }, 100);
        });
    }

    window.onresize = function () {
        setTimeout(wooCompareDesignTable, 200);
        setTimeout(wooCompareResizeSlideInit, 300);
    };

    function wooCompareGetPageHeight(stage = 0) {
        if (wooCompareVars.element_allow == 0) {
            let _isStick = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze').length <= 1 ? 0 : 1;
            let tableBonusHeight = 0;
            $('#vi-woo-compare-page-table .woo-compare-table-field-header-freeze:not(.woo-compare-cloned-stick) .woo-compare-cell:not(.tr-hide)').each(function () {
                if (_isStick) {
                    let _class = $(this).attr('class');
                    _class = _class.replaceAll(" ", ".");
                    if (!$('#vi-woo-compare-page-table .woo-compare-table-field-header-freeze.woo-compare-cloned-stick .' + _class).length) {
                        tableBonusHeight += $(this).outerHeight();
                    }
                }
            });
            if (_isStick) {
                wooCompare_bottomFreezeHeight = $('.woo-compare-table-field-header-freeze.woo-compare-element-stuck').outerHeight();
            }
            wooCompare_bottomScroll = $('#vi-woo-compare-page-table').outerHeight() + tableBonusHeight;
        }
    }

    function wooCompareResizeSlideInit() {
        wooCompare_scrollLeft = ($('.woo-compare-table-content').width() * wooCompare_scrollRate);
        $('.woo-compare-table-column.woo-compare-table-column-free').css('left', wooCompare_scrollLeft * wooCompare_scrollLevel);
        if (wooCompareVars.element_allow == 0) {
            $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').css('left', $('#vi-woo-compare-page-table').offset().left -
                $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-prev-contain').outerWidth());
            $('#vi-woo-compare-page-table .woo-compare-table-inner .woo-compare-slide-next-contain').css('left', $('#vi-woo-compare-page-table').offset().left + $('#vi-woo-compare-page-table').outerWidth());
        }
    }

    //save setting table
    function wooComparePageSaveSettings() {
        wooCompareAttributesDisplay();
        let _stick = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze');
        $('.woo-compare-table-field-header-freeze.woo-compare-element-stuck .woo-compare-popup-settings-field .woo-compare-field-display').each(function () {
            let _val = $(this).val();
            if ($(this).prop('checked')) {
                if ($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).hasClass('compe-attrs')) {
                    if ($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).hasClass('compe-attr-hide')) {
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).addClass('tr-hide');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).addClass('tr-hide');
                    } else {
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).removeClass('tr-hide');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).removeClass('tr-hide');
                    }
                } else {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).removeClass('tr-hide');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).removeClass('tr-hide');
                }
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).addClass('tr-hide');
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).addClass('tr-hide');
                if (_stick > 1) {
                    $('.woo-compare-table .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze.woo-compare-cloned-stick .woo-compare-tr-' + _val).remove();
                    $('.woo-compare-table .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-table-row-freeze.woo-compare-cloned-stick .woo-compare-tr-' + _val).remove();
                }
            }
        });
        $('.woo-compare-table-field-header-freeze.woo-compare-element-stuck .woo-compare-popup-settings-field .woo-compare-settings-freeze').each(function () {
            let _val = $(this).val();
            if ($(this).prop('checked')) {
                if (_stick.length > 1) {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
                        $(this).children('.woo-compare-table-row-freeze.woo-compare-element-stuck').append($(this).children('.woo-compare-table-row-free').children('.woo-compare-tr-' + _val));
                    });
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze.woo-compare-element-stuck').append(
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free .woo-compare-tr-' + _val));

                } else {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
                        $(this).children('.woo-compare-table-row-freeze').append($(this).children('.woo-compare-table-row-free').children('.woo-compare-tr-' + _val));
                    });
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze').append(
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free .woo-compare-tr-' + _val));
                }
            } else {
                if (_stick.length > 1) {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
                        $(this).children('.woo-compare-table-row-free').prepend($(this).children('.woo-compare-table-row-freeze.woo-compare-element-stuck').children('.woo-compare-tr-' + _val));
                        $(this).children('.woo-compare-table-row-freeze.woo-compare-cloned-stick').children('.woo-compare-tr-' + _val).remove();
                    });
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free').prepend(
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze.woo-compare-element-stuck .woo-compare-tr-' + _val));
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze.woo-compare-cloned-stick .woo-compare-tr-' + _val).remove();
                } else {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
                        $(this).children('.woo-compare-table-row-free').prepend($(this).children('.woo-compare-table-row-freeze').children('.woo-compare-tr-' + _val));
                    });
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free').prepend(
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze .woo-compare-tr-' + _val));
                }
            }
        });
        wooCompareSaveCookieSetting(1);

        wooCompareAlternateColor();
    }

    //setting button
    function wooCompareSaveSettings() {
        wooCompareAttributesDisplay();

        $('#vi-woo-compare-table .woo-compare-table-setting').css('max-height', $('#vi-woo-compare-table .woo-compare-table-inner').height());

        $('.woo-compare-popup-settings-field .woo-compare-field-display').each(function () {
            let _val = $(this).val();
            if ($(this).prop('checked')) {
                if ($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).hasClass('compe-attrs')) {
                    if ($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).hasClass('compe-attr-hide')) {
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).addClass('tr-hide');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).addClass('tr-hide');
                    } else {
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).removeClass('tr-hide');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).removeClass('tr-hide');
                    }
                } else {
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).removeClass('tr-hide');
                    $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).removeClass('tr-hide');
                }
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-tr-' + _val).addClass('tr-hide');
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-' + _val).addClass('tr-hide');
            }
        });
        $('.woo-compare-popup-settings-field .woo-compare-settings-freeze').each(function () {
            let _val = $(this).val();
            if ($(this).prop('checked')) {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
                    $(this).children('.woo-compare-table-row-freeze').append($(this).children('.woo-compare-table-row-free').children('.woo-compare-tr-' + _val));
                });
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze').append($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free .woo-compare-tr-' + _val));
            } else {
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').each(function () {
                    $(this).children('.woo-compare-table-row-free').prepend($(this).children('.woo-compare-table-row-freeze').children('.woo-compare-tr-' + _val));
                });
                $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-free').prepend($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-freeze .woo-compare-tr-' + _val));
            }
        });
        wooCompareAlternateColor();

        wooCompareSaveCookieSetting();
        if (wooCompareVars.element_allow == 1) wooCompareTableOuterSize();
    }

    function wooCompareApplyCookieSetting() {
        let cookieName = 'wooCompare_settings_' + wooCompareVars.user_id;
        let loadCookie = wooCompareGetCookie(cookieName) != '' ? JSON.parse(wooCompareGetCookie(cookieName)) : '';
        for (let i = 0; i < loadCookie.length; i++) {
            let _field = $('.woo-compare-table .woo-compare-table-setting table tbody .woo-compare-popup-settings-field.woo-compare-settings-field-' + loadCookie[i][0]);
            if (_field.length != 0) {
                _field[0].querySelector('.woo-compare-settings-freeze').checked = loadCookie[i][1];
                _field[0].querySelector('.woo-compare-field-display').checked = loadCookie[i][2];
            }
        }
    }

    function wooCompareSaveCookieSetting(page = 0) {
        let cookieSetting = [];
        let stickyPage = page === 1 ? '.woo-compare-table-field-header-freeze:not(.woo-compare-cloned-stick)' : '';
        let cookieName = 'wooCompare_settings_' + wooCompareVars.user_id;
        $('.woo-compare-table ' + stickyPage + ' .woo-compare-table-setting table tbody .woo-compare-popup-settings-field').each(function () {
            let _field = [$(this).data('field'), $(this).find('.woo-compare-settings-freeze:checkbox:checked').length, $(this).find('.woo-compare-field-display:checkbox:checked').length];
            cookieSetting.push(_field);
        });
        cookieSetting = JSON.stringify(cookieSetting);
        wooCompareSetCookie(cookieName, cookieSetting, 7)
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

    // widget load
    function wooCompareLoadWidget(stage = 0, single_product = '') {
        $('.woo-compare-widget-products').addClass('woo-compare-loading');
        let _products = '';
        stage === 1 ? _products = single_product : _products = wooCompareGetProducts();
        $('.widget_wpc-widget .woo-compare-widget-remove-all').show();
        let data = {
            action: 'wpc_load_widget',
            products: _products,
            nonce: wooCompareVars.nonce,
        };
        $.post(wooCompareVars.ajaxurl, data, function (response) {
            if ((response == '') || (response == 0)) {
                $('.woo-compare-widget-products').html('No product in compare list');
                $('.woo-compare-widget-remove-all').addClass('woo-compare-hide');
                $('.woo-compare-widget-btn').addClass('woo-compare-hide');
            } else {
                $('.woo-compare-widget-remove-all').removeClass('woo-compare-hide');
                $('.woo-compare-widget-btn').removeClass('woo-compare-hide');
                stage === 1 ? $('.woo-compare-widget-products').append(response) : $('.woo-compare-widget-products').html(response);
                $('.woo-compare-btn[data-id="' + single_product + '"]').removeClass('loading');
            }

            $('.woo-compare-widget-products').removeClass('woo-compare-loading');
        });
    }

    function wooCompareLoadDataJson(stage = 0, single_product = '', loadBar = 1, loadPopup = 1) {
        let _products = '';
        stage === 1 ? _products = single_product : _products = wooCompareGetProducts();
        if (loadPopup == 1) {
            if (_products == '') {
                $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
                $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
            } else {
                $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").removeClass('woo-compare-disable');
                $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").removeClass('woo-compare-disable');
            }
            $('.woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header').height('');
            $('#vi-woo-compare-table').height('');
        }
        $('.woo-compare-table-inner').addClass('woo-compare-loading');
        if (loadBar == 1) {
            $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-items').addClass('woo-compare-loading');
        }
        $('.woo-compare-widget-products').addClass('woo-compare-loading');
        $('.widget_wpc-widget .woo-compare-widget-remove-all').show();
        let data = {
            action: 'wpc_load_data_json',
            products: _products,
            stage: stage,
            dataType: 'JSON',
            nonce: wooCompareVars.nonce,
        };
        $.post(wooCompareVars.ajaxurl, data, function (response) {
            let _val = response;
            //compare widget
            wooCompareWidgetData(stage, _val[0]);
            //compare bar
            if (loadBar == 1) {
                wooCompareSidebarData(stage, _val[1]);
            }
            //compare table
            wooComparePopupData(stage, _val[2], _products);
            if (stage == 1) {
                $('.woo-compare-btn[data-id="' + _products + '"]').removeClass('loading');
                wooCompareButtonLoading(_products);
            }
        });
    }

    function wooCompareWidgetData(stage = 0, value = '') {
        if ((value == '') || (value == 0)) {
            $('.woo-compare-widget-products').html('No product in compare list');
            $('.woo-compare-widget-remove-all').addClass('woo-compare-hide');
            $('.woo-compare-widget-btn').addClass('woo-compare-hide');
        } else {
            $('.woo-compare-widget-remove-all').removeClass('woo-compare-hide');
            $('.woo-compare-widget-btn').removeClass('woo-compare-hide');
            stage === 1 ? $('.woo-compare-widget-products').append(value) : $('.woo-compare-widget-products').html(value);
        }
        $('.woo-compare-widget-products').removeClass('woo-compare-loading');
    }

    function wooCompareSidebarData(stage = 0, value = '') {
        if ((wooCompareVars.hide_empty === 1) && ((value === '') || (value === 0))) {
            $('.woo-compare-bar-items').removeClass('woo-compare-bar-items-loaded');
            wooCompareCloseCompareBar();
        } else {
            stage === 0 ? $('.woo-compare-bar-items').html(value).addClass('woo-compare-bar-items-loaded') : $('.woo-compare-bar-items').append(value).addClass('woo-compare-bar-items-loaded');
            if (!$('.woo-compare-table').hasClass('woo-compare-table-open')) {
                wooCompareOpenCompareBar();
            } else {
                wooCompareCloseCompareBar();
            }
        }
        $('.woo-compare-area .woo-compare-inner .woo-compare-bar .woo-compare-bar-items').removeClass('woo-compare-loading');
    }

    function wooComparePopupData(stage = 0, value = '', product = '') {
        if (stage === 0 && value !== '' && value != 0) {
            $('.woo-compare-table-items').html(value).addClass('woo-compare-table-items-loaded');
        } else {
            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content').append(value);
        }
        let _content = $('.woo-compare-table-content .woo-compare-table-column-free');
        if (_content.length > 3) {
            _content[_content.length - 1].style.left = _content[0].style.left;
            $('.woo-compare-slide-next-contain').show(400);
            $('.woo-compare-slide-next').show(400);
        }
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-description .wp-video').css('width', '100%');

        $('.woo-compare-table-inner').removeClass('woo-compare-loading');
        if (wooCompareVars.conditional_tag && wooCompareVars.popup_compare) {
            wooComparePopupFreezeInit();
        } else {
            $('#vi-woo-compare-table').html('');
        }
        switch (stage) {
            case 0:
                wooCompareSearchSlime();
                wooCompareSlideInit(0);
                break;
            default:
                wooCompareSlideInit(2);
                let col_length = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
                if (col_length < wooCompare_slideNum) {
                    $('.woo-compare-table-search-button').addClass('woo-compare-show');
                } else {
                    $('.woo-compare-table-search-button').removeClass('woo-compare-show');
                }
                break;
        }
        wooCompareDesignTable();
        if (wooCompareVars.element_allow == 0) {
            wooComparePageRowFreeze();
        }
        wooCompareApplyCookieSetting();
        wooCompareAttributesDisplay();
        wooCompareSaveSettings();

        if (product != null && product != '') {
            if (stage === 0) {
                if ($('.woo-compare-table .woo-compare-table-content .woo-compare-table-column .variations_form').length > 0) {
                    $('.woo-compare-table .woo-compare-table-content .woo-compare-table-column .woo-compare-tr-title .variations_form').each(function () {
                        $(this).wc_variation_form();
                    });
                }
            } else {
                if ($('.woo-compare-table .woo-compare-table-content .woo-compare-table-item-' + product + ' .variations_form').length > 0) {
                    $('.woo-compare-table .woo-compare-table-content .woo-compare-table-item-' + product + ' .variations_form').wc_variation_form();
                }
            }
        }
        wooCompareAlternateColor();
        if (!$('.woo-compare-area .woo-compare-table').hasClass('woo-compare-table-open')) wooCompare_show_floating();
        if (wooCompareVars.element_allow == 0) wpc_enable_scroll();
    }

    function wooCompareCloseCompare() {
        $('#woo-compare-area').removeClass('woo-compare-area-open');
        $(document.body).trigger('wooCompare_close');
    }

    function wooCompare_delay_floating() {
        if (wooCompareVars.popup_compare == 1) {
            setTimeout(function () {
                wooCompare_show_floating();
            }, 1);
        }
    }

    function wooCompare_show_floating() {
        if ((wooCompareVars.hide_empty == 0) || (wooCompareVars.hide_empty == 1 && $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-content .woo-compare-table-column').length != 0)) {
            if (wooCompareVars.floating_icon_position == 'left') {
                $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-floating-icon-hide-left');
            } else {
                $('.woo-compare-floating-icon-wrap').removeClass('woo-compare-floating-icon-hide-right');
            }
        }
    }

    function wooCompare_hide_floating() {
        if (wooCompareVars.floating_icon_position == 'left') {
            $('.woo-compare-floating-icon-wrap').addClass('woo-compare-floating-icon-hide-left');
        } else {
            $('.woo-compare-floating-icon-wrap').addClass('woo-compare-floating-icon-hide-right');
        }
    }

    function wpc_disable_scroll() {
        if ($(document).height() > $(window).height()) {
            let scrollTop = ($('html').scrollTop()) ? $('html').scrollTop() : $('body').scrollTop();
            $('html').addClass('woo-compare-html-scroll').css('top', -scrollTop);
        }
    }

    function wpc_enable_scroll() {
        let scrollTop = parseInt($('html').css('top'));
        $('html').removeClass('woo-compare-html-scroll');
        $('html,body').scrollTop(-scrollTop);
    }

    function wooCompareDesignTable() {
        let product_col = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        let product_col_free = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        let width = parseFloat($('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').width());
        let wooCompareCookieProducts = 'wooCompare_products';
        let wooCompareCount = 0;

        if (wooCompareVars.user_id != '') {
            wooCompareCookieProducts = 'wooCompare_products_' + wooCompareVars.user_id;
        }
        if (wooCompareGetCookie(wooCompareCookieProducts) != '') {
            let wooCompareProducts = wooCompareGetCookie(wooCompareCookieProducts).split(',');
            wooCompareCount = wooCompareProducts.length;
        }
        switch (wooCompare_slideNum) {
            case '1':
            case '2':
                switch (wooCompareCount) {
                    case 1:
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('min-width', '60%');
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('min-width', '40%');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css({
                            'width': '90%',
                            'min-width': 'unset'
                        });
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css({
                            'width': '10%',
                            'min-width': 'unset'
                        });
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('min-width', '40%');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('min-width', '20%');
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('min-width', '70%');
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('min-width', '30%');
                        break;
                    case 0:
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('min-width', '30%');
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('min-width', '70%');
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
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('min-width', '100%');
                        // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                        $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
                        $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
                        break;
                    default:
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css({
                            'width': '90%',
                            'min-width': 'unset'
                        });
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css({
                            'width': '10%',
                            'min-width': 'unset'
                        });
                        // if (!stage) wooCompareSearchClose();
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('min-width', '40%');
                        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('min-width', '20%');
                        break;
                }
                break;
            default:
                if (wooCompareCount < parseInt(wooCompare_slideNum)) {
                    switch (wooCompareCount) {
                        case 2:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '60%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '40%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '40%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '20%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                            break;
                        case 1:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '40%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '60%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '70%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '30%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                            break;
                        case 0:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '20%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '80%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '100%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                            $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear").addClass('woo-compare-disable');
                            $(".woo-compare-table .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-setting").addClass('woo-compare-disable');
                            break;
                        default:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // if (!stage) wooCompareSearchClose();
                            break;
                    }
                } else {
                    switch (product_col.length) {
                        case 2:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '60%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '40%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '40%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '20%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                            break;
                        case 1:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '40%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '60%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '70%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '30%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                            break;
                        case 0:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '20%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '80%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '100%');
                            // $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-button').removeClass('woo-compare-hide');
                            break;
                        default:
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items').css('width', '95%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search').css('width', '5%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').css('width', '28%');
                            $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-field-header').css('width', '16%');
                            // if (!stage) wooCompareSearchClose();
                            break;
                    }
                }
                break;
        }

        wooCompareTableRowSize();
        wooComparePageFreezeInit();
        wooCompareTableOuterSize();
    }

    function wooCompareSearchSlime(stage = 0) {
        let col_length = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
        let _col = wooCompare_slideNum - $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column').length;
        let _col_rate = _col < 1 ? 1 : _col;
        let base_width = $('.woo-compare-table .woo-compare-table-search').width();
        let col_width = ($('.woo-compare-table-content').width() * wooCompare_scrollRate * _col_rate);
        let _width = col_width + base_width;
        // console.log(_col_rate + '--' + col_width + '--' + base_width + '--' + _width);
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
        if (col_length < wooCompare_slideNum) {
            $('.woo-compare-table-search-button').addClass('woo-compare-show');
        } else {
            $('.woo-compare-table-search-button').removeClass('woo-compare-show');
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

    function wooCompareTableOuterSize() {
        let _height = $('.woo-compare-table-inner .woo-compare-table-items .woo-compare-table-field-header').height();
        if (_height < 500) {
            $('#vi-woo-compare-table').height(500);
        } else if (_height < (parseInt($(window).height()) / 100 * 94)) {
            $('#vi-woo-compare-table').height(_height);
        } else {
            $('#vi-woo-compare-table').height('94%');
        }
    }

    function wooCompareTableRowSize() {
        wooCompareRowResize(['image', 'name', 'cart'], '.woo-compare-tr-title .woo-compare-tr-title-');

        wooCompareRowResize(wooCompareVars.fields_arr, '.woo-compare-tr-');
        wooCompareRowResize(['title'], '.woo-compare-tr-');
    }

    function wooCompareAttributesDisplay() {
        let attributes = wooCompareVars.attribute_list;
        for (let i = 0; i < attributes.length; i++) {
            let _check = false;
            $('.woo-compare-table-field-header .woo-compare-cell.woo-compare-tr-' + attributes[i]).each(function () {
                $(this).addClass('compe-attrs');
            });
            $('.woo-compare-table-column .woo-compare-cell.woo-compare-tr-' + attributes[i]).each(function () {
                $(this).addClass('compe-attrs');
                if ($(this).html() !== '' && $(this).html() !== '&nbsp;') _check = true;
            });
            if (!_check) {
                $('.woo-compare-table-setting .woo-compare-popup-table-setting-fields .woo-compare-settings-field-' + attributes[i]).hide();
                $('.woo-compare-table-column .woo-compare-cell.woo-compare-tr-' + attributes[i]).addClass('compe-attr-hide');
                $('.woo-compare-table-field-header .woo-compare-cell.woo-compare-tr-' + attributes[i]).addClass('compe-attr-hide');
            } else {
                $('.woo-compare-table-setting .woo-compare-popup-table-setting-fields .woo-compare-settings-field-' + attributes[i]).show();
                $('.woo-compare-table-column .woo-compare-cell.woo-compare-tr-' + attributes[i]).removeClass('compe-attr-hide');
                $('.woo-compare-table-field-header .woo-compare-cell.woo-compare-tr-' + attributes[i]).removeClass('compe-attr-hide');
            }
        }
    }

    function wooCompareRowResize(_array = [], _object = '') {
        if (_array.length == 1) {
            $(_object + _array[0]).css('height', '');
            let _arrSingle = $(_object + _array[0]);
            if (_arrSingle.length === 1) {
                // $('.woo-compare-table .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').addClass('woo-compare-hide');
            } else {
                // $('.woo-compare-table .woo-compare-table-items .woo-compare-table-field-header .woo-compare-table-field-header-button .woo-compare-table-button-clear').removeClass('woo-compare-hide');
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
                    if (parseInt($(this).outerHeight()) > parseInt(max_height)) max_height = $(this).outerHeight();
                });
                $(_object + _array[i]).css('height', max_height + 'px');
            }
        }
    }

    function wooCompareSlideInit(stage = 5, action = 0) {
        let product_col = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column');
        let product_col_free = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column-free');
        let product_col_freeze = $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-items .woo-compare-table-content .woo-compare-table-column-freeze');
        wooCompare_scrollLeft = ($('.woo-compare-table-content').width() - $('.woo-compare-table-content .woo-compare-table-field-header').width()) / wooCompare_slideNum;
        if (parseInt(product_col_freeze.length) >= parseInt(wooCompare_slideNum)) {
            if (stage == 2) product_col_free[product_col_free.length - 1].classList.add('woo-compare-hide-right');
            $('.woo-compare-area .woo-compare-slide-next-contain').hide(400);
            $('.woo-compare-area .woo-compare-slide-prev-contain').hide(400);
            $('.woo-compare-slide-prev').hide(300);
            $('.woo-compare-slide-next').hide(300);
            return;
        }
        if (parseInt(product_col.length) <= parseInt(wooCompare_slideNum)) {
            if (stage == 3) {
                wooCompare_scrollLevel = 0;
                product_col.each(function () {
                    $(this).removeClass('woo-compare-hide-right').removeClass('woo-compare-hide');
                });
                if (product_col.length == wooCompare_slideNum) {
                    product_col.each(function () {
                        $(this).css({'left': 0, 'opacity': 1});
                    });
                }
            }
            wooCompareSetPageRowFreeze(0, product_col, product_col_free, product_col_freeze);
            $('.woo-compare-area .woo-compare-slide-next-contain').hide(400);
            $('.woo-compare-area .woo-compare-slide-prev-contain').hide(400);
            $('.woo-compare-slide-prev').hide(300);
            $('.woo-compare-slide-next').hide(300);
        } else {
            let _left = $('.woo-compare-table-field-header').width();
            let _diff = 0;
            _left += product_col_freeze.length * wooCompare_scrollLeft;
            product_col_freeze.length > 0 ? _diff = wooCompare_scrollLeft : _diff = _left;
            switch (stage) {
                case 0:
                    // init
                    wooCompareSetPageRowFreeze(1, product_col, product_col_free, product_col_freeze);
                    $('.woo-compare-area .woo-compare-slide-next-contain').show(400);
                    $('.woo-compare-area .woo-compare-slide-prev-contain').hide(400);
                    $('.woo-compare-slide-prev').hide(300);
                    $('.woo-compare-slide-next').show(300);
                    break;
                case 1:
                    // slider
                    // action 0 prev, action 1 next
                    wooCompareSetPageRowFreeze(2, product_col, product_col_free, product_col_freeze, action);
                    if (action === 0) {
                        //prev button
                        // console.log(' offset: ' + product_col[0].offsetLeft + ' diff: ' + _diff + ' left:' + _left);
                        if ((product_col_free[0].offsetLeft + wooCompare_scrollLeft + _diff * 0.5) < _left) {
                            $('.woo-compare-area .woo-compare-slide-prev-contain').show(400);
                            $('.woo-compare-slide-prev').show(400);
                        } else {
                            $('.woo-compare-area .woo-compare-slide-prev-contain').hide(400);
                            $('.woo-compare-slide-prev').hide(400);
                        }
                        if ((product_col[product_col.length - 1].offsetLeft + wooCompare_scrollLeft * 1.5) < ($('.woo-compare-table-items').width())) {
                            $('.woo-compare-area .woo-compare-slide-next-contain').hide(400);
                            $('.woo-compare-slide-next').hide(400);
                        } else {
                            $('.woo-compare-area .woo-compare-slide-next-contain').show(400);
                            $('.woo-compare-slide-next').show(400);
                        }
                    } else {
                        //next button
                        if ((product_col_free[0].offsetLeft + _diff * 0.5) < _left + wooCompare_scrollLeft) {
                            $('.woo-compare-area .woo-compare-slide-prev-contain').show(400);
                            $('.woo-compare-slide-prev').show(400);
                        } else {
                            $('.woo-compare-area .woo-compare-slide-prev-contain').hide(400);
                            $('.woo-compare-slide-prev').hide(400);
                        }
                        if ((product_col[product_col.length - 1].offsetLeft + wooCompare_scrollLeft / 2) < ($('.woo-compare-table-items').width() + wooCompare_scrollLeft)) {
                            $('.woo-compare-area .woo-compare-slide-next-contain').hide(400);
                            $('.woo-compare-slide-next').hide(400);
                        } else {
                            $('.woo-compare-area .woo-compare-slide-next-contain').show(400);
                            $('.woo-compare-slide-next').show(400);
                        }
                    }
                    break;
                case 3:
                    //remove
                    if (product_col[product_col.length - 1].offsetLeft + _diff * 0.5 < ($('.woo-compare-table-items').width() - wooCompare_scrollLeft)) {
                        wooCompare_scrollLevel += 1;
                        product_col_free.each(function () {
                            let _move = parseFloat($(this).css('left')) + parseFloat(wooCompare_scrollLeft);
                            $(this).css({'left': _move, 'opacity': 1});
                        });
                    }
                case 2:
                //add
                case 4:
                    //freeze
                    //show button next-previous
                    if ((product_col_free[0].offsetLeft + _diff * 0.5) < _left) {
                        $('.woo-compare-slide-prev-contain').show(400);
                        $('.woo-compare-slide-prev').show(400);
                    } else {
                        $('.woo-compare-slide-prev-contain').hide(400);
                        $('.woo-compare-slide-prev').hide(400);
                    }
                    if ((product_col[product_col.length - 1].offsetLeft + wooCompare_scrollLeft * 0.5) < ($('.woo-compare-table-items').width())) {
                        $('.woo-compare-area .woo-compare-slide-next-contain').hide(400);
                        $('.woo-compare-slide-next').hide(400);
                    } else {
                        $('.woo-compare-area .woo-compare-slide-next-contain').show(400);
                        $('.woo-compare-slide-next').show(400);
                    }

                    // set hide for slide
                    product_col_free[product_col_free.length - 1].classList.add('woo-compare-hide-right');
                    if (product_col_freeze.length > 0) {
                        product_col_freeze.each(function () {
                            $(this).removeClass('woo-compare-hide').removeClass('woo-compare-hide-right')
                        });
                    }
                    for (let i = 0; i < product_col_free.length; i++) {
                        if ((product_col_free[i].offsetLeft + (_diff * 0.5)) < _left) {
                            product_col_free[i].classList.add('woo-compare-hide');
                        } else {
                            product_col_free[i].classList.remove('woo-compare-hide');
                        }
                        if ((product_col_free[i].offsetLeft + wooCompare_scrollLeft / 2) > $('.woo-compare-table-items').width()) {
                            product_col_free[i].classList.add('woo-compare-hide-right');
                        } else {
                            product_col_free[i].classList.remove('woo-compare-hide-right');
                        }
                    }
                    wooCompareSetPageRowFreeze(2, product_col, product_col_free, product_col_freeze);
                    break;
                default:
                    // load...
                    wooCompareSetPageRowFreeze(1, product_col, product_col_free, product_col_freeze);
                    if ((product_col_free[product_col_free.length - 1].offsetLeft + wooCompare_scrollLeft / 2) < ($('.woo-compare-table-items').width())) {
                        $('.woo-compare-area .woo-compare-slide-next-contain').hide(400);
                        $('.woo-compare-slide-next').hide(300);
                    } else {
                        $('.woo-compare-area .woo-compare-slide-next-contain').show(400);
                        $('.woo-compare-slide-next').show(300);
                    }
                    if ((product_col[0].offsetLeft + _diff * 0.5) > _left) {
                        $('.woo-compare-slide-prev-contain').hide(300);
                        $('.woo-compare-slide-prev').hide(300);
                    } else {
                        $('.woo-compare-slide-prev-contain').show(300);
                        $('.woo-compare-slide-prev').show(300);
                    }
                    break;
            }
        }
    }

    function wooCompareSetPageRowFreeze(stage = 0, col = '', col_free = '', col_freeze = '', action = 0) {
        switch (stage) {
            case 0:
                //number <= total
                for (let i = 0; i < wooCompare_slideNum; i++) {
                    if (col[i] != null) {
                        col[i].classList.remove('col-hide');
                        if (col[i].children[0] != null) {
                            col[i].children[0].classList.add('woo-compare-row-available');
                            col[i].classList.add('woo-compare-col-available');
                        }
                    }
                }
                break;
            case 1:
                //number >= total and init stage
                for (let i = 0; i < col.length; i++) {
                    if (i < wooCompare_slideNum) {
                        if (col[i] != null) {
                            col[i].classList.remove('col-hide');
                            if (col[i].children[0] != null) {
                                col[i].children[0].classList.add('woo-compare-row-available');
                                col[i].classList.add('woo-compare-col-available');
                            }
                        }
                    } else {
                        col[i].classList.add('woo-compare-hide-right');
                    }
                }
                break;
            case 2:
                //slider
                let _hide = [], _hide_right = [], _avalable = [];
                for (let i = 0; i < col.length; i++) {
                    if (col[i].classList.contains('woo-compare-hide')) {
                        _hide.push(col[i]);
                        continue;
                    }
                    if (col[i].classList.contains('woo-compare-hide-right')) {
                        _hide_right.push(col[i]);
                        continue;
                    }
                    _avalable.push(col[i]);
                }
                if (action === 0) {
                    _hide.forEach(function (element) {
                        if (element.children[0] != null) {
                            element.children[0].classList.remove('woo-compare-row-available');
                            element.classList.remove('woo-compare-col-available');
                        }
                    });
                    for (let j = 0; j < _avalable.length; j++) {
                        if (j === 0) {
                            setTimeout(function () {
                                if (_avalable[j].children[0] != null) {
                                    _avalable[j].children[0].classList.add('woo-compare-row-available');
                                    _avalable[j].classList.add('woo-compare-col-available');
                                }
                            }, 200);
                        } else {
                            if (_avalable[j].children[0] != null) {
                                _avalable[j].children[0].classList.add('woo-compare-row-available');
                                _avalable[j].classList.add('woo-compare-col-available');
                            }
                        }
                    }
                    for (let k = 0; k < _hide_right.length; k++) {
                        if (k === 0) {
                            setTimeout(function () {
                                if (_hide_right[k].children[0] != null) {
                                    _hide_right[k].children[0].classList.remove('woo-compare-row-available');
                                    _hide_right[k].classList.remove('woo-compare-col-available');
                                }
                            }, 100);
                        } else {
                            if (_hide_right[k].children[0] != null) {
                                _hide_right[k].children[0].classList.remove('woo-compare-row-available');
                                _hide_right[k].classList.remove('woo-compare-col-available');
                            }
                        }
                    }
                } else {
                    _hide_right.forEach(function (element) {
                        if (element.children[0] != null) {
                            element.children[0].classList.remove('woo-compare-row-available');
                            element.classList.remove('woo-compare-col-available');
                        }
                    });
                    for (let j = 0; j < _avalable.length; j++) {
                        if (j == (_avalable.length - 1)) {
                            setTimeout(function () {
                                if (_avalable[j].children[0] != null) {
                                    _avalable[j].children[0].classList.add('woo-compare-row-available');
                                    _avalable[j].classList.add('woo-compare-col-available');
                                }
                            }, 200);
                        } else {
                            if (_avalable[j].children[0] != null) {
                                _avalable[j].children[0].classList.add('woo-compare-row-available');
                                _avalable[j].classList.add('woo-compare-col-available');
                            }
                        }
                    }
                    for (let k = 0; k < _hide.length; k++) {
                        if (k == (_hide.length - 1)) {
                            setTimeout(function () {
                                if (_hide[k].children[0] != null) {
                                    _hide[k].children[0].classList.remove('woo-compare-row-available');
                                    _hide[k].classList.remove('woo-compare-col-available');
                                }
                            }, 100);
                        } else {
                            if (_hide[k].children[0] != null) {
                                _hide[k].children[0].classList.remove('woo-compare-row-available');
                                _hide[k].classList.remove('woo-compare-col-available');
                            }
                        }
                    }
                }
                break;
            default:
                break;
        }
    }

    function wooCompareIconInvert() {
        if (wooCompareVars.button_achieve_pos == 0) {
            $('.woo-compare-btn.woo-compare-btn-inside').each(function () {
                let cp_color_added = wooCompareVars.button_achieve_added != '' ? wooCompareVars.button_achieve_added : 'rgb(242,202,104)';
                let cp_color = wooCompareVars.button_achieve_color != '' ? wooCompareVars.button_achieve_color : 'rgb(255,255,255)';
                let src_color = 'rgb(255,255,255)';
                if ($(this).hasClass('woo-compare-added')) {
                    src_color = wooCompareVars.button_achieve_added != '' ? wooCompareVars.button_achieve_added : wooCompare_rgb2hex(cp_color_added);
                } else {
                    src_color = wooCompareVars.button_achieve_color != '' ? wooCompareVars.button_achieve_color : wooCompare_rgb2hex(cp_color);
                }
                // let src_color = wooCompare_rgb2hex($(this).css("color"));
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
            // http://stackoverflow.com/a/3943023/112731
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

    function wooCompare_CloseSearch() {
        $('.woo-compare-table .woo-compare-table-search .woo-compare-table-search-button').addClass('woo-compare-hide');
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-inner').css({
            'position': 'relative',
            'width': '100%',
            'left': 'unset'
        }).removeClass('woo-compare-open');
        $('.woo-compare-table .woo-compare-table-inner .woo-compare-table-search .woo-compare-table-search-result').hide();
    }

});