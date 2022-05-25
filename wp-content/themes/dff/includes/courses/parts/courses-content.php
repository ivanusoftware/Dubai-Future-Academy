<?php
$posttype = get_post_type();
if ($img = get_image_by_id($courses->ID)) $src = $img[0];
else $src = '';
$courses_format = get_field_object('courses_format');
$courses_format_value = $courses_format['value'];
$courses_format_label = $courses_format['choices'][$courses_format_value];
$courses_tax_obj_list = get_the_terms($courses->ID, "courses-categories");
?>

<div class="course-item-img" style="background-image: url(<?php echo $src; ?>);">
    <!-- <img src="<?php echo $src; ?>" alt=""> -->
    <span class="courses-item-category">
        <?php
        if (is_array($courses_tax_obj_list) || is_object($courses_tax_obj_list)) {
            foreach ($courses_tax_obj_list as $courses_tax) {
                // $term = get_queried_object();                    
                $course_category_icon = get_field('course_category_icon', $courses_tax->taxonomy . '_' . $courses_tax->term_id);
                echo '<h4><span><img src="' . $course_category_icon['url'] . '" /></span>' . $courses_tax->name .  '</h4>';
            }
        }
        ?>
    </span>
</div>
<div class="course-item-desc">
    <h2><?php the_title(); ?></h2>

</div>
<div class="course-duration">
    <?php
    if ($courses_format_value == 'open_course') {
        echo '<h5>' . $courses_format_label . '</h5>';
    } else {
    ?>
        <?php if (have_rows('course_time_group')) : ?>
            <?php while (have_rows('course_time_group')) : the_row();
                $course_start = get_sub_field('course_start');
                $course_finish = get_sub_field('course_finish');
                // Load field value and convert to numeric timestamp.                                                
                $date_course_start  = date_i18n("j M Y", strtotime($course_start));
                $date_course_finish = date_i18n("j M Y",  strtotime($course_finish));
                echo  '<h5>' .  $date_course_start . ' - ' .  $date_course_finish . '</h5>';
            endwhile; ?>
        <?php endif; ?>
    <?php
    }
    ?>

    <div class="course-duration__status">
        <?php echo _e('Completed Course', 'dff'); ?>
    </div>    
</div>
<div class="course-progress">
    <div class="course-progress__wrap">
        <div class="course-progress__line" style="width:15%"></div>
    </div>
    <div class="course-progress__value">15%</div>
</div>