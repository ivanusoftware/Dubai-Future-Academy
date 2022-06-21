<article class="archive-courses-list certificate-wrap">
    <?php
    // $user_certificates = get_user_meta($current_user_id, 'user_courses_certificate4', true);
    // $user_certificates = get_user_meta($current_user_id, 'user_courses_certificate6', true);
    $course_id_to_user_ar_certificates = unserialize(get_user_meta($current_user_id, 'course_id_to_user_ar', true));
    $course_id_to_user_en_certificates = unserialize(get_user_meta($current_user_id, 'course_id_to_user_en', true));





    // $exam_key_lang    = 'course_' . dff_get_id_parrent_lang($user_courses_id) . '_exam_result';


    // $exam_result_lang = get_user_meta($current_user_id, $exam_key_lang, true);

    // print_r($user_certificates);

    $lang = get_bloginfo('language');

    if ($lang == 'ar') {
        if (is_array($course_id_to_user_ar_certificates) || is_object($course_id_to_user_ar_certificates)) {
            foreach ($course_id_to_user_ar_certificates as $course_id_ar) {
                // $image = wp_get_attachment_image_src($user_certificate['image_id'], 'full');
                //if ($user_certificate['result_exam'] >= 80) {
                // $lang = get_bloginfo('language');
                // if ($lang == 'ar') {
                //     echo 'test';
                // } else {
                $exam_key    = 'course_' . $course_id_ar . '_exam_result';
                $exam_result = get_user_meta($current_user_id, $exam_key, true);
                if ($exam_result >= 80) {
                    if ($img = get_image_by_id($course_id_ar)) $src = $img[0];
                    else $src = '';
                    $user_certificates = get_user_meta($current_user_id, 'user_courses_certificate_ar', true);
                    // echo dff_get_url_pdf_certificate($course_id_ar, $current_user_id, $user_certificates);
    ?>
                    <div class="course-item" course-id="<?php echo $user_certificate['course_id']; ?>">
                        <div class="course-item-content">
                            <h6 class="certificate"><?php _e('Certificate', 'dff'); ?></h6>
                            <div class="course-item-img" style="background-image: url(<?php echo $src; ?>);">
                            </div>
                            <div class="course-item-desc">
                                <h2><?php echo get_the_title($course_id_ar); ?></h2>
                            </div>
                            <a href="<?php echo site_url(dff_get_url_pdf_certificate($course_id_ar, $user_certificates)); ?>" class="btn-course-primary download-certificate" target="_blank"><?php _e('Download', 'dff'); ?></a>
                        </div>
                    </div>
                <?php
                }
                // }
                //}
            }
        }
    } else {
        if (is_array($course_id_to_user_en_certificates) || is_object($course_id_to_user_en_certificates)) {
            foreach ($course_id_to_user_en_certificates as $course_id_en) {
                // $image = wp_get_attachment_image_src($user_certificate['image_id'], 'full');
                //if ($user_certificate['result_exam'] >= 80) {
                // $lang = get_bloginfo('language');
                // if ($lang == 'ar') {
                //     echo 'test';
                // } else {
                $exam_key    = 'course_' . $course_id_en . '_exam_result';
                $exam_result = get_user_meta($current_user_id, $exam_key, true);
                if ($exam_result >= 80) {
                    if ($img = get_image_by_id($course_id_en)) $src = $img[0];
                    else $src = '';
                    $user_certificates = get_user_meta($current_user_id, 'user_courses_certificate_en', true);
                ?>
                    <div class="course-item" course-id="<?php echo $user_certificate['course_id']; ?>">
                        <div class="course-item-content">
                            <h6 class="certificate"><?php _e('Certificate', 'dff'); ?></h6>
                            <div class="course-item-img" style="background-image: url(<?php echo $src; ?>);">
                            </div>
                            <div class="course-item-desc">
                                <h2><?php echo get_the_title($course_id_en); ?></h2>
                            </div>
                            <a href="<?php echo site_url(dff_get_url_pdf_certificate($course_id_en, $user_certificates)); ?>" class="btn-course-primary download-certificate" target="_blank"><?php _e('Download', 'dff'); ?></a>
                        </div>
                    </div>
    <?php
                }
            }
        }
    }

    ?>
</article>