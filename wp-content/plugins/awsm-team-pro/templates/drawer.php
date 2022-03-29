<?php
/**
 * Drawer Preset Template.
 *
 * Override this by copying it to currenttheme/awsm-team-pro/drawer.php
 *
 * @package awsm-team-pro
 */

?>
<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-team', $id ) ) ); ?>" class="awsm-grid-wrapper">
	<?php echo $this->show_member_search( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo $this->show_team_filter( $team, $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php if ( $team->have_posts() ) : ?>
	<div class=''>
		<div class="gridder awsm-grid <?php echo esc_attr( $this->item_style( $options ) ); ?>">
		<?php
		while ( $team->have_posts() ) :
			$team->the_post();
			$teamdata     = $this->get_options( 'awsm_team_member', $team->post->ID );
			$member_terms = 'awsm-all';
			$terms        = get_the_terms( $team->post->ID, 'awsm_team_filters' );
			if ( ! empty( $terms ) ) {
				foreach ( $terms as $member_term ) {
					$member_terms .= ' awsm-' . str_replace( ' ', '-', $member_term->term_id );
				}
			}

			$personal_info = sprintf( '<div class="awsm-personal-info"><span>%2$s</span><h3>%1$s</h3></div>', get_the_title(), wp_kses( $teamdata['awsm-team-designation'], 'post' ) );
			/**
			 * Filters the member personal info content.
			 *
			 * @since 1.10.0
			 *
			 * @param string $personal_info The HTML content.
			 * @param array $teamdata Current member data array.
			 * @param mixed $team Team members object.
			 * @param int $id The Team Post ID.
			 */
			$personal_info = apply_filters( 'awsm_team_member_personal_info', $personal_info, $teamdata, $team, $id );
			?>
				<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member', $id, $team->post->ID ) ) ); ?>" class="awsm-grid-list awsm-grid-card awsm-team-item awsm-scale-anm <?php echo esc_attr( $member_terms ); ?>" data-griddercontent="#awsm-grid-content-<?php echo esc_attr( $team->post->ID ); ?>">
					<span class="awsm-team-link-control awsm-grid-list-item awsm-grid-list-item-<?php echo esc_attr( $team->post->ID ); ?>"<?php Awsm_Team::deep_link_attr( $team->post->ID ); ?>>
						<figure>
						<?php echo $this->get_team_thumbnail( $team->post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<figcaption>
								<?php echo $personal_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</figcaption>
						</figure>
					</span>
				</div>
			<?php
			endwhile;
		wp_reset_postdata();

		echo $this->get_search_no_results_content( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		</div>
		<div class="awsm-grid-expander style-1">
			<?php
			while ( $team->have_posts() ) :
				$team->the_post();
				$teamdata = $this->get_options( 'awsm_team_member', $team->post->ID );
				?>
			<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member-info', $id, $team->post->ID ) ) ); ?>"  class="awsm-grid-expander <?php echo esc_attr( $options['preset'] ); ?>">
				<div class="awsm-detailed-info" id="awsm-grid-content-<?php echo esc_attr( $team->post->ID ); ?>">
					<div class="awsm-details">
						<div class="awsm-personal-details">
							<div class="awsm-content-scrollbar">
								<?php
								$expanded_personal_info = sprintf( '<span>%2$s</span><h2>%1$s</h2>', get_the_title(), wp_kses( $teamdata['awsm-team-designation'], 'post' ) );
								/**
								 * Filters the member expanded personal info content.
								 *
								 * @since 1.10.0
								 *
								 * @param string $expanded_personal_info The HTML content.
								 * @param array $teamdata Current member data array.
								 * @param mixed $team Team members object.
								 * @param int $id The Team Post ID.
								 */
								$expanded_personal_info = apply_filters( 'awsm_team_member_detail_personal_info', $expanded_personal_info, $teamdata, $team, $id );

								echo $expanded_personal_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

								Awsm_Team::the_content( $filter_content, $team->post );
								?>
							</div>
						</div>
					</div>
					<div class="awsm-personal-contact-info">
					   <?php
						include $this->get_template_path( 'contact.php', 'partials' );
						include $this->get_template_path( 'social.php', 'partials' );
						?>
					</div>
				</div>
			</div>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php endif; ?>
</div>
