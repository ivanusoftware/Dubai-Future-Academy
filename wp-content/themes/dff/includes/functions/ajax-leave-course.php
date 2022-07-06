<?php
// Deletes a course from a user profile.
function leave_course_ajax_callback()
{
    if ($_COOKIE['user'] && $_COOKIE['fid-is-loggedin']) {
        $dff_get_future_user_data = dff_get_future_user_data();
        $future_user_id = $dff_get_future_user_data->id;
    }
    
    $course_id = $_POST['course_id'];
    $course_id_lang = $_POST['course_id_lang'];

    $lang = get_bloginfo('language');
    if ($lang == 'ar') {
        dff_delete_course_from_user_ar($future_user_id, $course_id);
        dff_delete_course_from_user_en($future_user_id, $course_id_lang);

    }else{
        dff_delete_course_from_user_ar($future_user_id, $course_id_lang);
        dff_delete_course_from_user_en($future_user_id, $course_id);
    }
    // dff_delete_course_from_user($course_id);
    // dff_delete_course_lang_from_user($course_id_lang);
    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_leave_course_ajax', 'leave_course_ajax_callback');
add_action('wp_ajax_nopriv_leave_course_ajax', 'leave_course_ajax_callback');
