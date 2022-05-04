<?php $lesson_upload_file = get_sub_field('lesson_upload_file'); ?>
<div class="lesson-inner-container">
    <div class="module-inner-content">
        <?php
        // print_r($lesson_upload_file);
        if ($lesson_upload_file) :

            // Extract variables.
            $url      = $lesson_upload_file['url'];
            $title    = $lesson_upload_file['filename'];
            $filesize = $lesson_upload_file['filesize'];
            $caption  = $lesson_upload_file['caption'];
            // $icon = $lesson_upload_file['icon'];
        ?>
            <div class="upload-file-wrap">
                <div class="file-info">
                    <h6><?php echo esc_html($title); ?></h6>
                    <span class="file-size"><?php echo formatSizeUnits($filesize); ?></span>
                </div>
                <div class="file-button-open">
                    <a href="<?php echo esc_attr($url); ?>" target="_blank" class="btn-course-primary open-btn">
                        <?php _e('Download', 'dff'); ?>
                    </a>
                </div>
            </div>            
        <?php endif; ?>
    </div>
</div>