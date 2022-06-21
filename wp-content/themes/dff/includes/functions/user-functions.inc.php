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

// Returns the user id of the current course.
if (!function_exists('dff_user_courses_ar')) {
    function dff_user_courses_ar($current_user_id, $course_id)
    {

        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user_ar', true);

        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            // Check if value exists
            if (in_array($course_id, $check_course_id_to_array)) {
                // echo 'Already exists course with ID: ' . $course_id;
            } else {
                $check_course_id_to_array[] = $course_id;
                update_user_meta(get_current_user_id(), 'course_id_to_user_ar', serialize($check_course_id_to_array));
            }
        } else {
            update_user_meta(get_current_user_id(), 'course_id_to_user_ar', serialize([$course_id]));
        }
        return $course_id_to_user;
    }
}

// Returns the current user s meta information for a course.
if (!function_exists('dff_user_courses_en')) {
    function dff_user_courses_en($current_user_id, $course_id)
    {

        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user_en', true);

        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            // Check if value exists
            if (in_array($course_id, $check_course_id_to_array)) {
                // echo 'Already exists course with ID: ' . $course_id;
            } else {
                $check_course_id_to_array[] = $course_id;
                update_user_meta(get_current_user_id(), 'course_id_to_user_en', serialize($check_course_id_to_array));
            }
        } else {
            update_user_meta(get_current_user_id(), 'course_id_to_user_en', serialize([$course_id]));
        }
        return $course_id_to_user;
    }
}

/**
 * Undocumented function
 *
 * @param [type] $current_user_id
 * @param [type] $course_id_lang
 * @return void
 */
if (!function_exists('dff_user_courses_lang')) {

    function dff_user_courses_lang($current_user_id, $course_id_lang)
    {

        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user', true);

        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            // Check if value exists
            if (in_array($course_id_lang, $check_course_id_to_array)) {
                // echo 'Already exists course with ID: ' . $course_id;
            } else {
                $check_course_id_to_array[] = $course_id_lang;
                update_user_meta(get_current_user_id(), 'course_id_to_user', serialize($check_course_id_to_array));
            }
        } else {
            update_user_meta(get_current_user_id(), 'course_id_to_user', serialize([$course_id_lang]));
        }
        return $course_id_to_user;
    }
}

/**
 *  Get the course id of the current user.
 *
 * @param [type] $current_user_id
 * @param [type] $course_id_lang
 * @return void
 */
// if (!function_exists('dff_user_courses_certificate_ids')) {

//     function dff_user_courses_certificate_ids($current_user_id, $course_id_lang)
//     {

//         $course_id_to_user = get_user_meta($current_user_id, 'user_courses_certificate_ids', true);

//         if (!empty($course_id_to_user)) {
//             $check_course_id_to_array = unserialize($course_id_to_user);
//             // Check if value exists
//             if (in_array($course_id_lang, $check_course_id_to_array)) {
//                 // echo 'Already exists course with ID: ' . $course_id;
//             } else {
//                 $check_course_id_to_array[] = $course_id_lang;
//                 update_user_meta(get_current_user_id(), 'user_courses_certificate_ids', serialize($check_course_id_to_array));
//             }
//         } else {
//             update_user_meta(get_current_user_id(), 'user_courses_certificate_ids', serialize([$course_id_lang]));
//         }
//         return $course_id_to_user;
//     }
// }

// Returns the user meta for a given course.
if (!function_exists('dff_delete_course_from_user')) {
    function dff_delete_course_from_user($course_id)
    {
        $current_user_id = get_current_user_id();
        $course_id_to_user = get_user_meta($current_user_id, 'course_id_to_user', true);
        $exem_result = get_user_meta($current_user_id, 'course_' . $course_id . '_exam_result', true);
        if (!empty($course_id_to_user)) {
            $check_course_id_to_array = unserialize($course_id_to_user);
            if (in_array($course_id, $check_course_id_to_array) && $exem_result < 80) {
                $check_course_id_to_array = array_diff($check_course_id_to_array, [$course_id]);
                update_user_meta(get_current_user_id(), 'course_id_to_user', serialize($check_course_id_to_array));
            }
        }
        return $course_id_to_user;
    }
}

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
                } elseif (!$get_exem_result && $module_or_exam == 'exam') {
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
    if (is_array($user_certificates) || is_object($user_certificates)) {
        foreach ($user_certificates as $user_certificate) {
            if ($user_certificate['result_exam'] >= 80) {
                $result = 'true';
            }
        }
    }
    return $result;
}


