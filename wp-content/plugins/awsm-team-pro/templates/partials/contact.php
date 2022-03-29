<?php
/**
 * Contact Info Template Part.
 *
 * @package awsm-team-pro
 */

if ( ! empty( $teamdata['awsm_contact'] ) ) {
	echo '<div class="awsm-contact-details">';
	foreach ( $teamdata['awsm_contact'] as $contact ) {
		if ( isset( $contact['content'] ) ) {
			if ( filter_var( $contact['content'], FILTER_VALIDATE_EMAIL ) ) {
				$contact['content'] = sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $contact['content'] ) );
			} elseif ( $this->validate_phone_number( $contact['content'] ) === true ) {
				$contact['content'] = sprintf( '<a href="tel:%1$s">%1$s</a>', esc_attr( $contact['content'] ) );
			}
			$contact_content = '<p><span>' . esc_html( $contact['label'] ) . ':</span>' . wp_kses( $contact['content'], 'post' ) . '</p>';
			/**
			 * Filters the member contact info content.
			 *
			 * @since 1.10.0
			 *
			 * @param string $contact_content The HTML content.
			 * @param array $contact Current info array.
			 * @param mixed $team The Team object.
			 */
			echo apply_filters( 'awsm_team_member_contact_info', $contact_content, $contact, $team ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
	echo '</div>';
}

