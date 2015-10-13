<?php

/**
 * Some Global functions to make life easier
 *
 * @since 0.1.0
 */

/**
 * Determine the amount of days in a month for a given month
 *
 * @param int|string $month
 * @param int|string $year
 * @return string
 * @since 0.1.0
 */
function wcars_days_in_month( $month = '', $year = '' ) {
	if ( is_numeric( $month ) && is_numeric( $year ) ) {
		$month = (int) $month;
		$year = (int) $year;

		return date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
	} else {
		return date( 't' );
	}
}