import dffNextBackButtonsActive from '../scripts/next-back-buttons-active';
import dffAjaxLessons from '../scripts/ajax-lessons';
import uploadExam from '../scripts/upload-exam';
import dffAccordion from '../scripts/accordion';
/** 
   * The functionality for the 
   * back button on the lesson. 
   */
const $ = jQuery.noConflict();

$(document).on('click', '.course-quiz__buttons .continue-course-module', function (e) {
    console.log('continue-course-module');
    e.preventDefault();
    const moduleIndex = $(this).attr('module-index');
    const lessonIndex = $(this).attr('lesson-index');
    const countLessonRow = $(".modules-course").find(".accordion").attr('count-lesson-row');
    console.log(countLessonRow);
    const lessonTestId = $(".course-sidebar").find(".accordion-item.module_" + moduleIndex + " .module-lesson-test").attr('lesson-test-id');
    const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
    const indexPrev = moduleIndex - 1;
    const headPrev = $('.accordion .accordion-item.module_' + indexPrev + ' .accordion-head');
    const headNext = $('.accordion .accordion-item.module_' + moduleIndex + ' .accordion-head');
    if (headPrev.hasClass('active')) {
        headPrev.siblings('.accordion-content').slideUp();
        headPrev.removeClass('active');
        headNext.siblings('.accordion-content').slideToggle();
        headNext.toggleClass('active');
    }
    // else{
    //     headPrev.siblings('.accordion-content').slideUp();
    //     headPrev.removeClass('active');
    //     headNext.siblings('.accordion-content').slideToggle();
    //     headNext.toggleClass('active'); 
    // }
 
    if (moduleIndex - 1 === countLessonRow - 1) {
        const examPostId = $(".modules-course .my-single-modules").find(".exam-tab-item").attr('exam-post-id');
        console.log('exam');
        console.log(examPostId);
        uploadExam(examPostId, 'exam');
    } else {
        dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId, countLessonRow);
    }
    // dffTryAgaineButton(moduleIndex, lessonIndex);
    dffAccordion();
    dffNextBackButtonsActive(moduleIndex, lessonIndex);
});

