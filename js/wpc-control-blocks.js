"use strict";
jQuery(document).ready(function ($) {
    let move = false;
    wooCompareSortable();
    ShowAvailableItems();
    AddItemToBlock();

    function wooCompareSortable() {
        $('.woo-compare-block-container__block').sortable({
            items: '.woo-compare-block-item',
            placeholder: 'woo-compare-block-place-holder',
            cursor: 'move',
            connectWith: '.woo-compare-block-container__block',
            start: function (event, ui) {
            },
            over: function (event, ui) {
            },
            receive: function (event, ui) {
                move = true;
            },
            stop: function (event, ui) {
                wooCompareApplyChange();
            }
        }).disableSelection();
    }

    $('body').on('click', '.woo-compare-block-item .woo-compare-block-edit', function (event) {
        event.stopPropagation();
        $(this).parent().find('.woo-compare-block-text').css({'opacity': 1, 'visibility': 'visible'}).focus();
    });

    $('body').on('change blur focusout', '.woo-compare-block-item .woo-compare-block-text', function (event) {
        $(this).css({'opacity': 0, 'visibility': 'hidden'});
        if ($(this).val() !== '') {
            $(this).parent().find('.woo-compare-block-header').html($(this).val());
        } else {
            $(this).parent().find('.woo-compare-block-header').html('Placeholder: ' + $(this).parent().data()['block_item']);
        }
        wooCompareApplyChange();
    });

    $('body').on('click', '.woo-compare-block-container__block .woo-compare-block-remove', function (event) {
        event.stopPropagation();
        $(this).parent().appendTo('.woo-compare-block-components__block');
        wooCompareApplyChange();
    });

    function ShowAvailableItems() {
        $('body').on('click', '.woo-compare-block-edit-block-add-item', function () {
            $('.woo-compare-block-edit-block-add-item').removeClass('woo-compare-block-edit-block-add-item-active');
            $(this).addClass('woo-compare-block-edit-block-add-item-active');
            $(this).parent().parent().addClass('woo-compare-block-received-block');

            $('.woo-compare-block-components-container').fadeIn(300);
        });
        $('body').on('click', '.woo-compare-block-inline-block-add-item', function () {
            $('.woo-compare-block-inline-block-add-item').removeClass('woo-compare-block-inline-block-add-item-active');
            $(this).addClass('woo-compare-block-inline-block-add-item-active');
            $('.woo-compare-block-components').addClass('woo-compare-block-components-inline');

            $('.woo-compare-block-components-container').fadeIn(300);
        })
    }

    function HideAvailableItems() {
        $('.woo-compare-block-edit-block-add-item').removeClass('woo-compare-block-edit-block-add-item-active');
        $('.woo-compare-block-components-container').fadeOut(300);
        $('.woo-compare-block-container__block').removeClass('woo-compare-block-received-block');
    }

    function wooCompareApplyChange() {

        let row = [];
        let blocks = $('.woo-compare-block-data_blocks .woo-compare-block-item');
        for (let i = 0; i < blocks.length; i++) {
            let block = [];
            block.push(blocks.eq(i).data()['block_item']);
            let res_val = blocks.eq(i).find('.woo-compare-block-text');
            block.push(res_val.val());
            if (blocks.eq(i).parent().hasClass('woo-compare-block-container__block')) block.push(1); else block.push(0);
            row.push(block);
        }
        move = false;
        wp.customize('woo_product_compare_params[wpc_blocks]').set(JSON.stringify(row));
    }

    function AddItemToBlock() {
        $('.woo-compare-block-components').on('click', '.woo-compare-block-item', function () {
            if ($('.woo-compare-block-components').hasClass('woo-compare-block-components-inline')) {
                let active_button = $('.woo-compare-block-inline-block-add-item-active'),
                    active_block = active_button.parent().parent(),
                    blocks = $('.woo-compare-block-container__block');
                $(this).insertBefore(active_button.parent());
                $('.woo-compare-block-components').removeClass('woo-compare-block-components-inline');
            } else {
                let active_button = $('.woo-compare-block-edit-block-add-item-active'),
                    active_block = active_button.parent().parent(),
                    blocks = $('.woo-compare-block-container__block');
                $('.woo-compare-block-item').removeClass('woo-compare-block-latest-item');
                let current_block = blocks.index(active_block);
                let position = 0;
                $(this).insertBefore(active_button.parent());
            }
            wooCompareApplyChange();
            HideAvailableItems();
        });

    }

    $('.woo-compare-block-components-close').on('click', function () {
        $('.woo-compare-block-components').removeClass('woo-compare-block-components-inline');
        HideAvailableItems();
    });

    $('.woo-compare-block-components-overlay').on('click', function () {
        $('.woo-compare-block-components').removeClass('woo-compare-block-components-inline');
        HideAvailableItems();
    })
});