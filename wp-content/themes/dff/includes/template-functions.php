<?php

/**********************************************************************************
 * Include js and css files
 **********************************************************************************/
include(get_template_directory() . '/includes/enqueue-script-style.php');
include(get_template_directory() . '/includes/functions/user-functions.inc.php');
/**
 * Disable Gutenberg template
 *
 * @param [type] $gutenberg_filter
 * @param [type] $post_type
 * @return void
 */
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
add_filter('use_block_editor_for_post_type', 'dff_disable_gutenberg', 10, 2);

// function id_WPSE_114111()
// {
//     global $post;
//     echo $id = $post->ID;
//     // do something
// }

// add_action('admin_notices', 'id_WPSE_114111');

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


/**
 * The function show default image.
 * @param $pid
 * @param string $size
 * @return array|bool|false
 **/
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
 * Archive Courses Ajax category filter
 *
 * @return void
 */
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
    ?>
            <div class="course-item">
                <a href="<?php echo get_the_permalink($courses->ID); ?>" class="course-item-content">
                    <?php get_template_part('includes/courses/parts/courses', 'content'); ?>
                </a>
            </div>
<?php
        }
    } else {
        // no posts found
    }
    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_courses_tax_ajax', 'courses_tax_ajax_callback');
add_action('wp_ajax_nopriv_courses_tax_ajax', 'courses_tax_ajax_callback');

/**
 * Get Ids Courses Category
 *
 * @param [type] $tax_courses_name
 * @return void
 */
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


add_action('init', function () {
    // add_rewrite_rule( 'user-profile/([a-z]+)[/]?$', 'index.php?my_course=$matches[1]', 'top' );
    // add_rewrite_rule('user-profile/([0-9]+)/?$', 'index.php&course_id=$matches[1]', 'top');
    add_rewrite_rule('my-courses/([0-9]+)[/]?$', 'index.php?course_id=$matches[1]', 'top');
});

add_filter('query_vars', function ($query_vars) {
    $query_vars[] = 'course_id';
    // $query_vars[] = 'id';
    return $query_vars;
});

add_action('template_include', function ($template) {
    // if (is_user_logged_in()) {
    //     // wp_redirect( home_url( '/wp-login.php' ), 302 );
    //     get_template_part(404);
    //     exit();
    // }
    if (get_query_var('course_id') == false || get_query_var('course_id') == '') {
        return $template;
    }
    return get_template_directory() . '/includes/courses/my-courses/my-courses.inc.php';
});



/**
 * Undocumented function
 *
 * @param [type] $bytes
 * @return void
 */
function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

add_action( 'wp_print_footer_scripts', 'mytheme_mejs_add_container_class' );

function mytheme_mejs_add_container_class() {
	if ( ! wp_script_is( 'mediaelement', 'done' ) ) {
		return;
	}
	?>
	<script>
	(function() {
		var settings = window._wpmejsSettings || {};
		settings.features = settings.features || mejs.MepDefaults.features;
		settings.features.push( 'exampleclass' );
		MediaElementPlayer.prototype.buildexampleclass = function( player ) {
			player.container.addClass( 'lesson-audio-container' );
		};
	})();
	</script>
	<?php
}

function remove_read_wpse_93843()
{
    $role = get_role('subscriber');
    $role->remove_cap('read');
}
add_action('admin_init', 'remove_read_wpse_93843');

function hide_admin_wpse_93843()
{
    if (current_user_can('subscriber')) {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action('wp_head', 'hide_admin_wpse_93843');

// function redirect_sub_to_home_wpse_93843( $redirect_to, $request, $user ) {
//     if ( isset($user->roles) && is_array( $user->roles ) ) {
//       if ( in_array( 'subscriber', $user->roles ) ) {
//           return home_url( );
//       }   
//     }
//     return $redirect_to;
// }
// add_filter( 'login_redirect', 'redirect_sub_to_home_wpse_93843', 10, 3 );



/**
 * URL Rewrites
 */
// function myRewrite()
// {
//     /** @global WP_Rewrite $wp_rewrite */
//     global $wp_rewrite;

//     $newRules = array(
//         'pets/?$' => 'index.php?my_page=pet',
//         'pets/(\d+)/?$' => sprintf(
//             'index.php?my_page=pet&pet_id=%s',
//             $wp_rewrite->preg_index(1)
//         ),
//     );

//     $wp_rewrite->rules = $newRules + (array) $wp_rewrite->rules;
// }

// add_action('generate_rewrite_rules', 'myRewrite');
