<?php
add_filter('use_block_editor_for_post_type', 'dff_disable_gutenberg', 10, 2);

function dff_disable_gutenberg($gutenberg_filter, $post_type)
{
    switch ($post_type) {
        case 'courses':
            return false;
            break;
            // case 'services':
            //     return false;
            //     break;      
    }

    return $gutenberg_filter;
}

/**
 * Options page
 */
// if (function_exists('acf_add_options_page')) {


//     acf_add_options_sub_page(array(
//         'page_title'  => 'Portfolio Settings',
//         'menu_title'  => 'Portfolio Settings',
//         'parent_slug' => 'edit.php?post_type=portfolio'
//     ));
//     acf_add_options_sub_page(array(
//         'page_title'  => 'Products Settings',
//         'menu_title'  => 'Products Settings',
//         'parent_slug' => 'edit.php?post_type=products'
//     ));

//     acf_add_options_sub_page(array(
//         'page_title'  => 'Vacancies Settings',
//         'menu_title'  => 'Vacancies Settings',
//         'parent_slug' => 'edit.php?post_type=vacancies'
//     ));
// }

// add_action('admin_menu', 'add_options_pages');
// function add_options_pages()
// {
//     if (!function_exists('acf_add_options_page')) {
//         return;
//     }
//     $parent_settings = acf_add_options_page(array(
//         'page_title'     => __('Theme General Settings', 'smarttek'),
//         'menu_title'     => __('Theme Settings', 'smarttek'),
//         'menu_slug'     => 'theme-settings',
//     ));
//     $parent = $parent_settings['menu_slug'];
//     $sub_options_pages = array(
//         'Why Choose Us',
//         'SmartTek in Nums',
//         'Our Team',
//         'Trust Us',
//         'Offices Info',
//         'Social links',
//         'Footer menu',
//         'Blog info',
//         'Forms',
//         'Third-party APIs',
//         'ChatBot',
//     );
//     foreach ($sub_options_pages as $title) {
//         acf_add_options_sub_page(
//             array(
//                 'page_title' => $title,
//                 'parent_slug' => $parent
//             )
//         );
//     }
// }


/*********************************************************************************
 * The function show default image.
 * @param $pid
 * @param string $size
 * @return array|bool|false
 ********************************************************************************/
function get_image_by_id($pid, $size = '')
{
    $img = false;
    $thumbnail_id = get_post_thumbnail_id($pid);
    if ($thumbnail_id)
        $img = wp_get_attachment_image_src($thumbnail_id, $size);
    return $img;
}

/**
 * The Handler Ajax request for courses category load more
 *
 * @return void
 */
/**
 * Archive portfolio Ajax filter
 *
 * @return void
 */
add_action('wp_ajax_courses_tax_ajax', 'courses_tax_ajax_callback');
add_action('wp_ajax_nopriv_courses_tax_ajax', 'courses_tax_ajax_callback');

function courses_tax_ajax_callback()
{
    $tax_courses_name = "courses-categories";

    $tax_id = (int)$_POST['tax_id'];
    $args = array(
        'posts_per_page' => -1,
        'post_type'      => 'courses',
        'post_status'    => array('publish'),
        'order'          => 'DESC',
        'orderby'        => 'date',
        'tax_query'      => array(
            array(
                'taxonomy' => $tax_courses_name,
                'field'    => 'id',
                'terms'    => $tax_id ? $tax_id : get_ids_courses_category($tax_courses_name),
            )
        )
    );
    // The Query
    $courses = new WP_Query($args);
?>
    <?php
    // The Loop
    if ($courses->have_posts()) {
        while ($courses->have_posts()) {
            $courses->the_post();
            get_template_part('includes/courses/parts/courses', 'content');
        }
    } else {
        // no posts found
    }
    wp_reset_postdata();
    wp_die();
}


function get_ids_courses_category($tax_courses_name)
{
    $ids = [];
    $courses_terms = get_terms($tax_courses_name, array(
        'parent'     => 0,
        'hide_empty' => true,
    ));
    foreach ($courses_terms as $courses_term) {
        $ids[] = $courses_term->term_id;
    };
    return $ids;
}
