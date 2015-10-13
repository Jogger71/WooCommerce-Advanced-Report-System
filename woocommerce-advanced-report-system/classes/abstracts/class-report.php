<?php

/**
 * WC Advanced Report System Report Class
 *
 * @since 0.1.0
 */

if (!defined('ABSPATH')) {
    exit('Cheaters Detected!');
}

if (!class_exists('WCARS_Report')) {
    class WCARS_Report
    {
        /**
         * Report orders
         *
         * @var array|WP_Post $orders
         * @since 0.1.0
         */
        public $orders;

        /**
         * Report Order Statuses
         *
         * @var array|string|bool $order_statuses
         * @since 0.1.0
         */
        private $order_statuses;

        /**
         * WooCommerce Order Statuses
         *
         * @var array|string|bool $wc_order_statuses
         * @since 0.1.0
         */
        public $wc_order_statuses;

        /**
         * Report Products
         *
         * @var array|string|bool $products
         * @since 0.1.0
         */
        private $products;

        /**
         * Report Product Types
         *
         * @var array|string|bool $product_types
         * @since 0.1.0
         */
        private $product_types;

        /**
         * Report Start Date
         *
         * @var array|bool $start_date
         * @since 0.1.0
         */
        private $start_date;

        /**
         * Report End Date
         *
         * @var array|bool $end_date
         * @since 0.1.0
         */
        private $end_date;

        /**
         * Report Date
         *
         * @var DateTime $report_date
         * @since 0.1.0
         */
        private $report_date;

        /**
         * Report Array
         *
         * @var array $report_array
         * @since 0.1.0
         */
        public $report_array;


        /**
         * Class Constructor
         *
         * @param array $args
         */
        public function __construct($args)
        {
            $this->report_date = getdate();

            if (isset($args['order_statuses'])) {
                $this->set_order_statuses($args['order_statuses']);
            } else {
                $this->set_order_statuses(false);
            }

            if (isset($args['products'])) {
                $this->set_products($args['products']);
            } else {
                $this->set_products(false);
            }

            if (isset($args['product_types'])) {
                $this->set_product_types($args['product_types']);
            } else {
                $this->set_product_types(false);
            }

            if (isset($args['start_date'])) {
                $this->start_date = $args['start_date'];
            } else {
                $this->start_date = false;
            }

            if (isset($args['end_date'])) {
                $this->end_date = $args['end_date'];
            } else {
                $now = getdate();
                $this->end_date['year'] = $now['year'];
                $this->end_date['month'] = $now['mon'];
                $this->end_date['day'] = $now['mday'];
            }

            $this->wc_order_statuses = wc_get_order_statuses();

            $this->initialise_report_array();
        }

        /**
         * Set the order statuses
         *
         * @param array|string|bool $value
         * @return bool
         * @since 0.1.0
         */
        public function set_order_statuses($value)
        {
            if (is_numeric($value) || is_array($value) || false === $value) {
                $this->order_statuses = $value;
                return true;
            } else {
                return false;
            }
        }

        /**
         * Get the order statuses
         *
         * @return string|array|bool
         * @since 0.1.0
         */
        public function get_order_statuses()
        {
            return $this->order_statuses;
        }

        /**
         * Set the products
         *
         * @param array|integer|bool $value
         * @return bool
         * @since 0.1.0
         */
        public function set_products($value)
        {
            if (is_numeric($value) || is_array($value) || false === $value) {
                $this->products = $value;
                return true;
            } else {
                return false;
            }
        }

        /**
         * Get the products
         *
         * @return array|int|bool
         * @since 0.1.0
         */
        public function get_products()
        {
            return $this->products;
        }

        /**
         * Set the product types
         *
         * @param array|string|bool $value
         * @return bool
         * @since 0.1.0
         */
        public function set_product_types($value)
        {
            if (is_string($value) || is_array($value) || false === $value) {
                $this->product_types = $value;
                return true;
            } else {
                return false;
            }
        }

        /**
         * Get the product types
         *
         * @return array|string|bool
         * @since 0.1.0
         */
        public function get_product_types()
        {
            return $this->product_types;
        }

        /**
         * Set the start date
         *
         * @param array|int|bool $value
         * @param string $param
         * @return bool
         * @since 0.1.0
         */
        public function set_start_date($value, $param = 'array')
        {
            if ('array' === $param && is_array($value) && (isset($value['year']) || isset($value['month']) || isset($value['day']))) {
                foreach ($value as $key => $unit) {
                    $this->start_date[$key] = $unit;
                }
                return true;
            } else if (is_string($param) && ('year' === $param || 'month' === $param || 'day' === $param) && is_int($value)) {
                $this->start_date[$param] = $value;
                return true;
            } else if (false === $value) {
                $this->start_date = $value;
                return true;
            } else {
                $this->start_date['year'] = $this->report_date['year'];
                $this->start_date['month'] = $this->report_date['mon'];
                $this->start_date['day'] = $this->report_date['mday'];
                return true;
            }
        }

        /**
         * Get the start date
         *
         * @param string $param
         * @return int|array
         * @since 0.1.0
         */
        public function get_start_date($param = 'all')
        {
            if ('year' === $param || 'month' === $param || 'day' === $param) {
                return $this->start_date[$param];
            } else {
                return $this->start_date;
            }
        }

        /**
         * Set the end date
         *
         * @param array|int|bool $value
         * @param string $param
         * @return bool
         * @since 0.1.0
         */
        public function set_end_date($value, $param = 'array')
        {
            if ('array' === $param && is_array($value) && (isset($value['year']) || isset($value['month']) || isset($value['day']))) {
                foreach ($value as $key => $unit) {
                    $this->end_date[$key] = $unit;
                }
                return true;
            } else if (is_string($param) && ('year' === $param || 'month' === $param || 'day' === $param) && is_int($value)) {
                $this->end_date[$param] = $value;
                return true;
            } else if (false === $value) {
                $this->end_date = $value;
                return true;
            } else {
                $this->end_date['year'] = $this->report_date['year'];
                $this->end_date['month'] = $this->report_date['mon'];
                $this->end_date['day'] = $this->report_date['mday'];
                return true;
            }
        }

        /**
         * Get the end date
         *
         * @param string $param
         * @return int|array
         * @since 0.1.0
         */
        public function get_end_date($param = 'all')
        {
            if ('year' === $param || 'month' === $param || 'day' === $param) {
                return $this->end_date[$param];
            } else {
                return $this->end_date;
            }
        }

        /**
         * Initialise report date
         *
         * @since 0.1.0
         */
        public function initialise_report_date()
        {
            $this->report_date = getdate();
        }

        /**
         * Get all the orders of specific statuses
         *
         * @since 0.1.0
         */
        public function get_orders()
        {
            $order_status_post = array();

            if (false === $this->order_statuses) {
                $order_status_post = false;
            } else {
                foreach ($this->order_statuses as $order_status) {
                    $order_status_post[] = 'wc-' . $order_status;
                }
            }

            $orders_args = array(
                'date_query' => array(
                    array(
                        'after' => array(
                            'year' => $this->get_start_date('year'),
                            'month' => $this->get_start_date('month'),
                            'day' => $this->get_start_date('day')
                        ),
                        'before' => array(
                            'year' => $this->get_end_date('year'),
                            'month' => $this->get_end_date('month'),
                            'day' => $this->get_end_date('day'),
                        ),
                        'inclusive' => true,
                    ),
                ),
                'posts_per_page' => -1,
                'post_type' => 'shop_order',
                'post_status' => $order_status_post == false ? 'any' : $order_status_post,
                'order' => 'ASC',
                'orderby' => 'title',
            );

            $orders = new WP_Query($orders_args);
            $this->orders = $orders->posts;
        }

        /**
         * Generate the report info array
         *
         * @since 0.1.0
         */
        public function generate_report_info()
        {
            $this->report_array['report_info']['report_date'] = $this->report_date['mday'] . '/' . $this->report_date['mon'] . '/' . $this->report_date['year'] . ' at ' . $this->report_date['hours'] . ':' . $this->report_date['minutes'] . ':' . $this->report_date['seconds'];
            $this->report_array['report_info']['report_from'] = $this->start_date['day'] . '/' . $this->start_date['month'] . '/' . $this->start_date['year'];
            $this->report_array['report_info']['report_to'] = $this->end_date['day'] . '/' . $this->end_date['month'] . '/' . $this->end_date['year'];
        }

        /**
         * Generate report global totals
         *
         * @since 0.1.0
         */
        public function generate_global_totals()
        {
            $this->report_array['global_totals']['total_orders'] = count($this->orders);
            foreach ($this->orders as $order) {
                $wc_order = wc_get_order($order->ID);
                $this->report_array['global_totals']['orders_sub_total'] += round(($wc_order->get_total() - $wc_order->get_total_tax()), 2) - $wc_order->get_total_shipping();
                $this->report_array['global_totals']['orders_total'] += round((float)$wc_order->get_total(), 2) - $wc_order->get_total_shipping();
                $this->report_array['global_totals']['coupons_total'] += round((float)$wc_order->get_total_discount(false), 2);
                $this->report_array['global_totals']['products_sold'] += $wc_order->get_item_count();
            }

        }

        /**
         * Generate the order statuses array
         *
         * @return array
         * @since 0.1.0
         */
        public function generate_order_statuses_array()
        {
            if (false === $this->get_order_statuses()) {
                foreach ($this->wc_order_statuses as $slug => $name) {
                    $order_statuses[] = substr($slug, 3);
                }
            } else {
                $order_statuses = $this->get_order_statuses();
            }

            $status_array = array();

            foreach ($order_statuses as $order_status) {
                foreach ($this->orders as $order) {
                    $wc_order = wc_get_order($order->ID);

                    if ($wc_order->get_status() == $order_status) {
                        $order_date = getdate(strtotime($wc_order->order_date));
                        $status_array['debug']['order_date'] = $wc_order->order_date;
                        $status_array['debug']['order_date_gd'] = $order_date;
                        $status_array[$order_status]['total_orders']++;
                        $status_array[$order_status]['orders_sub_total'] += round(($wc_order->get_total() - $wc_order->get_total_tax()), 2) - $wc_order->get_total_shipping();
                        $status_array[$order_status]['orders_total'] += round((float)$wc_order->get_total(), 2) - $wc_order->get_total_shipping();
                        $status_array[$order_status]['coupons_total'] += round((float)$wc_order->get_total_discount(false), 2);
                        $status_array[$order_status]['products_sold'] += (int)$wc_order->get_item_count();
                    }
                }

                $status_array[$order_status]['years'] = $this->generate_year_array($order_status);

            }

            return $status_array;
        }

        /**
         * Generate the orders years array
         *
         * @param string $order_status
         * @return array
         * @since 0.1.0
         */
        public function generate_year_array($order_status)
        {
            $years_array = array();

            foreach ($this->orders as $order) {
                $wc_order = wc_get_order($order->ID);
                if ($wc_order->get_status() === $order_status) {
                    $order_date = getdate(strtotime($wc_order->order_date));
                    $years_array[$order_date['year']]['total_orders']++;
                    $years_array[$order_date['year']]['orders_sub_total'] += round(($wc_order->get_total() - $wc_order->get_total_tax()), 2) - $wc_order->get_total_shipping();
                    $years_array[$order_date['year']]['orders_total'] += round((float)$wc_order->get_total(), 2) - $wc_order->get_total_shipping();
                    $years_array[$order_date['year']]['coupons_total'] += round((float)$wc_order->get_total_discount(false), 2);
                    $years_array[$order_date['year']]['products_sold'] += (int)$wc_order->get_item_count();
                    $years_array[$order_date['year']]['months'] = empty($years_array[$order_date['year']]['months']) ? $this->generate_months_array($order_date['year'], $order_status) : $years_array[$order_date['year']]['months'];
                }
            }

            return $years_array;
        }

        /**
         * Generate the orders months array
         *
         * @param int $year
         * @param string $order_status
         * @return array
         * @since 0.1.0
         */
        public function generate_months_array($year, $order_status)
        {
            $months_array = array();

            foreach ($this->orders as $order) {
                $wc_order = wc_get_order($order->ID);
                if ($wc_order->get_status() === $order_status) {
                    $order_date = getdate(strtotime($wc_order->order_date));
                    if ($order_date['year'] == $year) {
                        $months_array[$order_date['mon']]['total_orders']++;
                        $months_array[$order_date['mon']]['orders_sub_total'] += round(($wc_order->get_total() - $wc_order->get_total_tax()), 2) - $wc_order->get_total_shipping();
                        $months_array[$order_date['mon']]['orders_total'] += round((float)$wc_order->get_total(), 2) - $wc_order->get_total_shipping();
                        $months_array[$order_date['mon']]['orders_total_no_coupons'] += $wc_order->get_total() - $wc_order->get_total_discount(false);
                        $months_array[$order_date['mon']]['coupons_total'] += round((float)$wc_order->get_total_discount(false), 2);
                        $months_array[$order_date['mon']]['products_sold'] += (int)$wc_order->get_item_count();
                        $months_array[$order_date['mon']]['products'] = empty($months_array[$order_date['mon']]['products']) ? $this->generate_product_array($year, $order_date['mon'], $order_status) : $months_array[$order_date['mon']]['products'];
                        $months_array[$order_date['mon']]['days'] = empty($months_array[$order_date['mon']]['days']) ? $this->generate_days_array($year, $order_date['mon'], $order_status) : $months_array[$order_date['mon']]['days'];
                    }
                }
            }

            return $months_array;
        }

        /**
         * Generate days array for the month
         *
         * @param int $year
         * @param int $month
         * @param string $order_status
         * @return array
         * @since 0.1.0
         */
        public function generate_days_array($year, $month, $order_status)
        {
            $days_array = array();

            foreach ($this->orders as $order) {
                $wc_order = wc_get_order($order->ID);
                if ($wc_order->get_status() === $order_status) {
                    $order_date = getdate(strtotime($wc_order->order_date));
                    if ($order_date['year'] === $year && $order_date['mon'] === $month) {
                        $days_array[$order_date['mday']]['total_orders']++;
                        $days_array[$order_date['mday']]['orders_sub_total'] += ($wc_order->get_total() - $wc_order->get_total_tax()) - $wc_order->get_total_shipping();
                        $days_array[$order_date['mday']]['orders_total'] += round((float)$wc_order->get_total(), 2) - $wc_order->get_total_shipping();
                        $days_array[$order_date['mday']]['coupons_total'] += round((float)$wc_order->get_total_discount(false), 2);
                        $days_array[$order_date['mday']]['products_sold'] += (int)$wc_order->get_item_count();
                    }
                }
            }

            return $days_array;
        }

        /**
         * Generate products array for the month
         *
         * @param int $year
         * @param int $month
         * @param string $order_status
         * @return array
         * @since 0.1.0
         */
        public function generate_product_array($year, $month, $order_status)
        {
            $products_array = array();

            foreach ($this->orders as $order) {
                $wc_order = wc_get_order($order->ID);
                if ($wc_order->get_status() === $order_status) {
                    $order_date = getdate(strtotime($wc_order->order_date));
                    if ($order_date['year'] === $year && $order_date['mon'] === $month) {
                        $items = $wc_order->get_items();
                        foreach ($items as $item) {
                            $products_array[$item['product_id']]['total_orders']++;
                            $products_array[$item['product_id']]['orders_sub_total'] += $wc_order->get_line_total($item);
                            $products_array[$item['product_id']]['orders_total'] += $wc_order->get_line_total($item, true);
                            $products_array[$item['product_id']]['coupons_total'] += 0;
                            $products_array[$item['product_id']]['products_sold'] += $wc_order->get_line_subtotal($item) == 0 ? 0 : (int)$item['qty'];
                            $products_array[$item['product_id']]['days'] = empty($products_array[$item['product_id']]['days']) ? $this->generate_product_days_array($year, $month, $item['product_id'], 'completed') : $products_array[$item['product_id']]['days'];
                        }
                    }
                }
            }

            return $products_array;
        }

        /**
         * Generate the product days report
         *
         * @param int $year
         * @param int $month
         * @param int $product_id
         * @param $order_status
         * @return array
         * @since 0.1.0
         */
        public function generate_product_days_array($year, $month, $product_id, $order_status)
        {
            $days_array = array();

            foreach ($this->orders as $order) {
                $wc_order = wc_get_order($order->ID);
                if ($wc_order->get_status() === $order_status) {
                    $order_date = getdate(strtotime($wc_order->order_date));
                    if ($order_date['year'] === $year && $order_date['mon'] === $month) {
                        $items = $wc_order->get_items('line_item');
                        foreach ($items as $item) {
                            if ($item['product_id'] === $product_id) {
                                $days_array[$order_date['mday']]['total_orders']++;
                                $days_array[$order_date['mday']]['orders_sub_total'] += ($wc_order->get_line_subtotal($item) - (($wc_order->get_total_discount(true) / $wc_order->get_item_count()) * (int)$item['qty']));
                                $days_array[$order_date['mday']]['orders_total'] += $wc_order->get_item_total($item, true);
                                $days_array[$order_date['mday']]['coupons_total'] += 0;
                                $days_array[$order_date['mday']]['products_sold'] += $wc_order->get_line_subtotal($item) == 0 ? 0 : (int)$item['qty'];
                            }
                        }
                    }
                }
            }

            return $days_array;
        }

        /**
         * Initialise the report array
         *
         * @since 0.1.0
         */
        private function initialise_report_array()
        {
            $this->report_array = array(
                'report_info' => array(),
                'global_totals' => array(
                    'total_orders' => 0,
                    'orders_sub_total' => 0.00,
                    'orders_total' => 0.00,
                    'coupons_total' => 0.00,
                    'products_sold' => 0
                ),
                'order_statuses' => array(
                    'total_orders' => 0,
                    'orders_sub_total' => 0.00,
                    'orders_total' => 0.00,
                    'coupons_total' => 0.00,
                    'products_sold' => 0
                )
            );
        }

        /**
         * Create report array
         *
         * @since 0.1.0
         */
        public function create_report_array()
        {
            $this->get_orders();
            $this->generate_report_info();
            $this->generate_global_totals();
            $this->report_array['order_statuses'] = $this->generate_order_statuses_array();
        }
    }
}