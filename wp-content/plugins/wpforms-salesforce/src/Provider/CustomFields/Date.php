<?php

namespace WPFormsSalesforce\Provider\CustomFields;

/**
 * Date field.
 *
 * @since 1.0.0
 */
class Date extends Base {

	/**
	 * ISO 8601 date format.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const FORMAT = 'c';

	/**
	 * Retrieve a field value for delivery to Salesforce.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function value() {

		$field_id = $this->wpf_field_id;

		// Apply a special formatting for `Date / Time` WPForms field.
		if ( ! empty( $this->fields[ $field_id ]['type'] ) && 'date-time' === $this->fields[ $field_id ]['type'] ) {
			return $this->format_datetime();
		}

		// Also the date value can pass from any other WPForms field (e.g. Single Line).
		// We try to convert this string to a date value.
		$value     = '';
		$date_time = date_create( $this->fields[ $field_id ]['value'], $this->get_timezone() );
		if ( $date_time ) {
			$value = $date_time->format( self::FORMAT );
		}

		return $value;
	}

	/**
	 * Special formatting for `Date / Time` WPForms field.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function format_datetime() {

		$field_id = $this->wpf_field_id;
		$result   = '';

		// Skip for `time` format.
		if ( 'time' === $this->form_data['fields'][ $field_id ]['format'] ) {
			return $result;
		}

		$field_submit = $this->fields[ $field_id ];
		$date_format  = $this->get_field_format();
		$date_time    = false;

		// Try to parse a value with date string.
		if ( ! empty( $date_format ) ) {
			$date_time = date_create_from_format( $date_format, $field_submit['value'], $this->get_timezone() );
		}

		// Fallback with using timestamp value.
		if ( ! $date_time && ! empty( $field_submit['unix'] ) ) {
			$date_time = date_create( '@' . $field_submit['unix'], $this->get_timezone() );
		}

		// If has a DateTime object - return a date formatted according to expected format.
		if ( $date_time ) {
			$result = $date_time->format( self::FORMAT );
		}

		return $result;
	}

	/**
	 * Retrieve a format for submitted field.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	protected function get_field_format() {

		$field_data   = $this->form_data['fields'][ $this->wpf_field_id ];
		$field_submit = $this->fields[ $this->wpf_field_id ];
		$format       = '';

		if ( ! empty( $field_data['date_format'] ) ) {
			$format = $field_data['date_format'];
		}

		if (
			'date-time' === $field_data['format'] &&
			! empty( $field_data['time_format'] ) &&
			! empty( $field_submit['time'] )
		) {
			$format .= ' ' . $field_data['time_format'];
		}

		return trim( $format );
	}

	/**
	 * Retrieve the timezone from site settings as a `DateTimeZone` object.
	 *
	 * Timezone can be based on a PHP timezone string or a ±HH:MM offset.
	 *
	 * @since 1.0.0
	 *
	 * @return \DateTimeZone Timezone object.
	 */
	protected function get_timezone() {

		if ( function_exists( 'wp_timezone' ) ) {
			return wp_timezone();
		}

		// Fallback for WordPress version < 5.3.
		$timezone_string = get_option( 'timezone_string' );

		if ( ! $timezone_string ) {
			$offset  = (float) get_option( 'gmt_offset' );
			$hours   = (int) $offset;
			$minutes = ( $offset - $hours );

			$sign     = ( $offset < 0 ) ? '-' : '+';
			$abs_hour = abs( $hours );
			$abs_mins = abs( $minutes * 60 );

			$timezone_string = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );
		}

		return timezone_open( $timezone_string );
	}
}
