<?php
/**
 * Modal Popup Template Part.
 *
 * @package awsm-team-pro
 */

?>
<div id="<?php echo esc_attr( $this->add_id( array( 'modal-style', $id, $team->post->ID ) ) ); ?>" class="awsm-modal-item awsm-scale-anm awsm-modal-hidden <?php echo esc_attr( $member_terms ); ?> " data-info="<?php echo esc_attr( $team->post->ID ); ?>">
	<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member-info', $id, $team->post->ID ) ) ); ?>" class="awsm-modal-content">
		<div class="awsm-modal-content-main">
			<div class="awsm-image-main">
				<?php echo $this->get_team_thumbnail( $team->post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="awsm-modal-details">
				<div class="awsm-modal-content-inner">
					<?php
					$expanded_personal_info = sprintf( '<h3>%2$s</h3><h2>%1$s</h2>', get_the_title(), wp_kses( $teamdata['awsm-team-designation'], 'post' ) );
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

					require $this->get_template_path( 'contact.php', 'partials' );
					require $this->get_template_path( 'social.php', 'partials' );
					?>
				</div>
			</div>
		</div>
	</div>
</div>
