<?php
function create_future_user($future_user_id)
{
    global $wpdb;
    $table_name = $wpdb->base_prefix . 'dff_future_users';
    // $user_id = $wpdb->get_results("SELECT ID FROM $table_name WHERE future_user_id = $future_user_id");
    // $user_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");


    // $res = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE `future_user_id` = %s",$future_user_id));
    $res = $wpdb->get_row($wpdb->prepare("SELECT future_user_id FROM $table_name WHERE future_user_id = '$future_user_id'"));
    // print_r($user_id);
    if (!$res) {
        //if post id not already added
        $wpdb->insert(
            $table_name,
            array(
                'future_user_id' => $future_user_id,
                'user_date' => current_time('Y-m-d H:i:s'),
                'user_date_gmt' => current_time('Y-m-d H:i:s')
            )
        );
    }
}

if (!function_exists('add_course_id_future_user_en')) {
    // Adds a new post id to a course.
    function add_course_id_future_user_en($future_user_id, $course_en_id)
    {
        global $wpdb;
        $table_name = $wpdb->base_prefix . 'dff_future_users';
        $course_en_id_to_user =  $wpdb->get_row($wpdb->prepare("SELECT course_en_id FROM $table_name WHERE future_user_id = '$future_user_id'"));
        // $course_en_id_array = unserialize($course_en_id_to_user);
        if (!empty($course_en_id_to_user)) {
            $course_en_id_array = unserialize($course_en_id_to_user->course_en_id);
            // Check if value exists
            if (in_array($course_en_id, $course_en_id_array)) {
                // echo 'Already exists course with ID: ' . $course_id;
            } else {
                $course_en_id_array[] = $course_en_id;
                $wpdb->update(
                    $table_name,
                    array(
                        'course_en_id' => serialize($course_en_id_array)
                    ),
                    array('future_user_id' => $future_user_id)
                );
            }
        } else {
            $wpdb->update(
                $table_name,
                array(
                    'course_en_id' => serialize([$course_en_id])
                ),
                array('future_user_id' => $future_user_id)
            );
        }
    }
}


if (!function_exists('add_course_id_future_user_ar')) {
    // Adds a new post id to a course.
    function add_course_id_future_user_ar($future_user_id, $course_ar_id)
    {
        global $wpdb;
        $table_name = $wpdb->base_prefix . 'dff_future_users';

        $course_ar_id_to_user =  $wpdb->get_row($wpdb->prepare("SELECT course_ar_id FROM $table_name WHERE future_user_id = '$future_user_id'"));
        // $course_en_id_array = unserialize($course_en_id_to_user);
        if (!empty($course_ar_id_to_user)) {
            $course_ar_id_array = unserialize($course_ar_id_to_user->course_ar_id);
            // Check if value exists
            if (in_array($course_ar_id, $course_ar_id_array)) {
                // echo 'Already exists course with ID: ' . $course_id;
            } else {
                $course_ar_id_array[] = $course_ar_id;
                $wpdb->update(
                    $table_name,
                    array(
                        'course_ar_id' => serialize($course_ar_id_array)
                    ),
                    array('future_user_id' => $future_user_id)
                );
            }
        } else {
            $wpdb->update(
                $table_name,
                array(
                    'course_ar_id' => serialize([$course_ar_id])
                ),
                array('future_user_id' => $future_user_id)
            );
        }
    }
}


if (!function_exists('future_user_course_module_test_results')) {
    function future_user_course_module_test_results($future_user_id, $module_key)
    {
        // $module_exam_key = 'course_' . $course_id . '_exam_result';
        // $get_exem_result = get_user_meta($current_user_id, $module_exam_key, true);
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';

        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));

        $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$module_key'"));

        if (!$response_usmeta->dff_meta_key) {
            // if (empty($response_usmeta)) {
            //if post id not already added
            $wpdb->insert(
                $table_name_dff_future_usemeta,
                array(
                    'dff_future_user_id' => $response->ID,
                    'dff_meta_key' => $module_key,
                    'dff_meta_value' => null,
                    'user_date' => current_time('Y-m-d H:i:s'),
                    'user_date_gmt' => current_time('Y-m-d H:i:s')
                )
            );
            // }

        }
    }
}

