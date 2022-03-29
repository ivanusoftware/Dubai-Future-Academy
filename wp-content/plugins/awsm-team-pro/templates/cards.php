<?php
/**
 * Cards Preset Template.
 *
 * Override this by copying it to currenttheme/awsm-team-pro/cards.php
 *
 * @package awsm-team-pro
 */

$flip      = false;
$flipclass = array( 'awsm-figcaption' );
if ( in_array( $options['preset'], array( 'style-2' ), true ) ) {
	$flip      = true;
	$flipclass = array( 'awsm-flip-back' );
}
?>
<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-team', $id ) ) ); ?>" class="awsm-grid-wrapper">
	<?php echo $this->show_member_search( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php echo $this->show_team_filter( $team, $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<?php if ( $team->have_posts() ) : ?>
		<div class="awsm-grid <?php echo esc_attr( $this->item_style( $options ) ); ?>">
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

			$personal_info = sprintf( '<div class="awsm-personal-info"><h3>%1$s</h3><span>%2$s</span></div>', get_the_title(), wp_kses( $teamdata['awsm-team-designation'], 'post' ) );
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
				<div id="<?php echo esc_attr( $this->add_id( array( 'awsm-member', $id, $team->post->ID ) ) ); ?>" class="awsm-grid-card awsm-team-item awsm-scale-anm <?php echo esc_attr( $member_terms ); ?>">
				   <figure>
					<?php $this->checkprint( '<div class="awsm-flip-front">', $flip ); ?>
					 <?php echo $this->get_team_thumbnail( $team->post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					if ( $flip ) {
						echo $personal_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
					<?php $this->checkprint( '</div>', $flip ); ?>
						 <figcaption class="<?php echo esc_attr( $this->addclass( $flipclass ) ); ?>">
							<?php
								$this->checkprint( '<div class="awsm-flip-back-inner">', $flip );

								echo $personal_info; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>

							<div class="awsm-contact-info">
								<?php
								$this->checkprint( '<p>%s</p>', wp_kses( $teamdata['awsm-team-short-desc'], 'post' ), false, 'awsm-team-short-description-' . $options['team-style'] . '-' . $options['preset'] );
								include $this->get_template_path( 'social.php', 'partials' );
								?>
							</div>
							<?php $this->checkprint( '</div>', $flip ); ?>
						 </figcaption>
				   </figure>
				</div>
			<?php
			endwhile;
		wp_reset_postdata();

		echo $this->get_search_no_results_content( $id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
		</div>
	<?php endif; ?>
</div>
