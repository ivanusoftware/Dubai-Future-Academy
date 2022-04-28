import dffNextBackButtonsActive from '../scripts/next-back-buttons-active';
import dffAjaxLessons from '../scripts/ajax-lessons';
/** 
   * The functionality for the 
   * back button on the lesson. 
   */
jQuery(document).on('click', '.lesson-header .back', '', function (e) {
    e.preventDefault();
    const moduleIndex = $(this).attr('module-index');
    const lessonIndex = $(this).attr('lesson-index');
    const lessonTestId = $(".course-sidebar").find(".accordion-item.module_" + moduleIndex + " .module-lesson-test").attr('lesson-test-id');
    const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
    $(".course-sidebar").find(".accordion-head.active").parent().addClass('active-btn');
    dffNextBackButtonsActive(moduleIndex, lessonIndex);
    dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId);
});

/** 
* The functionality for the 
* next button on the lesson. 
*/
jQuery(document).on('click', '.lesson-header .next', function (e) {
    e.preventDefault();
    const moduleIndex = $(this).attr('module-index');
    const lessonIndex = $(this).attr('lesson-index');
    const countLessonRow = $(".modules-course").find(".accordion-head.active").attr('count-lesson-row');
    const lessonTestId = $(".course-sidebar").find(".accordion-item.module_" + moduleIndex + " .module-lesson-test").attr('lesson-test-id');
    const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
    $(".course-sidebar").find(".accordion-head.active").parent().addClass('active-btn');
    dffNextBackButtonsActive(moduleIndex, lessonIndex);
    dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId, countLessonRow)
});