// Returns the user s course certificate.
// function dff_user_courses_certificate($current_user_id, $dff_user_courses_ids)
// {
//     if (is_array($dff_user_courses_ids) || is_object($dff_user_courses_ids)) {
//         foreach ($dff_user_courses_ids as $user_courses_id) {
//             $exam_key    = 'course_' . $user_courses_id . '_exam_result';
//             $exam_result = get_user_meta($current_user_id, $exam_key, true);
//             // echo dff_get_translation_ids( $user_courses_id);
//             if ($exam_result >= 80) {
//                 // $user = get_user_by( 'id', $current_user_id );
//                 // print_r($user);
//                 $exam_date = date('Y-m-d');
//                 $name =  get_display_name($current_user_id);
//                 $event = get_the_title($user_courses_id);
//                 $lenghts = get_field('course_lenghts', $user_courses_id);
//                 $cat_name = pdf_return_courses_taxonomy($user_courses_id);
//                 $pdf_certificate_url = make_participation_certificate($name, $event, $user_courses_id, $current_user_id, $cat_name, $exam_date, $lenghts);
//             }

//             $user_certificate[] = array(
//                 'course_id'             => $user_courses_id,
//                 'title'                 => get_the_title($user_courses_id),
//                 'result_exam'           => $exam_result == 1 ? 1 : $exam_result,
//                 'image_id'              => get_post_thumbnail_id($user_courses_id),
//                 'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
//                 'lenghts'               => $lenghts,
//                 'exam_date'             => $exam_date,
//                 'tax_name'              => $cat_name,
//             );
//             $certificate = update_user_meta($current_user_id, 'user_courses_certificate4', $user_certificate);
//         }
//     }
//     return $certificate;
// }

