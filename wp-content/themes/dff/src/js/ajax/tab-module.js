import dffAccordion from '../scripts/accordion';
import dffSliderToLesson from '../scripts/slider-lesson';
import dffGalleryFancybox from '../scripts/gallery-fancybox';
/**
    * Ajax switches between tabs on the course page
    * in the my account
    */
const $ = jQuery.noConflict();
$(document).on('click', '.my-courses-tabs .tabs-nav-my-courses .tab-module', function (e) {
    e.preventDefault();

    $('.tabs-nav li').removeClass('active');
    $(this).parent().addClass('active');
    const courseId = $("#content").find(".my-courses-tabs").attr('course-id');
    const tabId = $(this).attr('tab-id');
    const data = {
        action: "tabs_lesson_ajax",
        main_tab_id: tabId,
        course_id: courseId,
    };
    // console.log(data);
    $.ajax({
        type: "POST",
        url: courses_ajax.url,
        data: data,
    }).done(function (response) {
        $(".my-courses-tabs-content .tab-wrapper ").html(response);
        $('.accordion .accordion-item:nth-child(1) .accordion-head').addClass('active');
        $('.accordion .accordion-item:nth-child(1) .accordion-content').slideDown();
        dffAccordion();
        dffSliderToLesson();
        dffGalleryFancybox();
        window.wp.mediaelement.initialize();
    }).fail(function (response) {
        console.log(response);
    });
});
