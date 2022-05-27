<?php
// Deletes a course from a user profile.
function leave_course_ajax_callback()
{

    $course_id       = $_POST['course_id'];
    dff_delete_course_from_user($course_id);
    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_leave_course_ajax', 'leave_course_ajax_callback');
add_action('wp_ajax_nopriv_leave_course_ajax', 'leave_course_ajax_callback');
