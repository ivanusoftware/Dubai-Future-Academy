<?php

class Publisher_Rest_Endpoints {

	/**
	 * Class Constructor
	 */
	public function __construct() {

		/*
		 * Initialize the Rest End Point
		 */
		add_action( 'rest_api_init', array( $this, 'npm_rest_points' ) );
	}

	/**
	 * WP Rest End Points for Events.
	 *
	 * @since 1.0.0
	 */
	public function npm_rest_points() {

		/**
		 * Verify the Token & Domain
		 * wp-json/publisher/token
		 */
		register_rest_route(
			'publisher', '/token', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'publisher_token_verify_api' ),
			)
		);

		/**
		 * Get Publisher Categories.
		 * wp-json/publisher/cats
		 */
		register_rest_route(
			'publisher', '/cats', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'publisher_get_cats' ),
			)
		);

		/**
		 * Get Publisher Categories.
		 * wp-json/publisher/tags
		 */
		register_rest_route(
			'publisher', '/tags', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'publisher_get_tags' ),
			)
		);

		/**
		 * Get Single Publisher by ID.
		 * wp-json/publisher/single
		 */
		register_rest_route(
			'publisher', 'single', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'publisher_get_single_post' ),
			)
		);

		/**
		 * Get Events.
		 * wp-json/publisher/
		 */
		register_rest_route(
			'publisher', 'pull', array(
				'methods'  => 'GET',
				'callback' => array( $this, 'publisher_get_posts' ),
			)
		);

	}

	/**
	 * Call back for Token Verify API.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool Verified or not.
	 */
	public function publisher_token_verify_api( WP_REST_Request $request ) {

		$parameters = $request->get_params();

		$token  = isset( $parameters['key'] ) ? $parameters['key'] : '';
		$domain = isset( $parameters['domain'] ) ? $parameters['domain'] : '';

		$verified = $this->npm_token_verify( $token, $domain );

		return $verified;
	}

	/**
	 * Verify the Token & Domain.
	 *
	 * @return bool Verified or not.
	 */
	public function npm_token_verify( $token, $domain ) {

		// Guilty until proven.
		$verified = false;
		global $wpdb;
		$token_added_sites = $wpdb->get_results( $wpdb->prepare( "SELECT option_value FROM %1soptions WHERE option_name = 'npm_child_sites'", $wpdb->base_prefix ), ARRAY_A );
		$token_added_sites = explode( '/', $token_added_sites[0]['option_value'] );
		if ( isset( $token_added_sites ) && ! empty( $token_added_sites ) ) {
			foreach ( $token_added_sites as $token_added_sites ) {
				$token_added_sites = maybe_unserialize( $token_added_sites );
				if ( $token_added_sites[0]['token'] === $token
					 && strpos( $domain, $token_added_sites[0]['siteurl'] ) !== false ) {
					$verified = true;
				}
			}
		}

		return $verified;
	}

	/**
	 * Call back to Get Event Categories.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array Categories IDs and Names.
	 */
	public function publisher_get_cats( WP_REST_Request $request ) {

		// Guilty until proven.
		$cats_data           = array();
		$cats_data['status'] = 401;

		$parameters = $request->get_params();

		$domain = isset( $parameters['domain'] ) ? $parameters['domain'] : '';
		$key    = isset( $parameters['key'] ) ? $parameters['key'] : '';

		$verified = $this->npm_token_verify( $key, $domain );
		if ( $verified ) {

			$option_key = 'nmp_english_category';

			$categories = get_option( $option_key );

			if( ! empty( $categories ) ) {
			    $categories = explode( ',', $categories );

                $cats_data['status']     = 200;
                $cats_data['total_cats'] = count( $categories );

                foreach ( $categories as $categories_ids ) {
                    $cat                                        = get_category( $categories_ids );
                    $cats_data['data'][ $cat->term_id ]['name'] = $cat->name;
                    $cats_data['data'][ $cat->term_id ]['description'] = $cat->description;
                    $cats_data['data'][ $cat->term_id ]['parent']      = $cat->parent;
                }
            } else {
                $cats_data['message'] = 'No categories allowed.';
            }
		}

		return $cats_data;

	}

	/**
	 * Call back to Get Event Tags.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array Tags IDs and Names.
	 */
	public function publisher_get_tags( WP_REST_Request $request ) {

		// Guilty until proven.
		$tags_data           = array();
		$tags_data['status'] = 401;

		$parameters = $request->get_params();

		$domain = isset( $parameters['domain'] ) ? $parameters['domain'] : '';
		$key    = isset( $parameters['key'] ) ? $parameters['key'] : '';

		$verified = $this->npm_token_verify( $key, $domain );
		if ( $verified ) {

			$option_key = 'nmp_english_tags';

			$tags = get_option( $option_key );

			if( ! empty( $tags ) ) {
                $tags = explode( ',', $tags );
                $tags_data['status']     = 200;
                $tags_data['total_tags'] = count( $tags );

                foreach ( $tags as $tags_ids ) {
                    $cat                                        = get_tag( $tags_ids );
                    $tags_data['data'][ $cat->term_id ]['name'] = $cat->name;
                    $tags_data['data'][ $cat->term_id ]['description'] = $cat->description;
                    $tags_data['data'][ $cat->term_id ]['parent']      = $cat->parent;
                }
            } else {
                $tags_data['message'] = 'No tags allowed.';
            }
		}

		return $tags_data;

	}

	/**
	 * Call back tp pull Single Post.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array Single Events Data.
	 */
	public function publisher_get_single_post( WP_REST_Request $request ) {

		$parameters = $request->get_params();

		$eid    = isset( $parameters['id'] ) ? $parameters['id'] : '';
		$token  = isset( $parameters['key'] ) ? $parameters['key'] : '';
		$domain = isset( $parameters['domain'] ) ? $parameters['domain'] : '';

		$data_missing = empty( $eid ) ? true : false;

		$verified = $this->npm_token_verify( $token, $domain );

		// Guilty until proven.
		$post_data           = array();
		$post_data['status'] = 401;

		if ( $verified && false === $data_missing ) {
			$single_post_data = get_post( $eid );

			if ( isset( $single_post_data ) && ! empty( $single_post_data ) ) {
				$post_data['status']                 = 200;
				$post_data['data']['id']             = $single_post_data->ID;
				$post_data['data']['post_title']     = $single_post_data->post_title;
				$post_data['data']['post_status']    = $single_post_data->post_status;
				$post_data['data']['post_parent']    = $single_post_data->post_parent;
				$post_data['data']['post_author']    = $single_post_data->post_author;
				$post_data['data']['post_content']   = $single_post_data->post_content;
				$post_data['data']['post_excerpt']   = $single_post_data->post_excerpt;
				$post_data['data']['featured_photo'] = get_the_post_thumbnail_url( $single_post_data->ID, 'full' );
			}
		}

		return $post_data;

	}

	/**
	 * Call back tp pull Posts.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array Events Data.
	 */
	public function publisher_get_posts( WP_REST_Request $request ) {

		$data_missing = false;

		$parameters = $request->get_params();

		$token        = isset( $parameters['key'] ) ? $parameters['key'] : '';
		$domain       = isset( $parameters['domain'] ) ? $parameters['domain'] : '';
		$child_eids   = isset( $parameters['pids'] ) ? explode( ',', $parameters['pids'] ) : '';
		$child_cats   = isset( $parameters['cats'] ) ? explode( ',', $parameters['cats'] ) : 'all';
		$child_tags   = isset( $parameters['tags'] ) ? explode( ',', $parameters['tags'] ) : 'all';
		$no_of_events = isset( $parameters['total'] ) && (int) $parameters['total'] > - 1 ? (int) $parameters['total'] : '';
		$cat_tax      = 'category';
		$tag_tax      = 'post_tag';

		// If this is not the first attempt.
		// There must be from and to dates in the request.
		if ( empty( $no_of_events ) && 0 !== $no_of_events ) {
			// Dates should be in YYYY-MM-DD HH:MM:SS format.
			if ( isset( $parameters['fromDate'] ) ) {
				$separated_after_timestamp = $this->dff_get_separated_timestamp( $parameters['fromDate'] );
			} else {
				$data_missing = true;
			}

			if ( isset( $parameters['toDate'] ) ) {
				$separated_before_timestamp = $this->dff_get_separated_timestamp( $parameters['toDate'] );
			} else {
				$separated_before_timestamp = $this->dff_get_separated_timestamp( current_time( 'm-d-Y H:i:s' ) );
			}
		}

		$verified = $this->npm_token_verify( $token, $domain );

		// Guilty until proven.
		$events_data           = array();
		$events_data['status'] = 401;

        $allowed_cats_string = get_option( 'nmp_english_category' );
        $allowed_tags_string  = get_option( 'nmp_english_tags' );

        $allowed_cats = explode( ',', $allowed_cats_string );
        $allowed_tags  = explode( ',', $allowed_tags_string );

        if( 'all' == $child_cats ) {
            $child_cats = $allowed_cats;
        }
        if( 'all' == $child_tags ) {
            $child_tags = $allowed_tags;
        }

        if( empty( $allowed_cats_string ) && empty( $allowed_tags_string ) ) {
            $events_data['message'] = 'No categories and tags are allowed, please contact plugin administrator.';
        }
        else if ( 'all' !== $child_cats && empty( array_intersect( $child_cats, $allowed_cats ) ) ) {
            $events_data['message'] = 'The supplied categories are not allowed.';
        }
        else if ( 'all' !== $child_tags && empty( array_intersect( $child_tags, $allowed_tags ) ) ) {
            $events_data['message'] = 'The supplied tags are not allowed.';
        }
        else if ( true === $data_missing ) {
            $events_data['message'] = 'The fromDate is missing.';
        }
        else if ( ! $verified ) {
            $events_data['message'] = 'The token or domain is incorrect.';
        }
        else {
			$args = array(
				'post_type'   => 'post',
				'post_status' => array( 'publish', 'trash' ),
			);

			// No of posts
			if ( ! empty( $no_of_events ) || 0 === $no_of_events ) {
				$no_of_posts            = 0 === $no_of_events ? - 1 : $no_of_events;
				$args['posts_per_page'] = $no_of_posts;
				$args['orderby']        = 'modified';
				$args['order']          = 'DESC';
			}

			// tax query
			$args['tax_query'] = array( 'relation' => 'OR' );

			if ( 'all' !== $child_cats ) {
				$args['tax_query'][] = array(
					'taxonomy'         => $cat_tax,
					'field'            => 'term_id',
					'include_children' => false,
					'terms'            => $child_cats,
				);
			}

			if ( 'all' !== $child_tags ) {
				$args['tax_query'][] = array(
					'taxonomy' => $tag_tax,
					'field'    => 'term_id',
					'terms'    => $child_tags,
				);
			}

			// date query
			if ( empty( $no_of_events ) && 0 !== $no_of_events ) {
				$args['date_query'] = array();

				$args['date_query'][] = array(
					'column' => 'post_modified_gmt',
					array(
						'after'     => array(
							'year'  => $separated_after_timestamp['year'],
							'month' => $separated_after_timestamp['month'],
							'day'   => $separated_after_timestamp['day'],
						),
						'before'    => array(
							'year'  => $separated_before_timestamp['year'],
							'month' => $separated_before_timestamp['month'],
							'day'   => $separated_before_timestamp['day'],
						),
						'inclusive' => true,
					),
					array(
						'hour'    => $separated_before_timestamp['hour'],
						'minute'  => $separated_before_timestamp['min'],
						'second'  => $separated_before_timestamp['sec'],
						'compare' => '<=',
					),
					array(
						'hour'    => $separated_after_timestamp['hour'],
						'minute'  => $separated_after_timestamp['min'],
						'second'  => $separated_after_timestamp['sec'],
						'compare' => '>=',
					),

				);
			}
			$the_query = new WP_Query( $args );

			if ( $the_query->have_posts() ) {
				$the_query->posts;

				// Status Success.
				if ( isset( $the_query->posts ) ) {
					$events_data['status'] = 200;
				}

				$e_counts = 0;
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$eid         = get_the_ID();
					$post_status = get_post_status();

					// Fetch Categories.
					$cats        = get_the_terms( $eid, $cat_tax );
					$parent_cats = array();
					if ( ! empty( $cats ) && false !== $cats ) {
						foreach ( $cats as $cat ) {
							$events_data['data'][ $e_counts ]['cats'][ $cat->term_id ]['name']   = $cat->name;
							$events_data['data'][ $e_counts ]['cats'][ $cat->term_id ]['parent'] = $cat->parent;
							$parent_cats[] = $cat->term_id;
						}
					}

					// Fetch Tags.
					$tags        = get_the_terms( $eid, $tag_tax );
					$parent_tags = array();
					if ( ! empty( $tags ) && false !== $tags ) {
						foreach ( $tags as $tag ) {
							$events_data['data'][ $e_counts ]['tags'][ $tag->term_id ]['name']   = $tag->name;
							$events_data['data'][ $e_counts ]['tags'][ $tag->term_id ]['parent'] = $tag->parent;
							$parent_tags[] = $tag->term_id;
						}
					}

					// Status of the post. (Added/Modified/Deleted)
					if ( 'publish' === $post_status ) {
						$status = 'modified';
						if ( isset( $parameters['fromDate'] ) ) {
							$created_on = get_the_date( 'Y-m-d H:i:s' );
							if ( $parameters['fromDate'] <= $created_on ) {
								$status = 'added';
							}
						}
					} else {
						$status = 'deleted';
					}

					// Filter events if $child_eids is not blank.
					if ( ! empty( $child_eids ) ) {
						// By default, the event is not eligible to be sent back.
						$eligible = 0;

						/**
						 * Events do not exist is child site and trashed from parent,
						 * (OR) Parent Events with 0 cats or tags are also not eligible.
						 */
						if ( ( ! in_array( (string) $eid, $child_eids, true ) && 'deleted' === $status )
							 || ( 0 === count( $parent_cats ) && 0 === count( $parent_tags ) && 'deleted' !== $status )
						) {
							continue;

							/*
							 * If event exist in child site, and its status ar parent site
							 * is "deleted", event is eligible to proceed.
							 */
						} elseif ( in_array( (string) $eid, $child_eids, true ) && 'deleted' === $status ) {
							$status = 'deleted';

						} else {

							/*
							 * Match cats & tags of Parent Event with the requested $cats & $tags,
							 * If even a single tag/cat matches with requests, prepare data to "update"
							 */

							if ( 'all' !== $child_cats && 0 !== count( $child_cats ) && 0 !== count( $parent_cats ) ) {
								$intersected_cats = array_intersect( $parent_cats, $child_cats );
								if ( 0 !== count( $intersected_cats ) ) {
									$eligible = 1;
								}
							} elseif ( 'all' === $child_cats ) {
								// If all cats are requested, then all new events should be sent back.
								$eligible = 1;
							}

							// Try to find matched tags.
							if ( 'all' !== $child_tags && 0 !== count( $child_tags ) && 0 !== count( $parent_tags ) ) {
								$intersected_tags = array_intersect( $parent_tags, $child_tags );
								if ( 0 !== count( $intersected_tags ) ) {
									$eligible = 1;
								}
							} elseif ( 'all' === $child_tags ) {
								// If all tags are requested, then all new events should be sent back.
								$eligible = 1;
							}

							/*
							 * If there is nothing matching, it means the cats/tags are removed,
							 * then prepare data to "delete"
							 */
							if ( in_array( (string) $eid, $child_eids, true ) && 0 === $eligible ) {
								$status = 'deleted';
							}
						}
					}

					if ( 'deleted' === $status ) {
						// Only two values to pass if 'deleted'.
						$events_data['data'][ $e_counts ]['eid']    = $eid;
						$events_data['data'][ $e_counts ]['status'] = $status;
						$e_counts ++;
					} else {
						$post = get_post( $eid );

						if ( isset( $post->post_title ) && ! empty( $post->post_title ) ) {

							$events_data['data'][ $e_counts ]['eid']            = $eid;
							$events_data['data'][ $e_counts ]['status']         = $status;
							$events_data['data'][ $e_counts ]['post_title']     = $post->post_title;
							$events_data['data'][ $e_counts ]['post_date']      = $post->post_date;
							$events_data['data'][ $e_counts ]['post_status']    = $post->post_status;
							$events_data['data'][ $e_counts ]['post_parent']    = $post->post_parent;
							$events_data['data'][ $e_counts ]['post_author']    = $post->post_author;
							$events_data['data'][ $e_counts ]['post_content']   = $post->post_content;
							$events_data['data'][ $e_counts ]['post_excerpt']   = $post->post_excerpt;
							$events_data['data'][ $e_counts ]['featured_photo'] = get_the_post_thumbnail_url( $eid, 'full' );
							// Increment.
							$e_counts ++;

						}
					}
					$events_data['total_events'] = $e_counts;

				}
			} else {
				$events_data['status'] = 200;
                $events_data['message'] = 'No Publisher added or updated';
			}
			// Restore original Post Data
			wp_reset_postdata();
		}

		return $events_data;

	}

	/**
	 * Date & Time Separator.
	 *
	 * @param string $timestamp The timestamp 'MM-DD-YYYY HH:MM:SS'.
	 *
	 * @return array Separated date and time values.
	 */
	public function dff_get_separated_timestamp( $timestamp ) {

		$separated = array();

		$date_ex = explode( ' ', $timestamp );

		$date_ex_inner      = $date_ex[0];
		$date_ex_inner      = explode( '-', $date_ex_inner );
		$separated['year']  = $date_ex_inner[0];
		$separated['month'] = $date_ex_inner[1];
		$separated['day']   = $date_ex_inner[2];

		$time_ex_inner     = isset( $date_ex[1] ) ? $date_ex[1] : '00:00:00';
		$time_ex_inner     = explode( ':', $time_ex_inner );
		$separated['hour'] = $time_ex_inner[0];
		$separated['min']  = $time_ex_inner[1];
		$separated['sec']  = $time_ex_inner[2];

		return $separated;

	}

}

new Publisher_Rest_Endpoints();
