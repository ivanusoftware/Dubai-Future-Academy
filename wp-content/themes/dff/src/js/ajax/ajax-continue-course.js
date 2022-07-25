// import dffNextBackButtonsActive from '../scripts/next-back-buttons-active';
import dffAjaxLessons from '../scripts/ajax-lessons';
import uploadExam from '../scripts/upload-exam';
import dffAccordion from '../scripts/accordion';
import dffTryAgainActive from '../scripts/try-again-active';
import dffTryAgaineButton from '../scripts/try-again-button';
import dffTryAgainActiveMainTab from '../scripts/try-again-active-main-tab';
/** 
   * The functionality for the 
   * back button on the lesson. 
   */
const $ = jQuery.noConflict();

$(document).on('click', '.course-quiz__buttons .continue-course-module', function (e) {
    e.preventDefault();
    const moduleIndex = $(this).attr('module-index');
    const lessonIndex = $(this).attr('lesson-index');
    const tabId = $(this).attr('tab-id');
    const countLessonRow = $(".modules-course").find(".accordion").attr('count-lesson-row');
    const lessonTestId = $(".course-sidebar").find(".accordion-item.module_" + moduleIndex + " .module-lesson-test").attr('lesson-test-id');
    const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
    const indexPrev = moduleIndex - 1;
    const headPrev = $('.accordion .accordion-item.module_' + indexPrev + ' .accordion-head');
    const headNext = $('.accordion .accordion-item.module_' + moduleIndex + ' .accordion-head');
    if (headPrev.hasClass('active')) {
        headPrev.siblings('.accordion-content').slideUp();
        headPrev.removeClass('active');
        headNext.parent().removeClass('close-module');
        headNext.parent().addClass('open-module');
        headNext.next().slideToggle();
        headNext.toggleClass('active');
    }
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
        console.log('click');
        $(".my-courses-tabs-content .tab-wrapper ").html(response);
        $(".module_" + moduleIndex + " .accordion-content ul li.tab-item[lesson-index='1']").addClass('active');
        dffAccordion();
        if (moduleIndex - 1 === countLessonRow - 1) {
            $(".modules-course .my-single-modules .exam-tab-item").addClass('active');
            const examPostId = $(".modules-course .my-single-modules").find(".exam-tab-item").attr('exam-post-id');
            uploadExam(examPostId, 'exam');
        } else {
            // dffAccordion();
            dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId);
            dffTryAgaineButton(moduleIndex, lessonIndex);
            dffTryAgainActiveMainTab(tabId);
            dffTryAgainActive(moduleIndex);
        }

    }).fail(function (response) {
        console.log(response);
    });
});

