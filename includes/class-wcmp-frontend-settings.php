<?php

use WPO\WC\MyParcel\Compatibility\WC_Core as WCX;
use WPO\WC\MyParcel\Compatibility\Order as WCX_Order;
use WPO\WC\MyParcel\Compatibility\Product as WCX_Product;

/**
 * Frontend views
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'WooCommerce_MyParcel_Frontend_Settings' ) ) :

    class WooCommerce_MyParcel_Frontend_Settings {

        const DAYS_SATURDAY = 6;

        const CARRIER_CODE = 1;
        const CARRIER_NAME = "PostNL";
        const BASE_URL = "https://api.myparcel.nl/";

        private $settings;

        function __construct() {

            $this->settings = WooCommerce_MyParcel()->checkout_settings;
//			add_action( 'woocommerce_myparcel_frontend_settings', array($this, 'get_default_settings' ));
            //add_action( 'woocommerce_update_order_review_fragments', array( $this, 'order_review_fragments' ) );
        }

        /**
         * @return mixed
         */
        public function get_cutoff_time() {
            if (
                date_i18n( 'w' ) == self::DAYS_SATURDAY &&
                isset( $this->settings['saturday_cutoff_time'] )
            ) {
                return $this->settings['saturday_cutoff_time'];
            }

            return $this->settings['cutoff_time'];
        }

        /**
         * @return int
         */
        public function is_saturday_enabled() {
            return $this->settings['saturday_delivery_enabled'] ? 1 : 0;
        }

        /**
         * @return mixed
         */
        public function get_saturday_cutoff_time() {
            return $this->settings['saturday_cutoff_time'];
        }

        /**
         * @return mixed
         */
        public function get_dropoff_delay() {
            return $this->settings['dropoff_delay'];
        }

        /**
         * @return mixed
         */
        public function get_deliverydays_window() {
            return $this->settings['deliverydays_window'];
        }

        /**
         * @return string
         */
        public function get_dropoff_days() {
            return implode( ";", $this->settings['dropoff_days'] );
        }

        /**
         * @return string
         */
        public function get_api_url() {
            return self::BASE_URL;
        }

        /**
         * @return string
         */
        public function get_country_code() {
            return WC()->customer->get_shipping_country();
        }


        /**
         * @return bool
         */
        public function is_pickup_enabled() {
            return (bool) $this->settings['pickup_enabled'];
        }

        /**
         * @return string
         */
        public function get_price_pickup() {
            $price = $this->settings['pickup_fee'];
            $total_price = $this->get_total_price_with_tax($price);
            return $total_price;
        }

        /**
         * @return bool
         */
        public function is_signed_enabled() {
            return (bool) $this->settings['signed_enabled'];
        }

        /**
         * @return string
         */
        public function get_price_signature() {
            $price = $this->settings['signed_fee'];
            $total_price = $this->get_total_price_with_tax($price);
            return $total_price;
        }

        /**
         * @return bool
         */
        public function is_monday_delivery_enabled() {
            return (bool) $this->settings['monday_delivery'];
        }

        /**
         * @return null|string
         */
        public function get_checkout_display() {
            if ( isset( $this->settings['checkout_display'] ) ) {
                return $this->settings['checkout_display'];
            }

            return null;
        }

        /**
         * @param $price
         *
         * @return string
         */
        public function get_total_price_with_tax($price){
            $base_tax_rates     = WC_Tax::get_base_tax_rates( '');
            $base_tax_key       = key($base_tax_rates);
            $taxRate            = $base_tax_rates[$base_tax_key]['rate'];
            $tax                = $price * $taxRate / 100;
            $total_price        = money_format('%.2n', $price + $tax);

            return $total_price;
        }

    }

endif; // class_exists

return new WooCommerce_MyParcel_Frontend_Settings();
