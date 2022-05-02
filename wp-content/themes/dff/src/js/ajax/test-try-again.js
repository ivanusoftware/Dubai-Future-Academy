import dffAccordion from '../scripts/accordion';
import dffAjaxLessons from '../scripts/ajax-lessons';
import dffNextBackButtonsActive from '../scripts/next-back-buttons-active';
import dffTryAgainActiveMainTab from '../scripts/try-again-active-main-tab';
import dffTryAgainActive from '../scripts/try-again-active';
/**
 * The click functionality the button "try again"
 * to pass the test for the module.
 */
const $ = jQuery.noConflict();
jQuery(document).on('click', '.exam-footer .test-try-again', function (e) {
    e.preventDefault();
    const moduleIndex = $(this).attr('module-index');
    const lessonIndex = $(this).attr('lesson-index');
    const lessonTestId = $(this).attr('lesson-test-id');
    const courseId = $("#content").find(".my-courses-tabs").attr('course-id');
    const tabId = $(this).attr('tab-id');
    const data = {
        action: "tabs_lesson_ajax",
        main_tab_id: tabId,
        course_id: courseId,
    };
    $.ajax({
        type: "POST",
        url: courses_ajax.url,
        data: data,
    }).done(function (response) {
        $(".my-courses-tabs-content .tab-wrapper ").html(response);
        dffAccordion();
        dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId);
        dffNextBackButtonsActive(moduleIndex, lessonIndex);
        dffTryAgainActiveMainTab(tabId);
        dffTryAgainActive(moduleIndex);
    }).fail(function (response) {
        console.log(response);
    });
});