// if (!function_exists('future_user_course_module_test_results_ar')) {
//     function future_user_course_module_test_results_ar($future_user_id, $module_key)
//     {
//         // $module_exam_key = 'course_' . $course_id . '_exam_result';
//         // $get_exem_result = get_user_meta($current_user_id, $module_exam_key, true);
//         global $wpdb;
//         $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
//         $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';

//         $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));

//         $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$module_key'"));

//         if (!$response_usmeta->dff_meta_key) {
//             // if (empty($response_usmeta)) {
//             //if post id not already added
//             $wpdb->insert(
//                 $table_name_dff_future_usemeta,
//                 array(
//                     'dff_future_user_id' => $response->ID,
//                     'dff_meta_key' => $module_key,
//                     'dff_meta_value' => null,
//                     'user_date' => current_time('Y-m-d H:i:s'),
//                     'user_date_gmt' => current_time('Y-m-d H:i:s')
//                 )
//             );
//             // }

//         }
//     }
// }

if (!function_exists('future_user_course_module_exem_result')) {
    function future_user_course_module_exem_result($future_user_id, $exem_module_key)
    {
        // $module_exam_key = 'course_' . $course_id . '_exam_result';
        // $get_exem_result = get_user_meta($current_user_id, $module_exam_key, true);
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';

        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));
        $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$exem_module_key'"));
        if (!$response_usmeta) {
            //if post id not already added
            $wpdb->insert(
                $table_name_dff_future_usemeta,
                array(
                    'dff_future_user_id' => $response->ID,
                    'dff_meta_key' => $exem_module_key,
                    'dff_meta_value' => null,
                    'user_date' => current_time('Y-m-d H:i:s'),
                    'user_date_gmt' => current_time('Y-m-d H:i:s')
                )
            );
        }
    }
}

if (!function_exists('future_user_course_module_certificate')) {
    function future_user_course_module_certificate($future_user_id, $certificate_key, $courses_id)
    {
        // $module_exam_key = 'course_' . $course_id . '_exam_result';
        // $get_exem_result = get_user_meta($current_user_id, $module_exam_key, true);
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';

        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));
        $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$certificate_key'"));

        $name =  'Ivan Chumak';
        $title_en = get_the_title($courses_id);
        // $title_ar = $post_title_ar->post_title;
        $cat_name_en = pdf_return_courses_taxonomy($courses_id);
        // $cat_name_ar = $post_cat_name_ar->name;
        $pdf_certificate_url = make_participation_certificate($courses_id, $future_user_id);

        $user_certificate_en[] = array(
            'course_id'             => $courses_id,
            'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
        );

        if (!$response_usmeta) {
            //if post id not already added
            $wpdb->insert(
                $table_name_dff_future_usemeta,
                array(
                    'dff_future_user_id' => $response->ID,
                    'dff_meta_key' => $certificate_key,
                    'dff_meta_value' => serialize($user_certificate_en),
                    'user_date' => current_time('Y-m-d H:i:s'),
                    'user_date_gmt' => current_time('Y-m-d H:i:s')
                )
            );
        } else {
            $wpdb->update(
                $table_name_dff_future_usemeta,
                array(
                    'dff_meta_key' => $certificate_key,
                    'dff_meta_value' => serialize($user_certificate_en),
                ),
                array('dff_future_user_id' => $response->ID, 'dff_meta_key' => $certificate_key)
            );
        }
    }
}

if (!function_exists('get_future_user_course_certificate')) {
    function get_future_user_course_certificate($future_user_id, $certificate_key)
    {
        // $module_exam_key = 'course_' . $course_id . '_exam_result';
        // $get_exem_result = get_user_meta($current_user_id, $module_exam_key, true);
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';

        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));
        $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT dff_meta_value FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$certificate_key'"));
        return unserialize($response_usmeta->dff_meta_value);
    }
}