function dff_user_courses_certificate($current_user_id)
{


    // unset($dff_user_courses_ids[2]);
    // print_r($dff_user_courses_ids);
    global $wpdb;
    $user_certificate_en = array();
    $user_certificate_ar = array();
    // $course_id_to_user_ar_certificates = unserialize(get_user_meta($current_user_id, 'course_id_to_user_ar', true));



    $lang = get_bloginfo('language');
    if ($lang == 'ar') {
        $course_id_to_user_ar = unserialize(get_user_meta($current_user_id, 'course_id_to_user_ar', true));
        if (is_array($course_id_to_user_ar) || is_object($course_id_to_user_ar)) {
            foreach ($course_id_to_user_ar as $courses_id) {
                // echo $courses_id;
                $courses_id_en = dff_get_id_parrent_lang($courses_id);
                $post_title_en = $wpdb->get_row("SELECT post_title FROM wp_posts WHERE ID = $courses_id_en");
                $post_cat_name_en = $wpdb->get_row("SELECT t.name FROM wp_terms t LEFT JOIN wp_term_relationships tr ON (t.term_id = tr.term_taxonomy_id) WHERE tr.object_id = $courses_id_en");
                $name =  get_display_name($current_user_id);
                $title_en = $post_title_en->post_title;
                $title_ar = get_the_title($courses_id);
                $cat_name_en = $post_cat_name_en->name;
                $cat_name_ar = pdf_return_courses_taxonomy($courses_id);
                $pdf_certificate_url = make_participation_certificate($name, $title_en, $title_ar, $courses_id, $current_user_id, $cat_name_en, $cat_name_ar);

                $user_certificate_ar[] = array(
                    'course_id'             => $courses_id,
                    'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
                );
            }
        }
        $certificate = update_user_meta($current_user_id, 'user_courses_certificate_ar', $user_certificate_ar);
    } else {
        $course_ids_to_user_en = unserialize(get_user_meta($current_user_id, 'course_id_to_user_en', true));
        if (is_array($course_ids_to_user_en) || is_object($course_ids_to_user_en)) {
            foreach ($course_ids_to_user_en as $courses_id) {
                // echo $courses_id;
                $courses_id_ar = dff_get_id_parrent_lang($courses_id);
                $post_title_ar = $wpdb->get_row("SELECT post_title FROM wp_3_posts WHERE ID = $courses_id_ar");
                $post_cat_name_ar = $wpdb->get_row("SELECT t.name FROM wp_3_terms t LEFT JOIN wp_3_term_relationships tr ON (t.term_id = tr.term_taxonomy_id) WHERE tr.object_id = $courses_id_ar");
                $name =  get_display_name($current_user_id);
                $title_en = get_the_title($courses_id);
                $title_ar = $post_title_ar->post_title;
                $cat_name_en = pdf_return_courses_taxonomy($courses_id);
                $cat_name_ar = $post_cat_name_ar->name;
                $pdf_certificate_url = make_participation_certificate($name, $title_en, $title_ar, $courses_id, $current_user_id, $cat_name_en, $cat_name_ar);

                $user_certificate_en[] = array(
                    'course_id'             => $courses_id,
                    'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
                );
            }
        }
        $certificate = update_user_meta($current_user_id, 'user_courses_certificate_en', $user_certificate_en);
    }


    // foreach ($course_ids_to_user_en as $courses_id) {

    // }

    // $user_certificate = array();
    // global $wpdb;


    // if (is_array($dff_user_courses_ids) || is_object($dff_user_courses_ids)) {



    //     foreach ($dff_user_courses_ids as $user_courses_id) {



    //         $exam_key    = 'course_' . $user_courses_id . '_exam_result';
    //         $exam_key_lang    = 'course_' . dff_get_id_parrent_lang($user_courses_id) . '_exam_result';


    //         $exam_result = get_user_meta($current_user_id, $exam_key, true);
    //         $exam_result_lang = get_user_meta($current_user_id, $exam_key_lang, true);

    //         // echo dff_get_translation_ids( $user_courses_id);



    //         if ($exam_result >= 80 && $exam_result_lang >= 80) {
    //             // $user = get_user_by( 'id', $current_user_id );
    //             // print_r($user);
    //             // $exam_date = date('Y-m-d');
    //             $user_courses_id_lang =  dff_get_id_parrent_lang($user_courses_id);
    //             $lang = get_bloginfo('language');
    //             if ($lang == 'ar') {
    //                 $title = get_the_title($user_courses_id);
    //                 $post_title_lang = $wpdb->get_row( "SELECT post_title FROM wp_posts WHERE ID = $user_courses_id_lang");

    //             } else {
    //                 $title = get_the_title($user_courses_id);

    //                 $post_title_lang = $wpdb->get_row( "SELECT post_title FROM wp_3_posts WHERE ID = $user_courses_id_lang");

    //             }





    //             $name =  get_display_name($current_user_id);

    //             $title_lang = $post_title_lang->post_title;
    //             $image_id = get_post_thumbnail_id($user_courses_id);
    //             $image_id_lang = get_post_thumbnail_id(dff_get_id_parrent_lang($user_courses_id));

    //             // $lenghts = get_field('course_lenghts', $user_courses_id);
    //             $cat_name = pdf_return_courses_taxonomy($user_courses_id);
    //             $pdf_certificate_url = make_participation_certificate($name, $title, $title_lang, $user_courses_id, $current_user_id, $cat_name);
    //         }

    //         $user_certificate_arr = array(
    //             'en' => array(
    //                 'title' => $lang != 'ar' ? $title : $title_lang
    //             ),
    //             'ar' => array(
    //                 'title' => $lang == 'ar' ? $title : $title_lang
    //             ),
    //             // 'course_id'          => $user_courses_id,
    //             // 'title'              => $title,
    //             'title_lang'         => $title_lang,
    //             'result_exam'        => $exam_result == 1 ? 1 : $exam_result,
    //             'exam_result_lang'   => $exam_result_lang == 1 ? 1 : $exam_result_lang,
    //             'image_id'           => $image_id,
    //             'image_id_lang'           => $image_id_lang,
    //             'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
    //             // 'lenghts'               => $lenghts,
    //             // 'exam_date'             => $exam_date,
    //             'tax_name'              => $cat_name,
    //         );

    //         $user_certificate[] =  $user_certificate_arr;

    //     }
    // }
    // $certificate = update_user_meta($current_user_id, 'user_courses_certificate6', $user_certificate);
    // // print_r($certificate);
    return $certificate;
}
