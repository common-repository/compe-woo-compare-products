jQuery(document).ready(function ($) {
    'use strict';

    $('.vi-ui.dropdown').dropdown();

    jQuery('.wpc-radio-icons-label').on('click', function () {
        jQuery('.wpc-radio-icons-label').removeClass('wpc-radio-icons-active');
        jQuery(this).addClass('wpc-radio-icons-active');
    });

    handleEnable();

    function handleEnable() {
        $("#wpc_popup_compare").on("click", function () {
            $(".wpc_popup_compare_att").toggle(this.checked);
        });
        if ($('#wpc_popup_compare').is(":checked"))
            $(".wpc_popup_compare_att").show();
        else
            $(".wpc_popup_compare_att").hide();

        $("#wpc_remove_mode").on("click", function () {
            $(".wpc_remove_text").toggle(this.checked);
        });
        if ($('#wpc_remove_mode').is(":checked"))
            $(".wpc_remove_text").show();
        else
            $(".wpc_remove_text").hide();

        $("#wpc_open_sidebar").on("click", function () {
            $(".wpc_side_bar_hide_empty").toggle(this.checked);
        });
        if ($('#wpc_open_sidebar').is(":checked"))
            $(".wpc_side_bar_hide_empty").show();
        else
            $(".wpc_side_bar_hide_empty").hide();

        $("#wpc_open_floating_icon").on("click", function () {
            $(".wpc_floating_icon_open").toggle(this.checked);
        });
        if ($('#wpc_open_floating_icon').is(":checked"))
            $(".wpc_floating_icon_open").show();
        else
            $(".wpc_floating_icon_open").hide();
    }

    $(".wpc-page-search").select2({
        closeOnSelect: true,
        placeholder: "Please fill in your product title",
        ajax: {
            url: "admin-ajax.php?action=wpc_search_page",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            delay: 250,
            data: function (params) {
                return {
                    keyword: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        minimumInputLength: 1
    });
});