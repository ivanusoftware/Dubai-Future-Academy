// Uploads an exam to an exam post.
export default function uploadExam(examPostId, moduleType) {
    const $ = jQuery.noConflict();
    const courseId = $(".modules-course").find(".course-sidebar").attr('course-id');
    const data = {
        action: "upload_exam_ajax",
        exam_post_id: examPostId,
        course_id: courseId,
        module_type: moduleType,
    };
    $.ajax({
        type: "POST",
        url: courses_ajax.url,
        data: data,
        beforeSend: function () {
            $('#loader').show();
        },
    }).done(function (response) {
        $(".main-content .content .lesson-container").html('<div class="lesson-wrapper">' + response + '</div>');
        $('#loader').hide();
    }).fail(function (response) {
        console.log(response);
    });
}