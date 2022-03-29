<?php
$paged_no = 1;
$language = get_option( 'dff_language' );
$data     = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
if ( isset( $data ) && false !== $data ) {
	$attributes        = $data;
	$paged_no          = isset( $attributes['paged'] ) ? (int) $attributes['paged'] : $paged_no;
	$cats              = isset( $attributes['checkedCats'] ) && 'all' !== $attributes['checkedCats'] ? explode( ',', $attributes['checkedCats'] ) : 'all';
	$tags              = isset( $attributes['checkedTags'] ) && 'all' !== $attributes['checkedTags'] ? explode( ',', $attributes['checkedTags'] ) : 'all';
	$show_upcoming_tog = isset( $attributes['upcomingEvent'] ) && ! empty( $attributes['upcomingEvent'] ) ? 'true' === $attributes['upcomingEvent'] ? true : false : true;
	$from              = 'frontend';
} else {
	$from              = 'backend';
	$cats              = 0 !== count( $attributes['checkedCats'] ) ? $attributes['checkedCats'] : 'all';
	$tags              = 0 !== count( $attributes['checkedTags'] ) ? $attributes['checkedTags'] : 'all';
	$show_upcoming_tog = isset( $attributes['openUpcomingToggle'] ) ? $attributes['openUpcomingToggle'] : true;
}

$total_events      = isset( $attributes['totalEvents'] ) ? (int) $attributes['totalEvents'] : 12;
$order_by          = isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'date';
$e_layout          = isset( $attributes['eLayout'] ) ? $attributes['eLayout'] : 'list-view';
$block_order       = 'date' === $order_by ? 'DESC' : 'ASC';
$feature_image_tog = isset( $attributes['featureImageToggle'] ) ? $attributes['featureImageToggle'] : true;
$pagination_tog    = isset( $attributes['paginationToggle'] ) ? $attributes['paginationToggle'] : true;
$datetime_tog      = isset( $attributes['dateTimeToggle'] ) ? $attributes['dateTimeToggle'] : true;

$open_new_tab_tog = isset( $attributes['openNewTabToggle'] ) ? $attributes['openNewTabToggle'] : true;
$cats_tog         = isset( $attributes['catsToggle'] ) ? $attributes['catsToggle'] : true;
$tags_tog         = isset( $attributes['tagsToggle'] ) ? $attributes['tagsToggle'] : true;
$title_color      = isset( $attributes['titleColor'] ) ? $attributes['titleColor'] : '#000';
$text_color       = isset( $attributes['textColor'] ) ? $attributes['textColor'] : '#000';
$bg_color         = isset( $attributes['bgColor'] ) ? $attributes['bgColor'] : 'transparent';

$args = array(
	'post_type'      => 'dff-events',
	'post_status'    => 'publish',
	'orderby'        => $order_by,
	'order'          => $block_order,
	'posts_per_page' => $total_events,
	'paged'          => $paged_no,
);

// tax query
$args['tax_query'] = array();

if ( ! empty( $cats ) && 'all' !== $cats ) {
	$args['tax_query'][] = array(
		'taxonomy' => 'events_categories',
		'field'    => 'term_id',
		'terms'    => $cats,
	);

	$cats = implode( ',', $cats );
}

if ( ! empty( $tags ) && 'all' !== $tags ) {
	$args['tax_query'][] = array(
		'taxonomy' => 'events_tags',
		'field'    => 'term_id',
		'terms'    => $tags,
	);

	$tags = implode( ',', $tags );
}

$args['meta_query']    = array();
$upcoming_event_hidden = 'false';
if ( $show_upcoming_tog ) {
	$upcoming_event_hidden = 'true';
	$args['meta_query'][]  = array(
		'key'   => 'upcoming',
		'value' => 'yes',
	);
}

if ( 0 === count( $args['tax_query'] ) ) {
	unset( $args['tax_query'] );
}
if ( 0 === count( $args['meta_query'] ) ) {
	unset( $args['meta_query'] );
}

