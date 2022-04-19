<?php
$lesson_audio_file = get_sub_field('lesson_audio_file');
$audio_file_name = get_sub_field('audio_file_name');
?>

<div class="lesson-inner-container">
    <div class="module-inner-content">
        <?php
        // print_r($lesson_audio_file);
        if ($lesson_audio_file) :

            // Extract variables.
            $url = $lesson_audio_file['url'];
            $title = $lesson_audio_file['filename'];
            $filesize = $lesson_audio_file['filesize'];
            // $caption = $lesson_audio_file['caption'];
            // $icon = $lesson_audio_file['icon'];
            // echo do_shortcode("[audio src='$url']");
            $attr = array(
                'src'      => $url,
                'loop'     => '',
                'autoplay' => '',
                'preload'  => 'none',
                // 'class'    => 'lesson-audio-file',
                // 'style'    => '50'
            );

        ?>
            <div class="upload-audio-file-wrap">
                <?php
                    echo $audio_file_name ? '<h6>' . $audio_file_name . '</h6>' : '';
                    echo wp_audio_shortcode($attr);
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>