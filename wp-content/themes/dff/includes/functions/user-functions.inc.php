<?php

// Returns the course id of the current user.
if (!function_exists('dff_delete_course_lang_from_user')) {
    function dff_delete_course_lang_from_user($course_id_lang)
    {
        $current_user_id = get_current_user_id();
        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user', true);
        $exem_result = get_user_meta($current_user_id, 'course_' . $course_id_lang . '_exam_result', true);
        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            if (in_array($course_id_lang, $check_course_id_to_array) && $exem_result < 80) {
                $check_course_id_to_array = array_diff($check_course_id_to_array, [$course_id_lang]);
                update_user_meta(get_current_user_id(), 'course_id_to_user', serialize($check_course_id_to_array));
            }
        }
        return $course_id_to_user;
    }
}


if (!function_exists('dff_user_course_module_result')) {
    function dff_user_course_module_result($future_user_id, $course_id)
    {
        $module_exam_key = 'course_' . $course_id . '_exam_result';       

        if (have_rows('course_module_repeater', $course_id)) :
            while (have_rows('course_module_repeater', $course_id)) : the_row();
                $module_or_exam = get_sub_field('module_or_exam');
                $module_i = get_row_index();
                $module_key = 'course_' . $course_id . '_module_' . $module_i . '_result';

                if ($module_or_exam == 'module') {
                    future_user_course_module_test_results($future_user_id, $module_key);
                } elseif ($module_or_exam == 'exam') {
                    future_user_course_module_exem_result($future_user_id, $module_exam_key);
                }
            endwhile;
        // else:
        endif;
    }
}

// Get meta data for a single course module.
if (!function_exists('dff_user_course_module_result_lang')) {
    function dff_user_course_module_result_lang($future_user_id, $course_id)
    {
        global $wpdb;
        $lang = get_bloginfo('language');
        if ($lang == 'ar') {
            $table_name = $wpdb->base_prefix . 'postmeta';
        }else{
            $table_name = $wpdb->base_prefix . '3_postmeta';
        }
        $module_exam_key = 'course_' . $course_id . '_exam_result';
        $response = $wpdb->get_row($wpdb->prepare("SELECT meta_value FROM $table_name WHERE meta_key LIKE 'course_module_repeater' AND post_id = '$course_id'")); 

        foreach (dff_create_array($response->meta_value) as $k => $item) {            
            $module_key = 'course_' . $course_id . '_module_' . $item . '_result';
            $module_key_module_or_exam = 'course_module_repeater_' . $k . '_module_or_exam';
            $module_or_exam = $wpdb->get_row($wpdb->prepare("SELECT meta_value FROM $table_name WHERE meta_key LIKE '$module_key_module_or_exam' AND post_id = '$course_id'"));

            if ($module_or_exam->meta_value == 'module') {
                future_user_course_module_test_results($future_user_id, $module_key);
            } elseif ($module_or_exam->meta_value == 'exam') {
                future_user_course_module_exem_result($future_user_id, $module_exam_key);
            }
        }
    }
}

/**
 * Returns the result exam for a given list of user certificates
 *
 * @param [type] $user_certificates
 * @return void
 */
function dff_get_result_exam($user_certificates)
{
    if (is_array($user_certificates) || is_object($user_certificates)) {
        foreach ($user_certificates as $user_certificate) {
            if ($user_certificate['result_exam'] >= 80) {
                $result = 'true';
            }
        }
    }
    return $result;
}