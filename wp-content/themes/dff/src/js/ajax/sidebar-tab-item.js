// import dffAccordion from '../scripts/accordion';
import dffSliderToLesson from '../scripts/slider-lesson';
import dffGalleryFancybox from '../scripts/gallery-fancybox';
/**
     * Ajax switches between modules and 
     * lessons on the course page in the my account 
     */
 const $ = jQuery.noConflict();
 $(document).on('click', '.course-sidebar .accordion-content .tab-item', function (e) {
    e.preventDefault();
    const tabItem = $('.accordion-content ul li');
    const moduleIndex = $(this).attr('module-index');
    const lessonIndex = $(this).attr('lesson-index');
    const lessonTestId = $(this).attr('lesson-test-id');
    const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
    tabItem.removeClass('active');
    $(this).addClass('active');    
    const data = {
        action: "upload_lesson_ajax",
        module_index: moduleIndex,
        lesson_index: lessonIndex,
        course_id: courseId,
        lesson_test_id: lessonTestId,
    };
    // console.log(data); 
    $.ajax({
        type: "POST",
        url: courses_ajax.url,
        data: data,
        beforeSend: function () {
            // $('#loader').show().parent().parent().addClass('loader-wrap');
            $('#loader').show();
        },
    }).done(function (response) {
        $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');
        dffSliderToLesson();
        dffGalleryFancybox();
        // dffAccordion();
        $(window.wp.mediaelement.initialize);
        $('#loader').hide();
        // console.log(response);
    }).fail(function (response) {
        console.log(response);
    });
});