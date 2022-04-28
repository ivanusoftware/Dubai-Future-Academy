import dffSliderToLesson from '../scripts/slider-lesson';
import dffGalleryFancybox from '../scripts/gallery-fancybox';
 /**
     * Upload a lesson ajax
     * @param {*} moduleIndex 
     * @param {*} lessonIndex 
     * @param {*} lessonTestId 
     * @param {*} courseId 
     * @param {*} countLessonRow 
     */
  export default function dffAjaxLessons(moduleIndex, lessonIndex, lessonTestId, courseId, countLessonRow) {
    // if (lessonIndex == undefined) lessonIndex = false;
    // if (lessonTestId == undefined) lessonTestId = false;
    const data = {
        action: "upload_lesson_ajax",
        module_index: moduleIndex,
        lesson_index: lessonIndex,
        course_id: courseId,
        lesson_test_id: lessonTestId,
        count_lesson_row: countLessonRow,
    };
    $.ajax({
        type: "POST",
        url: courses_ajax.url,
        data: data,
        beforeSend: function () {
            $('#loader').show();
        }
    }).done(function (response) {
        $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');
        $('#loader').hide();
        dffSliderToLesson();
        dffGalleryFancybox();
        $(window.wp.mediaelement.initialize);
    }).fail(function (response) {
        console.log(response);
    });
}