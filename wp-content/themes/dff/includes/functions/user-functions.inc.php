<?php

/**
 * The function get course id
 *
 * @param [type] $current_user_id
 * @param [type] $course_id
 * @return void
 */
if (!function_exists('dff_user_courses')) {
    function dff_user_courses($current_user_id, $course_id)
    {

        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user', true);

        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            // Check if value exists
            if (in_array($course_id, $check_course_id_to_array)) {
                // echo 'Already exists course with ID: ' . $course_id;
            } else {
                $check_course_id_to_array[] = $course_id;
                update_user_meta(get_current_user_id(), 'course_id_to_user', serialize($check_course_id_to_array));
            }
        } else {
            update_user_meta(get_current_user_id(), 'course_id_to_user', serialize([$course_id]));
        }
        return $course_id_to_user;
    }
}

if (!function_exists('dff_user_course_module_result')) {
    function dff_user_course_module_result($current_user_id, $course_id)
    {
        $module_exam_key = 'course_' . $course_id . '_exam_result';
        if (have_rows('course_module_repeater', $course_id)) :
            while (have_rows('course_module_repeater', $course_id)) : the_row();
                $module_or_exam = get_sub_field('module_or_exam');
                $module_i = get_row_index();
                $module_key = 'course_' . $course_id . '_module_' . $module_i . '_result';
                $get_result = get_user_meta($current_user_id, $module_key, true);
                if (!$get_result && $module_or_exam == 'module') {
                    update_user_meta(get_current_user_id(), $module_key, true);
                } else {
                    update_user_meta(get_current_user_id(), $module_exam_key, true);
                }
            endwhile;
        else :
        endif;
    }
}
// function dff_add_user_course_module_result($user_id, $course_id, $module_i, $test_result)
// {
//     // $module_exam_key = 'course_' . $course_id . '_exam_result';
//     if (have_rows('course_module_repeater', $course_id)) :
//         while (have_rows('course_module_repeater', $course_id)) : the_row();
//             $module_or_exam = get_sub_field('module_or_exam');            
//             $module_key = 'course_' . $course_id . '_module_' . $module_i . '_result';            
//             if ($module_or_exam == 'module') {
//                 update_user_meta($user_id, $module_key, $test_result);
//             }
//         endwhile;
//     else :
//     endif;
// }
