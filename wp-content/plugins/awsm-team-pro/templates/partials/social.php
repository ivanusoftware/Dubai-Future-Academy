<?php
/**
 * Social Info Template Part.
 *
 * @package awsm-team-pro
 */

if ( ! empty( $teamdata['awsm_social'] ) ) {
	echo '<div class="awsm-social-icons">';
	foreach ( $teamdata['awsm_social'] as $social ) {
		$social_link = '';
		if ( isset( $social['link'] ) ) {
			if ( filter_var( $social['link'], FILTER_VALIDATE_EMAIL ) ) {
				$social_link = sprintf( 'href="mailto:%1$s"', esc_attr( $social['link'] ) );
			} elseif ( $this->validate_phone_number( $social['link'] ) === true ) {
				$social_link = sprintf( 'href="tel:%1$s"', esc_attr( $social['link'] ) );
			} else {
				$social_link = sprintf( 'href="%1$s" target="_blank"', esc_url( $social['link'] ) );
			}
		}
		$social_info = '';
		if ( isset( $social['icon'] ) ) {
			$social_info = '<span><a ' . $social_link . '><i class="awsm-icon-' . esc_attr( $social['icon'] ) . '" aria-hidden="true"></i></a></span>';
		}
		/**
		 * Filters the member social info content.
		 *
		 * @since 1.10.0
		 *
		 * @param string $social_info The HTML content.
		 * @param array $social Current info array.
		 * @param mixed $team The Team object.
		 */
		echo apply_filters( 'awsm_team_member_social_info', $social_info, $social, $team ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</div>';
}

