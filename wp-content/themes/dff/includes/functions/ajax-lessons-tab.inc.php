<?php

/**
 * Undocumented function
 *
 * @return void
 */
function upload_lesson_ajax_callback()
{
    $course_id        = $_POST['course_id'];
    $module_index     = $_POST['module_index'];
    $lesson_index     = $_POST['lesson_index'];
    $count_lesson_row = $_POST['count_lesson_row'];
    $lesson_test_id   = $_POST['lesson_test_id'];
    include(get_template_directory() . '/includes/courses/my-courses/loader.php');
    if (have_rows('course_module_repeater', $course_id)) :  while (have_rows('course_module_repeater', $course_id)) : the_row();
            $model_i = get_row_index();
            if (have_rows('course_lesson_repeater')) :
                while (have_rows('course_lesson_repeater')) : the_row();
                    $lesson_or_test = get_sub_field('lesson_or_test');
                    $lesson_i = get_row_index();
                    if ($model_i == $module_index && $lesson_i == $lesson_index && $lesson_or_test == 'lesson') {
?>
                        <div class="lesson-header">
                            <button class="back" module-index="<?php echo $module_index; ?>" lesson-index="<?php echo $lesson_index - 1; ?>" <?php echo $lesson_i == 1 ? 'disabled' : ''; ?>><?php _e('Back', 'dff'); ?></button>
                            <?php
                            if ($count_lesson_row - 1 >= $lesson_index) {
                            ?>
                                <button class="next module-lesson-test" module-index="<?php echo $module_index; ?>" lesson-index="<?php echo $lesson_index + 1; ?>" lesson-test-id="<?php echo $lesson_test_id; ?>">
                                    <?php _e('Next', 'dff'); ?>
                                </button>
                            <?php
                            } else {
                            ?>
                                <button class="next" module-index="<?php echo $module_index; ?>" lesson-index="<?php echo $lesson_index + 1; ?>">
                                    <?php _e('Next', 'dff'); ?>
                                </button>
                            <?php
                            }
                            ?>
                        </div>
    <?php
                        get_template_part('includes/courses/my-courses/parts-course/lesson-inner', 'content');
                    } elseif ($model_i == $module_index && $lesson_i == $lesson_index && $lesson_or_test == 'lesson_test') {
                        get_template_part('includes/courses/my-courses/parts-course/quiz', 'content');
                    }
                endwhile;
            // wp_reset_postdata();
            endif;
        endwhile;
    endif; ?>
<?php
    // wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_upload_lesson_ajax', 'upload_lesson_ajax_callback');
add_action('wp_ajax_nopriv_upload_lesson_ajax', 'upload_lesson_ajax_callback');


// Adds the upload_exam_ajax callback to the wp_die action.
/**
 * Undocumented function
 *
 * @return void
 */
function upload_exam_ajax_callback()
{
    $course_id    = $_POST['course_id'];
    // $module_index = $_POST['module_index'];
    // $lesson_index = $_POST['lesson_index'];
    include(get_template_directory() . '/includes/courses/my-courses/loader.php');
    $module_type  = $_POST['module_type'];
?>
    <?php if (have_rows('course_module_repeater',  $course_id)) :
        // $row_count = count(get_field('course_module_repeater'));
        while (have_rows('course_module_repeater',  $course_id)) : the_row();
            $module_or_exam = get_sub_field('module_or_exam');
            // $model_i = get_row_index();
            if ($module_or_exam == $module_type) {
                get_template_part('includes/courses/my-courses/parts-course/exam', 'content');
            }

        endwhile;
    endif; ?>
<?php
    wp_die();
}
add_action('wp_ajax_upload_exam_ajax', 'upload_exam_ajax_callback');
add_action('wp_ajax_nopriv_upload_exam_ajax', 'upload_exam_ajax_callback');


// Ajax callback for tabs_lesson_ajax
/**
 * Undocumented function
 *
 * @return void
 */
function tabs_lesson_ajax_callback()
{
    $tab_id    = $_POST['main_tab_id'];
    // $course_id = $_POST['course_id'];
    if ($tab_id == 'tab-1') {
        include(get_template_directory() . '/includes/courses/my-courses/parts-course/about-course.php');
    } else if ($tab_id == 'tab-2') {
        include(get_template_directory() . '/includes/courses/my-courses/parts-course/modules-course.php');
    } else if ($tab_id == 'tab-3') {
        include(get_template_directory() . '/includes/courses/my-courses/parts-course/my-progress-course.php');
    }

    // wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_tabs_lesson_ajax', 'tabs_lesson_ajax_callback');
add_action('wp_ajax_nopriv_tabs_lesson_ajax', 'tabs_lesson_ajax_callback');


/**
 * Ajax callback for add course to the user account
 * @return void
 */
function add_lesson_to_user_ajax_callback()
{
    $course_id       = $_POST['course_id'];
    $course_id_lang  = $_POST['course_id_lang'];
    $current_user_id =  get_current_user_id();
    $course_id_cer = get_the_ID();
    dff_user_courses($current_user_id, $course_id);
    dff_user_courses_lang($current_user_id, $course_id_lang);
    $lang = get_bloginfo('language');
    if ($lang == 'ar') {
        dff_user_courses_ar($current_user_id, $course_id);
        dff_user_courses_en($current_user_id, dff_get_id_parrent_lang($course_id));
    }else{
        dff_user_courses_en($current_user_id, $course_id);
        dff_user_courses_ar($current_user_id, dff_get_id_parrent_lang($course_id));
    }
    // dff_user_courses_certificate_ids($current_user_id, $course_id_cer);

    dff_user_course_module_result($current_user_id,  $course_id);
    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_add_lesson_to_user_ajax', 'add_lesson_to_user_ajax_callback');
add_action('wp_ajax_nopriv_add_lesson_to_user_ajax', 'add_lesson_to_user_ajax_callback');


/**
 * Ajax callback for add course to the user account
 * @return void
 */
function download_certificate_ajax_callback()
{
    $user_courses_id       = $_POST['user_courses_id'];
    $current_user_id =  get_current_user_id();
    // $user_courses_id = 10410;
    // $dff_user_courses_ids = unserialize(get_user_meta($current_user_id, 'course_id_to_user', true));
    // echo __DIR__ . '/fpdf/fpdf.php';
    // require(__DIR__ . '/fpdf/fpdf.php');
    $path = $_SERVER['DOCUMENT_ROOT'];
    // require(get_template_directory() . '/includes/fpdf/fpdf.php');
    // $path . 'wp-content/themes/dff/includes/fpdf/fpdf.php';
    // dff_user_courses_certificate($current_user_id, $dff_user_courses);
    $exam_key    = 'course_' . $user_courses_id . '_exam_result';
    $exam_result = get_user_meta($current_user_id, $exam_key, true);
    if ($exam_result >= 80) {
        // $user = get_user_by( 'id', $current_user_id );
        // print_r($user);
        // require(get_template_directory() . '/includes/fpdf/fpdf.php');
        // echo get_template_directory() . '/includes/fpdf/fpdf.php';
        // require(__DIR__ . '/fpdf/fpdf.php');
        $exam_date = date('Y-m-d');
        $name =  get_display_name($current_user_id);
        $event = get_the_title($user_courses_id);
        $lenghts = get_field('course_lenghts', $user_courses_id);


        $today = date("d M Y");
        // $certificate_dimensions = array(pixel_to_pt(1123), pixel_to_pt(794));
        // $name_pos = array(pixel_to_pt(240), pixel_to_pt(350));
        // $name_size = array(pixel_to_pt(637), pixel_to_pt(0));
        // $event_pos = array(pixel_to_pt(520), pixel_to_pt(470));
        // $event_size = array(pixel_to_pt(221), pixel_to_pt(10));
        $certificate_template = esc_url(get_template_directory_uri()) . "/images/pdf-certificate.png";

        $certificate = new FPDF("Landscape", "pt", 1123, 794);
        $certificate->AddPage();

        $certificate->Image($certificate_template, 0, 0, 1123, 794);
        $certificate->SetFont("Helvetica", "", 54);
        $certificate->SetXY(240, 350);
        $certificate->Cell(637, 0, $name, 0, 0, "C");
        $certificate->SetFont("Helvetica", "", 16);
        $certificate->SetXY(220, 335);
        // $certificate->Cell($event_size[0], $event_size[1], $event, 0, 0, "C");
        // $certificate->Text(0,370, $event);
        $strText = $event;
        $strText = str_replace("\n", "<br>", $strText);
        $certificate->MultiCell(400, 25, $strText, 0, 'C', 0);

        // echo date('d M Y',strtotime($exam_date));

        $certificate->SetFont("Helvetica", "", 14);
        $certificate->Text(200, 520,  'Date: ' . date('d M Y', strtotime($exam_date)));
        $certificate->SetFont("Helvetica", "", 14);
        $certificate->Text(520, 520,  'Lenghts: ' . $lenghts . ' total hours');


        // $path = $_SERVER['DOCUMENT_ROOT'];
        // $filename = $path . 'wp-content/uploads/certificates/' . $current_user_id . '_' . $user_courses_id . '_pdf_certificate.pdf';
        // $url = '/wp-content/uploads/certificates/' . $current_user_id . '_' . $user_courses_id . '_pdf_certificate.pdf';
        // if (file_exists($filename)) {
        //     $url_file = $url;
        // } else {
        //     // $certificate->Output('wp-content/uploads/certificates/' . $current_user_id . '_' . $user_courses_id . '_pdf_certificate.pdf', 'F');
        //     $url_file = $url;
        // }

        $pdf_certificate_url =   '$url_file';

        $user_certificate[] = array(
            'course_id'             => $user_courses_id,
            'title'                 => get_the_title($user_courses_id),
            'result_exam'           => $exam_result == 1 ? 1 : $exam_result,
            'image_id'              => get_post_thumbnail_id($user_courses_id),
            'pdf_certificate_url'   => $pdf_certificate_url ? $pdf_certificate_url : '',
            'lenghts'               => $lenghts,
            'exam_date'             => $exam_date,
        );
        $key_certificate = 'user_' . $current_user_id . '_courses_' . $user_courses_id . '_certificate';
        update_user_meta($current_user_id, $key_certificate , $user_certificate);
    }




    wp_send_json_success();
    wp_die();
}
add_action('wp_ajax_download_certificate_ajax', 'download_certificate_ajax_callback');
add_action('wp_ajax_nopriv_download_certificate_ajax', 'download_certificate_ajax_callback');
