<?php

/**
 * WooCommerce Advanced Report System UI class
 * @since 0.1.0
 */

if (!defined('ABSPATH')) {
    exit('Cheaters Detected!');
}

if (!class_exists('UI')) {
    class UI
    {

        /**
         * Class Constructor
         * @param array $info
         */
        public function __construct($info)
        {
        }

        /**
         * Generate the UI Table
         *
         * @param array $report
         */
        public function generate_ui($report)
        {
            $this->generate_report_info($report['report_info']);
        }

        /**
         * Generate Report info block
         *
         * @param array $report_info
         * @since 0.1.0
         */
        public function generate_report_info($report_info)
        {
            echo '<table class="report-section report-info">';
            echo '<thead>';
            echo '<tr><th colspan="2">Report Information</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Report Date</h3></td>';
            printf('<td class="value">%s</td>', $report_info['report_date']);
            echo '<td class="label"><h3 class="heading-label">Report From</h3></td>';
            printf('<td class="value">%s</td>', $report_info['report_from']);
            echo '<td class="label"><h3 class="heading-label">Report To</h3></td>';
            printf('<td class="value">%s</td>', $report_info['report_to']);
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }

        /**
         * Generate global order status block
         *
         * @param array $report
         * @since 0.1.0
         */
        public function generate_global_order_status_block($report)
        {
            $order_status_info = $report['global_totals'];

            echo '<table class="report-section order-status all-orders">';
            echo '<thead>';
            echo '<tr><th colspan="2">All Orders</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Total Orders</h3></td>';
            printf('<td class="value">%s</td>', empty($order_status_info['total_orders']) ? 0 : $order_status_info['total_orders']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Sub Total</h3></td>';
            printf('<td class="value">R %s</td>', empty($order_status_info['orders_sub_total']) ? 0.00 : $order_status_info['orders_sub_total']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Total</h3></td>';
            printf('<td class="value">R %s</td>', empty($order_status_info['orders_total']) ? 0.00 : $order_status_info['orders_total']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Total Discount</h3></td>';
            printf('<td class="value">R %s</td>', empty($order_status_info['coupons_total']) ? 0.00 : $order_status_info['coupons_total']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Products Sold</h3></td>';
            printf('<td class="value">%s</td>', empty($order_status_info['products_sold']) ? 0 : $order_status_info['products_sold']);
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }

        /**
         * Generate order status block
         *
         * @param array $report
         * @param string $order_status
         * @param bool|string $title_override
         * @since 0.1.0
         */
        public function generate_order_status_block($report, $order_status, $title_override = false)
        {
            $order_status_info = $report['order_statuses'][$order_status];

            printf('<table class="report-section order-status %s">', $order_status);
            echo '<thead>';
            printf('<tr><th colspan="2">%s</th></tr>', $title_override ? $title_override : ucfirst($order_status) . ' Orders');
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Total Orders</h3></td>';
            printf('<td class="value">%s</td>', empty($order_status_info['total_orders']) ? 0 : $order_status_info['total_orders']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Sub Total</h3></td>';
            printf('<td class="value">R %s</td>', empty($order_status_info['orders_sub_total']) ? 0.00 : $order_status_info['orders_sub_total']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Total</h3></td>';
            printf('<td class="value">R %s</td>', empty($order_status_info['orders_total']) ? 0.00 : $order_status_info['orders_total']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Total Discount</h3></td>';
            printf('<td class="value">R %s</td>', empty($order_status_info['coupons_total']) ? 0.00 : $order_status_info['coupons_total']);
            echo '</tr>';
            echo '<tr>';
            echo '<td class="label"><h3 class="heading-label">Products Sold</h3></td>';
            printf('<td class="value">%s</td>', empty($order_status_info['products_sold']) ? 0 : $order_status_info['products_sold']);
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }

        /**
         * Generate completed sales report
         *
         * @param array $report
         * @since 0.1.0
         */
        public function generate_stock_report($report)
        {
            if (!empty($report['order_statuses']['completed']['years'])) {
                foreach ($report['order_statuses']['completed']['years'] as $year => $values) {
                    $this->generate_year($year, $report);
                }
            } else {
                echo '<h3>No completed orders for period.</h3>';
            }
        }

        /**
         * Generate a year
         *
         * @param int $year
         * @param array $report
         * @since 0.1.0
         */
        public function generate_year($year, $report)
        {
            echo '<table class="year">';
            echo '<thead>';
            echo '<tr>';
            printf('<th colspan="6">%s</th>', $year);
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<td>Year Orders Total:</td>';
            printf('<td>%s</td>', $report['order_statuses']['completed']['years'][$year]['total_orders']);
            echo '<td>Year Sub Total:</td>';
            printf('<td>R %s</td>', $report['order_statuses']['completed']['years'][$year]['orders_sub_total']);
            echo '<td>Year Total:</td>';
            printf('<td>R %s</td>', $report['order_statuses']['completed']['years'][$year]['orders_total']);
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';

            foreach ($report['order_statuses']['completed']['years'][$year]['months'] as $month => $values) {
                $this->generate_month($month, $year, $report);
            }
        }

        /**
         * Generate a month
         *
         * @param int $month
         * @param int $year
         * @param array $report
         * @since 0.1.0
         */
        public function generate_month($month, $year, $report)
        {
            $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            echo '<table class="month-sales">';
            echo '<thead>';
            echo '<tr>';
            printf('<th colspan="%d">%s</th>', (4 + $days_in_month), date('F', mktime(0, 0, 0, $month, 1, $year)));
            echo '</tr>';
            echo '<tr>';
            echo '<th class="product-name">Product</th>';
            echo '<th class="product-sku">SKU</th>';
            for ($value = 1; $value <= $days_in_month; $value++) {
                printf('<th>%s</th>', $value);
            }
            echo '<th class="total-sold">Total Sold</th>';
            echo '<th class="total-earned">Total Earned</th>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($report['order_statuses']['completed']['years'][$year]['months'][$month]['products'] as $product => $values) {
                $this->generate_product_row($year, $month, $days_in_month, $product, $report);
            }
            $this->generate_totals_row($year, $month, $days_in_month, $report);
            echo '</tbody>';
            echo '</table>';
        }

        /**
         * Generate a product row
         *
         * @param int $year
         * @param int $month
         * @param int $dim Days in the month
         * @param int $product_id
         * @param array $report
         * @since 0.1.0
         */
        public function generate_product_row($year, $month, $dim, $product_id, $report)
        {
            $product_info = $report['order_statuses']['completed']['years'][$year]['months'][$month]['products'][$product_id];
            $product_days = $product_info['days'];
            $product_obj = wc_get_product((int)$product_id);
            echo '<tr>';
            $this->generate_table_cell($product_obj->get_title(), 'product-name');
            $this->generate_table_cell($product_obj->get_sku(), 'product-sku');
            for ($value = 1; $value <= $dim; $value++) {
                if (empty($product_days[$value])) {
                    $this->generate_empty_table_cell('product-value');
                } else {
                    $this->generate_table_cell($product_days[$value]['products_sold'], 'product-value');
                }
            }
            $this->generate_table_cell($product_info['products_sold'], 'products-sold');
            $this->generate_table_cell($product_info['orders_sub_total'], 'sub-total');
            echo '</tr>';
        }

        /**
         * Generate the totals row
         *
         * @param int $year
         * @param int $month
         * @param int $dim Days in the month
         * @param array $report
         * @since 0.1.0
         */
        public function generate_totals_row($year, $month, $dim, $report)
        {
            $month_days = $report['order_statuses']['completed']['years'][$year]['months'][$month]['days'];
            $month = $report['order_statuses']['completed']['years'][$year]['months'][$month];
            echo '<tr>';
            $this->generate_table_cell('Total Sold', 'products-sold');
            $this->generate_empty_table_cell('products-sold');
            for ($value = 1; $value <= $dim; $value++) {
                if (empty($month_days[$value])) {
                    $this->generate_empty_table_cell('products-sold');
                } else {
                    $this->generate_table_cell($month_days[$value]['products_sold'], 'products-sold');
                }
            }
            $this->generate_table_cell($month['products_sold'], 'total-products-sold');
            $this->generate_empty_table_cell('sub-total');
            echo '</tr>';
            echo '<tr>';
            $this->generate_table_cell('Total Earned', 'sub-total');
            $this->generate_empty_table_cell('sub-total');
            for ($value = 1; $value <= $dim; $value++) {
                if (empty($month_days[$value])) {
                    $this->generate_empty_table_cell('sub-total');
                } else {
                    $this->generate_table_cell('R ' . $month_days[$value]['orders_sub_total'], 'sub-total');
                }
            }
            $this->generate_empty_table_cell('sub-total');
            $this->generate_table_cell('R ' . $month['orders_sub_total'], 'grand-total');
        }

        /**
         * Generate a table cell
         *
         * @param mixed $value
         * @param mixed $classes
         * @since 0.1.0
         */
        public function generate_table_cell($value, $classes = '')
        {
            if (!empty($classes)) {
                printf('<td class="%1$s">%2$s</td>', $classes, $value);
            } else {
                printf('<td>%s</td>', $value);
            }
        }

        /**
         * Generate an empty table cell
         *
         * @param string $classes
         * @since 0.1.0
         */
        public function generate_empty_table_cell($classes = '')
        {
            if (!empty($classes)) {
                printf('<td class="%s"></td>', $classes);
            } else {
                echo '<td></td>';
            }
        }

        /**
         * Generate order status selection box
         *
         * @param array $order_statuses
         * @since 0.1.0
         */
        public function generate_order_status_selection($order_statuses)
        {
            if (isset($_POST['wc_report_order_statuses'])) {
                $selected_statuses = $_POST['wc_report_order_statuses'];
            } else {
                $selected_statuses = array(
                    'on-hold',
                    'processing',
                    'completed'
                );
            }

            echo '<select class="report_statuses" name="wc_report_order_statuses" id="wc_report_order_status" multiple="multiple">';
            foreach ($order_statuses as $order_status => $name) {
                printf('<option value="%1$s" %2$s>%3$s</option>', substr($order_status, 3), in_array(substr($order_status, 3), $selected_statuses) ? selected(true, true, false) : '', $name);
            }
            echo '</select>';
        }
    }
}