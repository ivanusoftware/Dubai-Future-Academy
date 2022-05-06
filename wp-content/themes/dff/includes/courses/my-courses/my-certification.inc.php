<article class="archive-courses-list certificate-wrap">
    <?php
    $user_certificates = get_user_meta($current_user_id, 'user_courses_certificate4', true);
    if (dff_get_result_exam($user_certificates) == 'true') {
        foreach ($user_certificates as $user_certificate) {
            $image = wp_get_attachment_image_src($user_certificate['image_id'], 'full');
            if ($user_certificate['result_exam'] >= 80) {
    ?>
            <div class="course-item">
                <div class="course-item-content">
                    <h6 class="certificate"><?php _e('Certificate', 'dff'); ?></h6>
                    <div class="course-item-img" style="background-image: url(<?php echo $image[0]; ?>);">
                    </div>
                    <div class="course-item-desc">
                        <h2><?php echo $user_certificate['title']; ?></h2>
                    </div>
                    <a class="btn-course-primary download-certificate"><?php _e('Download', 'dff'); ?></a>
                </div>
            </div>
        <?php
            }
        }
    }else {
        ?>
        <p><?php printf(__('You have no certifications. Please, check our <a href="%s">courses</a>', 'dff'), esc_url(site_url('courses'))); ?></p>
    <?php
    }
    ?>
</article>