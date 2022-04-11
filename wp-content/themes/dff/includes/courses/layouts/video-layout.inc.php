<?php
if (get_sub_field('choose_video') === 'vimeo') {
?>
    <div class="video-wrapper">
        <iframe src="https://player.vimeo.com/video/<?php echo get_sub_field('vimeo_video'); ?>" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
    <?php
} elseif (get_sub_field('choose_video') === 'upload_video') {
    $upload_video = get_sub_field('upload_video');
    // print_r($lesson_audio_file);
    if ($upload_video) :
        $attr = array(
            'src'      => $upload_video['url'],
            'loop'     => '',
            'autoplay' => '',
            // 'width'    => '810',
            'hight'    => '430',
            'preload'  => 'none',
            // 'class'    => 'video-audio-file',
            // 'style'    => '50'
        );
    ?>
        <div class="video-wrapper-upload">
            <?php echo wp_video_shortcode($attr); ?>          
        </div>
    <?php endif;
} elseif (get_sub_field('choose_video') === 'youtube') {
    $youtube_video = get_sub_field('youtube_video');
    ?>
    <div class="video-wrapper">

        <iframe width="100%" height="400" src="https://www.youtube.com/embed/<?php echo $youtube_video; ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<?php
}
?>