import dffAccordion from '../scripts/accordion';
import uploadExam from '../scripts/upload-exam';
import dffNextBackButtonsActive from '../scripts/next-back-buttons-active';
import dffTryAgainActiveMainTab from '../scripts/try-again-active-main-tab';
import dffTryAgainActive from '../scripts/try-again-active';
/**
   * The click functionality the button "try again"
   * to pass the Exam for the course.
   */
//   const $ = jQuery.noConflict();
jQuery(document).on('click', '.exam-footer .test-try-again-exam', function (e) {
    e.preventDefault();
    const moduleIndex = $(this).attr('module-index');
    const examPostId = $(this).attr('exam-post-id');
    const moduleType = $(this).attr('module-type');
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
        dffTryAgainActiveMainTab(tabId);
        dffTryAgainActive(moduleIndex);
        dffNextBackButtonsActive(moduleIndex);
        uploadExam(examPostId, moduleType);
    }).fail(function (response) {
        console.log(response);
    });
});
/**
   * Ajax upload Exam url 
   */
 jQuery(document).on('click', '.accordion-head.exam-tab-item', function (e) {
    e.preventDefault();
    const examPostId = $(this).attr('exam-post-id');
    const moduleType = $(this).attr('module-type');
    uploadExam(examPostId, moduleType);
});