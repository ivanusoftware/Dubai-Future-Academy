(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).on('click', '.dff-view-more', function (e) {
        e.preventDefault();
        const lang = $('#dff_language').val();
        const target = $(this).attr('target');
        let link = $(this).attr('href');
        const connnector = link.indexOf('?') === -1 ? '?' : '&';
        link = $(this).attr('href') + connnector + 'lang=' + lang;
        window.open(
            link,
            target
        );
    });

    $(document).on('click', '.de-pagination-ul li:not(.disabled):not(.active)', function () {
        const paged = $(this).attr('data-page');
        const checkedCats = $('#checked_cats').val();
        const checkedTags = $('#checked_tags').val();
        const totalEvents = $('#total_events').val();
        const orderBy = $('#order_by').val();
        const eLayout = $('#e_layout').val();
        const featureImageToggle = $('#feature_img_tog').val();
        const paginationToggle = $('#pagination_tog').val();
        const dateTimeToggle = $('#date_time_tog').val();
        const openUpcomingToggle = $('#upcoming_tog').val();
        const titleColor = $('#title_color').val();
        const textColor = $('#text_color').val();
        const upcomingEvent = $('#upcoming_event').val();
        const data = {
            'paged': paged,
            'checkedCats': checkedCats,
            'checkedTags': checkedTags,
            'totalEvents': totalEvents,
            'orderBy': orderBy,
            'eLayout': eLayout,
            'featureImageToggle': featureImageToggle,
            'paginationToggle': paginationToggle,
            'dateTimeToggle': dateTimeToggle,
            'openUpcomingToggle': openUpcomingToggle,
            'titleColor': titleColor,
            'textColor': textColor,
            'upcomingEvent': upcomingEvent
        };

        jQuery.ajax({
            url: ajax_object.ajaxurl,
            type: 'POST',
            data: {
                action: 'events_render_callback',
                data: data,
            },
            success: function (data) {
                var eventsWrapper = document.getElementById("dff-events-wrapper");
                eventsWrapper.innerHTML = data;
            },
            beforeSend: function () {
                $('#dff-events-wrapper').addClass('loading');
            },
            complete: function () {
                $('#dff-events-wrapper').removeClass('loading');
            },

        });
    });

})(jQuery);