$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ) {

	if ( 'ar' === $language ) {
		$date_str    = 'تاريخ';
		$time_str    = 'وقت';
		$cats_str    = 'التصنيفات';
		$tags_str    = 'العلامات';
		$view_detail = 'عرض التفاصيل';
	} else {
		$date_str    = 'Date';
		$time_str    = 'Time';
		$cats_str    = 'Categories';
		$tags_str    = 'Tags';
		$view_detail = 'View Details';
	}

	if ( 'backend' === $from ) { ?>
		<div id="dff-events-wrapper">
	<?php
	}
	?>
	<div class="<?php echo esc_attr( $e_layout . ' lang_' . $language ); ?> grid-main" style="background-color: <?php echo sanitize_hex_color( $bg_color ); ?> !important;">
		<?php
		while ( $the_query->have_posts() ) {

			$the_query->the_post();

			$eid            = get_the_ID();
			$etitle         = get_the_title();
			$featured_image = get_the_post_thumbnail_url( $eid, 'full' );
			$e_date         = get_post_meta( $eid, 'event_date_select', true );
			$e_date         = ! empty( $e_date ) ? date( 'F d, Y', strtotime( $e_date ) ) : '-';

			$e_end_date     = get_post_meta( $eid, 'event_end_date_select', true );
			$e_end_date     = ! empty( $e_end_date ) ? date( 'F d, Y', strtotime( $e_end_date ) ) : '';

			$slug           = get_post_meta( $eid, 'event_slug', true );
			$source_eid     = get_post_meta( $eid, 'eid', true );

			if ( 'ar' === $language ) {
				$slug           = ! empty( $slug ) ? EVENT_SOURCE_URL . "/$slug/?lang=ar" : EVENT_SOURCE_URL . "/?p=$source_eid/?lang=ar";
			} else {
				$slug           = ! empty( $slug ) ? EVENT_SOURCE_URL . "/$slug" : EVENT_SOURCE_URL . "/?p=$source_eid";
			}

			$e_starttime    = get_post_meta( $eid, 'event_time_start_select', true );
			$e_endtime      = get_post_meta( $eid, 'event_time_end_select', true );
			$featured_image_id = get_post_thumbnail_id( $eid );
			$image_alt      = get_post_meta( $featured_image_id, '_wp_attachment_image_alt', true );
			$image_title    = get_the_title( $featured_image_id );

			if ( 'ar' === $language ) {
				$months = [
					'January'   => 'كانون الثاني',
					'February'  => 'شهر فبراير',
					'March'     => 'مارس',
					'April'     => 'أبريل',
					'May'       => 'مايو',
					'June'      => 'يونيو',
					'July'      => 'يوليو',
					'August'    => 'أغسطس',
					'September' => 'سبتمبر',
					'October'   => 'اكتوبر',
					'November'  => 'شهر نوفمبر',
					'December'  => 'ديسمبر',
				];
				if ( ! empty( $e_date ) ) {
					$day    = date( 'd', strtotime( $e_date ) );
					$month  = date( 'F', strtotime( $e_date ) );
					$year   = date( 'Y', strtotime( $e_date ) );
					$month  = $months[ $month ];
					$e_date = $month . ' ' . $day . ' ,' . $year;
				}

				if ( ! empty( $e_end_date ) ) {
					$day    = date( 'd', strtotime( $e_end_date ) );
					$month  = date( 'F', strtotime( $e_end_date ) );
					$year   = date( 'Y', strtotime( $e_end_date ) );
					$month  = $months[ $month ];
					$e_end_date = $month . ' ' . $day . ' ,' . $year;
				}

			
				$e_starttime      = new DateTime( "$e_starttime" );
				$e_endtime        = new DateTime( "$e_endtime" );
				$e_starttime      = $e_starttime->format( 'h:i A' );
				$e_endtime        = $e_endtime->format( 'h:i A' );
				$event_time_frame = $e_starttime . ' - ' . $e_endtime;
				$event_time_frame = str_replace( 'AM', 'صباحًا', $event_time_frame );
				$event_time_frame = str_replace( 'PM', 'مساءً', $event_time_frame );
			} else {
				$e_starttime      = new DateTime( "$e_starttime" );
				$e_endtime        = new DateTime( "$e_endtime" );
				$e_starttime      = $e_starttime->format( 'h:i A' );
				$e_endtime        = $e_endtime->format( 'h:i A' );
				$event_time_frame = $e_starttime . ' - ' . $e_endtime;
			}

			if( isset( $e_end_date ) && !empty( $e_end_date ) ) {
				$e_date = $e_date . " - " . $e_end_date;
			}

			// Image should be in background for Card View.
			$image_bg = '';
			if ( 'card-view' === $e_layout ) {
				$image_bg = ! empty( $featured_image ) ? $featured_image : EVENT_PLUGIN_URL . 'assets/images/default-event.png';
				$image_bg = "background-image: url($image_bg)";
			}
			?>
			<div class="grid-item">
				<div class="grid-inner" style="<?php echo esc_attr( $image_bg ); ?>">
					<?php
					if ( $feature_image_tog ) {
						$featured_image = false !== $featured_image ? $featured_image : EVENT_PLUGIN_URL . 'assets/images/default-event.png';
						if ( 'card-view' !== $e_layout ) {
						?>
							<div class="thumbnail">
								<img class="e_images" src="<?php echo esc_url( $featured_image ); ?>" alt="<?php if( isset( $image_alt ) && !empty( $image_alt ) ) { echo $image_alt; } else { echo $image_title; } ?>"/>
							</div>
						<?php
						}
					}
					?>
					<div class="details">
						<h3 class="title"><a href="<?php echo esc_url( $slug ); ?>" style="color: <?php echo sanitize_hex_color( $title_color ); ?> !important;"><?php echo esc_html( $etitle ); ?></a></h3>

						<?php if ( 'list-view' === $e_layout ) { ?>
							<p style="color: <?php echo sanitize_hex_color( $text_color ); ?> !important;">
								<?php echo esc_html( substr( wp_strip_all_tags( get_the_content() ), 0, 250 ) . '..' ); ?>
							</p>
						<?php } ?>

						<div class="view-more" style="color: <?php echo esc_html( $text_color ); ?>;">
							<?php if ( $datetime_tog && '-' !== $e_date ) { ?>
								<div class="date">
									<span class="e_date" style="color: <?php echo sanitize_hex_color( $text_color ); ?> !important;"><strong><?php echo esc_html( $date_str ); ?>:</strong><?php echo esc_html( ' ' . $e_date ); ?></span>
									<?php
									if ( ! empty( $e_starttime ) & ! empty( $e_endtime ) ) {
										$e_starttime = new DateTime( "$e_starttime" );
										$e_endtime   = new DateTime( "$e_endtime" );
										$e_starttime = $e_starttime->format( 'h:i A' );
										$e_endtime   = $e_endtime->format( 'h:i A' );
										?>
										<?php 
										if( empty( $e_end_date ) ) {
											?>
												<span class="e_times" style="color: <?php echo sanitize_hex_color( $text_color ); ?> !important;"><strong><?php echo esc_html( $time_str ); ?>:</strong><?php echo " ". esc_html( $event_time_frame ); ?></span>
											<?php
										}
										?>
									<?php } ?>
								</div>
							<?php } ?>
							<?php if ( $cats_tog || $tags_tog ) { ?>
								<div class="cats-tags">
									<?php
									if ( $cats_tog ) {
										$cats_list = get_the_terms( $eid, 'events_categories' );
										if ( false !== $cats_list ) {
											$cats_list = join( ', ', wp_list_pluck( $cats_list, 'name' ) );
											?>
											<div class="cats-list date"><strong><?php echo esc_html( $cats_str ); ?>: </strong><?php echo esc_html( $cats_list ); ?></div>
										<?php
										}
									}
									if ( $tags_tog ) {
										$tags_list = get_the_terms( $eid, 'events_tags' );
										if ( false !== $tags_list ) {
											$tags_list = join( ', ', wp_list_pluck( $tags_list, 'name' ) );
											?>
											<div class="tags-list date"><strong><?php echo esc_html( $tags_str ); ?>: </strong><?php echo esc_html( $tags_list ); ?></div>
										<?php
										}
									}
									?>
								</div>
							<?php } ?>
							<a class="dff-view-more detail-event" href="<?php echo esc_url( $slug ); ?>" target="<?php echo $open_new_tab_tog ? '_blank' : '_self'; ?>"><?php echo esc_html( $view_detail ); ?></a>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		wp_reset_query();
		?>
		<input id='dff_language' type='hidden' value='<?php echo esc_attr( $language ); ?>'/>
		<input id='checked_tags' type='hidden' value='<?php echo esc_attr( $tags ); ?>'/>
		<input id='checked_cats' type='hidden' value='<?php echo esc_attr( $cats ); ?>'/>
		<input id='total_events' type='hidden' value='<?php echo esc_attr( $total_events ); ?>'/>
		<input id='order_by' type='hidden' value='<?php echo esc_attr( $order_by ); ?>'/>
		<input id='e_layout' type='hidden' value='<?php echo esc_attr( $e_layout ); ?>'/>
		<input id='feature_img_tog' type='hidden' value='<?php echo esc_attr( $feature_image_tog ); ?>'/>
		<input id='pagination_tog' type='hidden' value='<?php echo esc_attr( $pagination_tog ); ?>'/>
		<input id='date_time_tog' type='hidden' value='<?php echo esc_attr( $datetime_tog ); ?>'/>
		<input id='open_new_tab_tog' type='hidden' value='<?php echo esc_attr( $open_new_tab_tog ); ?>'/>
		<input id='title_color' type='hidden' value='<?php echo esc_attr( $title_color ); ?>'/>
		<input id='text_color' type='hidden' value='<?php echo esc_attr( $text_color ); ?>'/>
		<input id='upcoming_event' type='hidden' value='<?php echo esc_attr( $upcoming_event_hidden ); ?>'/>
	</div>
	<?php
	$max_num_pages = (int) $the_query->max_num_pages;
	if ( $pagination_tog && 1 < $max_num_pages ) {

		$pages_left_right = 2;
		$all_count        = (int) $the_query->found_posts;
		$prev_page        = 1 !== $paged_no ? $paged_no - 1 : 1;
		$next_page        = $max_num_pages !== $paged_no ? $paged_no + 1 : $max_num_pages;

		$start = 0 < ( $paged_no - $pages_left_right ) ? $paged_no - $pages_left_right : 1;
		$last  = $max_num_pages > ( $paged_no + $pages_left_right ) ? $paged_no + $pages_left_right : $max_num_pages;

		if ( 1 > ( $paged_no - $pages_left_right ) && $max_num_pages !== $last && $paged_no > $pages_left_right ) {
			$last = $last + ( $paged_no - $pages_left_right );
		}
		if ( $paged_no === $pages_left_right ) {
			$last ++;
		} elseif ( $paged_no < $pages_left_right ) {
			$last = $last + ( $pages_left_right - $paged_no ) + 1;
		}
		if ( $max_num_pages < ( $paged_no + $pages_left_right ) && 1 !== $start ) {
			$start = $start - ( $paged_no + $pages_left_right - $max_num_pages );
		}
		$start              = $start > 0 ? $start : 1;
		$last               = $last > $max_num_pages ? $max_num_pages : $last;
		$display_first_last = $max_num_pages > ( ( $pages_left_right * 2 ) + 1 ) ? 'yes' : 'no';
		?>
		<div class="de-pagination-outer">
			<ul class="de-pagination-ul">
				<?php if ( 'yes' === $display_first_last ) { ?>
					<li class="start prev <?php echo 1 === $paged_no ? 'disabled' : ''; ?>" data-page='1'><span>First</span></li>
				<?php } ?>
				<li class="prev <?php echo 1 === $paged_no ? 'disabled' : ''; ?>" data-page='<?php echo esc_attr( $prev_page ); ?>'><span>Previous</span></li>
				<?php
				for ( $i = $start; $i <= $last; $i ++ ) {
					$class = $i === $paged_no ? 'active' : '';
					if ( $i === $start && 1 !== $start ) {
						echo '...';
					}
					?>
					<li class='<?php echo esc_attr( $class ); ?>' data-page='<?php echo esc_attr( $i ); ?>'><span><?php echo esc_html( $i ); ?></span></li>
					<?php
					if ( $i === $last && $max_num_pages !== $i ) {
						echo '...';
					}
				}
				?>
				<li class="next <?php echo $paged_no === $max_num_pages ? 'disabled' : ''; ?>" data-page='<?php echo esc_attr( $next_page ); ?>'><span>Next</span></li>
				<?php if ( 'yes' === $display_first_last ) { ?>
					<li class="next end <?php echo $paged_no === $max_num_pages ? 'disabled' : ''; ?>" data-page='<?php echo esc_attr( $max_num_pages ); ?>'><span>Last</span></li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}
	if ( 'backend' === $from ) {
	?>
		</div>
	<?php
	}
} else {
?>
	<div class="no-record" style="text-align:center"> No events found.</div>
<?php
}
