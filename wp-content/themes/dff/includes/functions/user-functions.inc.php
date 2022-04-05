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

        // $course_id = $_GET['id'];
        // $course_id = '10410';

        // if(empty($course_id)){

        //     echo 'No value';

        //     die();

        // }
        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user', true);

        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            // Check if value exists
            if (in_array($course_id, $check_course_id_to_array)) {
                echo 'Already exists course with ID: ' . $course_id;
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
