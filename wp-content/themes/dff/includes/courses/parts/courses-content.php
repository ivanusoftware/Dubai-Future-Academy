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
                echo '<h4><span><img src="' . isset($course_category_icon['url']) . '" /></span>' . $courses_tax->name .  '</h4>';
            }
        }
        ?>
    </span>
</div>
<div class="course-item-desc">
    <h2><?php the_title(); ?></h2>
</div>
