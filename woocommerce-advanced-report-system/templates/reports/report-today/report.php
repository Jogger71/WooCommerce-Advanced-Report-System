<?php

/**
 * Today's Report
 *
 * @since 0.1.0
 */

$today = getdate();

$report_args = array(
	'order_statuses' => false,
	'products' => false,
	'product_types' => false,
	'start_date' => array(
		'year' => $today[ 'year' ],
		'month' => $today[ 'mon' ],
		'day' => 1
	),
	'end_date' => array(
		'year' => $today[ 'year' ],
		'month' => $today[ 'mon' ],
		'day' => $today[ 'mday' ],
	)
);

$last_month_args = array(
	'order_statuses' => false,
	'products' => false,
	'product_types' => false,
	'start_date' => array(
		'year' => $today[ 'year' ],
		'month' => $today[ 'mon' ] - 1,
		'day' => 1
	),
	'end_date' => array(
		'year' => $today[ 'year' ],
		'month' => $today[ 'mon' ] - 1,
		'day' => $today[ 'mday' ],
	)
);

$report = new WCARS_Report( $report_args );
$report_last_month = new WCARS_Report( $last_month_args );
$report->create_report_array();
$report_last_month->create_report_array();

$report_array = $report->report_array;
$last_month = $report_last_month->report_array;

$ui = new UI( $report_array );

?>

<div class="wrap wcars-report-ui">
	<h2>Today's Information</h2>

	<?php $ui->generate_ui( $report_array ); ?>
	<h3>Report Date: <?php echo $report_array[ 'report_info' ][ 'report_date' ]; ?></h3>

	<?php
	$ui->generate_global_order_status_block( $report_array );
	$ui->generate_order_status_block( $report_array, 'on-hold' );
	$ui->generate_order_status_block( $last_month, 'completed', 'Last Month' );
	$ui->generate_order_status_block( $report_array, 'completed' );

	echo '<br class="clear" />';

	$ui->generate_stock_report( $last_month );
	$ui->generate_stock_report( $report_array );
	?>

</div>
