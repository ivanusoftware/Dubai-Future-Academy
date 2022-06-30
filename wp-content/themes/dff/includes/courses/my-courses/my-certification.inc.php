<article class="archive-courses-list certificate-wrap">
    <?php
    $future_courses_ids = future_user_courses_ids($future_user_id);
    $lang = get_bloginfo('language');

    if ($lang == 'ar') {
        if (is_array($future_courses_ids) || is_object($future_courses_ids)) {
            foreach ($future_courses_ids as $future_courses_id) {

                $certificate_key = 'course_' . dff_get_id_parrent_lang($future_courses_id) . '_certificate';
                if ($certificate_key) {
                    $tmp_arr = get_future_user_course_certificate($future_user_id, $certificate_key);
                    if (is_array($tmp_arr) || is_object($tmp_arr)) {
                        foreach ($tmp_arr as $item) {
                            if ($img = get_image_by_id($future_courses_id)) $src = $img[0];
                            else $src = '';
    ?>
                            <div class="course-item">
                                <div class="course-item-content">
                                    <h6 class="certificate"><?php _e('Certificate', 'dff'); ?></h6>
                                    <div class="course-item-img" style="background-image: url(<?php echo $src; ?>);">
                                    </div>
                                    <div class="course-item-desc">
                                        <h2><?php echo get_the_title($future_courses_id); ?></h2>
                                    </div>
                                    <a href="<?php echo site_url($item['pdf_certificate_url']); ?>" class="btn-course-primary download-certificate" target="_blank"><?php _e('Download', 'dff'); ?></a>
                                </div>
                            </div>
                        <?php
                        }
                    }
                }
            }
        }
    } else {
        if (is_array($future_courses_ids) || is_object($future_courses_ids)) {
            foreach ($future_courses_ids as $future_courses_id) {

                $certificate_key = 'course_' . $future_courses_id . '_certificate';
                if ($certificate_key) {
                    $tmp_arr = get_future_user_course_certificate($future_user_id, $certificate_key);
                    if (is_array($tmp_arr) || is_object($tmp_arr)) {
                        foreach ($tmp_arr as $item) {
                            if ($img = get_image_by_id($item['course_id'])) $src = $img[0];
                            else $src = '';
                        ?>
                            <div class="course-item">
                                <div class="course-item-content">
                                    <h6 class="certificate"><?php _e('Certificate', 'dff'); ?></h6>
                                    <div class="course-item-img" style="background-image: url(<?php echo $src; ?>);">
                                    </div>
                                    <div class="course-item-desc">
                                        <h2><?php echo get_the_title($item['course_id']); ?></h2>
                                    </div>
                                    <a href="<?php echo site_url($item['pdf_certificate_url']); ?>" class="btn-course-primary download-certificate" target="_blank"><?php _e('Download', 'dff'); ?></a>
                                </div>
                            </div>
    <?php
                        }
                    }
                }
            }
        }
    }

    ?>
</article>