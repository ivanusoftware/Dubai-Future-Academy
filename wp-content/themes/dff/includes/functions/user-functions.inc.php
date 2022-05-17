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
/**
 * dff_user_course_module_result function.
 *
 * @param [type] $current_user_id
 * @param [type] $course_id
 * @return void
 */
if (!function_exists('dff_user_course_module_result')) {
    function dff_user_course_module_result($current_user_id, $course_id)
    {
        $module_exam_key = 'course_' . $course_id . '_exam_result'; 
        $get_exem_result = get_user_meta($current_user_id, $module_exam_key, true);
        if (have_rows('course_module_repeater', $course_id)) :
            while (have_rows('course_module_repeater', $course_id)) : the_row();
                $module_or_exam = get_sub_field('module_or_exam');
                $module_i = get_row_index();
                $module_key = 'course_' . $course_id . '_module_' . $module_i . '_result';
                $get_result = get_user_meta($current_user_id, $module_key, true);
                if (!$get_result && $module_or_exam == 'module') {
                    update_user_meta(get_current_user_id(), $module_key, true);
                } elseif(!$get_exem_result && $module_or_exam == 'exam') {
                    update_user_meta(get_current_user_id(), $module_exam_key, true);
                }
            endwhile;
        else :
        endif;
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
    foreach ($user_certificates as $user_certificate) {
        if ($user_certificate['result_exam'] >= 80) {
            $result = 'true';
        }
    }
    return $result;
}


// Returns the user s course certificate.
function dff_user_courses_certificate($current_user_id, $dff_user_courses_ids)
{

    foreach ($dff_user_courses_ids as $user_courses_id) {
        $exam_key    = 'course_' . $user_courses_id . '_exam_result';
        $exam_result = get_user_meta($current_user_id, $exam_key, true);
        if ($exam_result >= 80) {
            // $user = get_user_by( 'id', $current_user_id );
            // print_r($user);
            $exam_date = date('Y-m-d');
            $name =  get_display_name($current_user_id);
            $event = get_the_title($user_courses_id);
            $lenghts = get_field('course_lenghts', $user_courses_id);
            $pdf_certificate_url = make_participation_certificate($name, $event, $user_courses_id, $current_user_id, $exam_date, $lenghts);
        }

        $user_certificate[] = array(
            'course_id'             => $user_courses_id,
            'title'                 => get_the_title($user_courses_id),
            'result_exam'           => $exam_result == 1 ? 1 : $exam_result,
            'image_id'              => get_post_thumbnail_id($user_courses_id),
            'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
            'lenghts'               => $lenghts,
            'exam_date'             => $exam_date,
        );
        $certificate = update_user_meta($current_user_id, 'user_courses_certificate4', $user_certificate);
    }
    return $certificate;
}