if (!function_exists('future_user_courses_ids')) {
    function future_user_courses_ids($future_user_id)
    {
        global $wpdb;
        $table_name = $wpdb->base_prefix . 'dff_future_users';
        $user_courses =  $wpdb->get_row("SELECT course_en_id, course_ar_id FROM $table_name WHERE future_user_id = '$future_user_id'");
        $future_user_courses_ids_array = $user_courses;
        $course_en_id = unserialize($future_user_courses_ids_array->course_en_id);
        $course_ar_id = unserialize($future_user_courses_ids_array->course_ar_id);
        $lang = get_bloginfo('language');
        $future_courses_ids = ($lang == 'ar' ? $course_ar_id : $course_en_id);
        return $future_courses_ids;
    }
}

if (!function_exists('update_tests_result')) {
    function update_tests_result($future_user_id, $module_key, $value)
    {
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';

        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));

        // $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT dff_meta_key FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$module_key'"));
        // //         echo $module_key;
        // // echo $value;
        // // if ($response_usmeta->dff_meta_key == $module_key) {
        $wpdb->update(
            $table_name_dff_future_usemeta,
            array(
                'dff_meta_key' => $module_key,
                'dff_meta_value' => $value,
            ),
            array('dff_future_user_id' => $response->ID, 'dff_meta_key' => $module_key)
        );
        // }
    }
}

if (!function_exists('update_exam_result')) {
    function update_exam_result($future_user_id, $exam_key, $value)
    {
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';
        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));
        $wpdb->update(
            $table_name_dff_future_usemeta,
            array(
                'dff_meta_key' => $exam_key,
                'dff_meta_value' => $value,
            ),
            array('dff_future_user_id' => $response->ID, 'dff_meta_key' => $exam_key)
        );
    }
}

if (!function_exists('get_exam_result')) {
    function get_exam_result($future_user_id, $exam_key)
    {
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';
        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));

        $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT dff_meta_value FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$exam_key'"));
        return $response_usmeta->dff_meta_value;
    }
}

if (!function_exists('get_test_result')) {
    function get_test_result($future_user_id, $module_key)
    {
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';
        $response = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));

        $response_usmeta = $wpdb->get_row($wpdb->prepare("SELECT dff_meta_value FROM $table_name_dff_future_usemeta WHERE dff_future_user_id = '$response->ID' AND dff_meta_key = '$module_key'"));
        return $response_usmeta->dff_meta_value;
    }
}


// Returns the user meta for a given course.
if (!function_exists('dff_delete_course_from_user_en')) {
    function dff_delete_course_from_user_en($future_user_id, $course_id)
    {
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        // $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';
        $response = $wpdb->get_row($wpdb->prepare("SELECT course_en_id FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));
        if (!empty($response->course_en_id)) {
            $check_course_id_to_array = unserialize($response->course_en_id);
            if (in_array($course_id, $check_course_id_to_array)) {
                $check_course_id_to_array = array_diff($check_course_id_to_array, [$course_id]);
                $wpdb->update(
                    $table_name_dff_future_users,
                    array(
                        'course_en_id' => serialize($check_course_id_to_array)
                    ),
                    array('future_user_id' => $future_user_id)
                );
            }
        }
    }
}

if (!function_exists('dff_delete_course_from_user_ar')) {
    function dff_delete_course_from_user_ar($future_user_id, $course_id)
    {
        global $wpdb;
        $table_name_dff_future_users = $wpdb->base_prefix . 'dff_future_users';
        // $table_name_dff_future_usemeta = $wpdb->base_prefix . 'dff_future_usemeta';
        $response = $wpdb->get_row($wpdb->prepare("SELECT course_ar_id FROM $table_name_dff_future_users WHERE future_user_id = '$future_user_id'"));
        if (!empty($response->course_ar_id)) {
            $check_course_id_to_array = unserialize($response->course_ar_id);
            if (in_array($course_id, $check_course_id_to_array)) {
                $check_course_id_to_array = array_diff($check_course_id_to_array, [$course_id]);
                $wpdb->update(
                    $table_name_dff_future_users,
                    array(
                        'course_ar_id' => serialize($check_course_id_to_array)
                    ),
                    array('future_user_id' => $future_user_id)
                );
            }
        }
        // Get PDF certificate url
    }
}

if (!function_exists('get_pdf_certificate_url')) {
    function get_pdf_certificate_url($future_user_id, $course_id)
    {
        $certificate_key = 'course_' . $course_id . '_certificate';
        return get_future_user_course_certificate($future_user_id, $certificate_key);        
    }
}
